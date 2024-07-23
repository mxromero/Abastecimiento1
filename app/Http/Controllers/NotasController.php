<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class NotasController extends Controller
{
    //

    public function mesactual(){
        return view('Notas.mesactual');
    }

    public function NotaRecepcion(){

        $rut = Auth::user()->rut;
        $anio = date('Y');
        $mes = date('m');
        $productor = Auth::user()->descripcion;
        $mesActual = ucfirst(Carbon::now()->locale('es')->isoFormat('MMMM'));
        $totalKilos = 0;

        $rutaBase = env('PDF_BASE_PATH');
        $pdfFiles = $this->getPdfFiles($rutaBase.$rut.'\\'.$anio.'\\'.$mes.'\\');

        $data = collect($pdfFiles)->map(function ($pdfFile) use ($productor, $rut, $anio, $mes, &$totalKilos) {
            $parts = explode('_', pathinfo($pdfFile, PATHINFO_FILENAME));
            $downloadUrl = route('descargar.pdf', ['rut' => $rut, 'anio' => $anio, 'mes' => $mes, 'filename' => basename($pdfFile)]);
            $kilos = number_format($parts[7], 0, ',', '.');
            $fecha = \Carbon\Carbon::createFromFormat('Ymd', $parts[2])->format('d/m/Y');

            //revisar año anterior y mes anterior
            $totalKilos += $kilos;

            return [
                'Entrega' => $parts[0],
                'Lote' => $parts[1],
                'Fecha' => $fecha,
                'Guía' => $parts[3],
                'Productor' => $productor,
                'Especie' => $parts[5],
                'Variedad' => $parts[4],
                'Kilos' => $kilos,
                'Bins' => $parts[6],
                'Descarga' => '<a href="' . $downloadUrl . '" download="' . basename($pdfFile) . '">Descargar</a>'
            ];
           
           
        });

        $total = count($data);
    
        return view('Notas.mesactual', compact('data','mesActual','total','totalKilos'));


    }

    public function Liquidaciones(){
        return view('Dashboard.Liquidaciones');
    }


    private function getPdfFiles($path){
        try {
            $files = File::allFiles($path);
            $pdfFiles = [];
            foreach ($files as $file) {
                if ($file->getExtension() === 'pdf') {
                    $pdfFiles[] = $file->getFilename();
                }
            }

            return $pdfFiles;

        }  catch (\InvalidArgumentException $e) {
            Log::error($e->getMessage());
        }
    }


    public function HistorialNotaRecepcion($mesOffset = 1) // <-- Añadimos un parámetro opcional
    {
        $rut = Auth::user()->rut;
        $rutaBase = env('PDF_BASE_PATH') . $rut . '\\'; 

        $fechaActual = \Carbon\Carbon::now();
        $fechaActual->subMonths($mesOffset); // Restar meses según el parámetro
        $anio = $fechaActual->year;
        $mes = str_pad($fechaActual->month,2 ,'0', STR_PAD_LEFT);

        $mesActual = ucfirst($fechaActual->locale('es')->isoFormat('MMMM'));
        $totalKilos = 0;

        $anios = array_diff(scandir($rutaBase), ['..', '.']);
        
        $estructuraCarpetas = [];

        foreach ($anios as $anio) {
            $rutaAnio = $rutaBase . $anio . '\\';
            if (is_dir($rutaAnio)) {
                $meses = array_diff(scandir($rutaAnio), ['..', '.']);
                $estructuraCarpetas[$anio] = [];

                foreach ($meses as $mesIter) { 
                    $rutaMes = $rutaAnio . $mesIter . '\\';
                    if (is_dir($rutaMes) && $mesIter == $mes) { // <-- Mostrar solo el mes seleccionado
                        $pdfFiles = $this->getPdfFiles($rutaMes);
                        $estructuraCarpetas[$anio][$mesIter] = collect($pdfFiles)->map(function ($pdfFile) use ($rut, $anio, $mesIter, &$totalKilos) {
                            $parts = explode('_', pathinfo($pdfFile, PATHINFO_FILENAME));
                            $kilos = number_format($parts[7], 0, ',', '.');
                            $fecha = \Carbon\Carbon::createFromFormat('Ymd', $parts[2])->format('d/m/Y');
                            $downloadUrl = route('descargar.pdf', ['rut' => $rut, 'anio' => $anio, 'mes' => $mesIter, 'filename' => $pdfFile]);
                        

                            $totalKilos += $kilos;

                            return [
                                'Entrega' => $parts[0],
                                'Lote' => $parts[1],
                                'Fecha' => $fecha,
                                'Guía' => $parts[3],
                                'Productor' => Auth::user()->descripcion,
                                'Especie' => $parts[5],
                                'Variedad' => $parts[4],
                                'Kilos' => $kilos,
                                'Bins' => $parts[6],
                                'filename' => $pdfFile,
                                'Descarga' => $downloadUrl,
                            ];
                        });
                    }
                }
            }
        }
        // Asegurarse de que $estructuraCarpetas no esté vacío
        if (empty($estructuraCarpetas[$anio][$mes])) {
            return view('notas.mesanterior', ['error' => 'No se encontraron registros para el mes seleccionado.'], compact('estructuraCarpetas'));
        }

        return view('notas.mesanterior', compact('estructuraCarpetas','mesActual','totalKilos'));
    }


}
