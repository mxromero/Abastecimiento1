<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Models\Produccion;

class ProduccionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {

        $paletizadoras = DB::table('paletizadoras')
                            ->select('paletizadora as paletizadora_alias')
                            ->orderBy('paletizadora','asc')
                            ->distinct()
                            ->pluck('paletizadora_alias','paletizadora_alias');
        $paletizadoras->prepend('',''); //Agrega espacio en blanco

        $materiales = DB::table('produccion')
                            ->select('material as material_alias')
                            ->orderBy('material','asc')
                            ->distinct()
                            ->pluck('material_alias','material_alias');
        $materiales->prepend('',''); //Agrega espacio en blanco

        $resultados = [];

        return view('produccion.index', compact('paletizadoras','materiales','resultados'));
    }



     public function buscar(Request $request){

            $resultados = [];


            $query = DB::table('produccion')
                        ->select('material','lote','uma','cantidad','batch1','fecha','hora','paletizadora','NordPrev','VersionF');

            // Aplicar filtros basados en los parámetros proporcionados
            if ($request->has('fecha_inicial') && $request->has('fecha_final')) {
                $query->whereBetween('fecha', [$request->fecha_inicial, $request->fecha_final]);
            }
            if ($request->has('paletizadora')) {
                $query->where('paletizadora', $request->paletizadora);
            }
            if ($request->has('material')) {
                $query->where('material', $request->material);
            }

            $data = $query->get()->map(function ($row) {
                $row->fecha =  $row->fecha ? Carbon::parse($row->fecha)->format('d/m/Y') : '';
                $row->uma = ltrim($row->uma,'0');
                $row->action = '<a href="' . route('produccion.edit', $row->uma) . '"><i class="fas fa-pencil" aria-hidden="true"></i></a>|'
                            .  '<a href="' . route('produccion.printer', $row->uma) . '"><i class="fas fa-print" aria-hidden="true"></i></a>|'
                            .  '<form action="' . route('produccion.destroy', $row->uma) . '" method="POST" style="display: inline;" onsubmit="return confirmarEliminacion(event)>' .
                                    csrf_field() .
                                    method_field('DELETE') .
                            '<button type="button
                            " class="btn btn-link text-danger" ><i class="fas fa-trash" aria-hidden="true"></i></button>' .
                            '</form>';
                return $row;
            });

            session()->put('search_params',$request->query());

            return view('produccion.buscar',compact('data'));


     }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $exidv = $id ? str_pad((string) $id, 20, '0', STR_PAD_LEFT) : null;


        if ($exidv && !preg_match('/^\d{1,20}$/', $exidv)) {
            return redirect()->back()->withErrors(['uma' => 'El formato del UMA no es válido.']);
        }

        $data = Produccion::Select('uma','material','lote','centro','almacen','NOrdPrev','VersionF',
                                    'fecha','hora','cantidad','paletizadora','exp_sap','n_doc','li_mb','li_fq','batch1','cant1')
                                    ->where('uma', $exidv)
                                    ->get()
                                    ->map(function($row){
                                            $row->uma = ltrim($row->uma,'0');
                                            $row->fecha =  $row->fecha ? Carbon::parse($row->fecha)->format('Y-m-d') : '';
                                            $row->hora = $row->hora ? Carbon::parse($row->hora)->format('H:i') : '';
                                            return $row;
                                    });

            return view('produccion.editar', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $exidv = $id ? str_pad((string) $id, 20, '0', STR_PAD_LEFT) : null;

        $request->validate([
                    'uma' => 'required|string|max:20',
                    'material' => 'required|string',
                    'lote' => 'required|string',
                    'almacen' => 'required|string|max:4',
                    'NOrdPrev' => 'required|string|max:10',
                    'VersionF' => 'required|string|max:4',
        ]);

        $produccion = Produccion::where('uma',$exidv)->firstOrFail();
        $produccion->exp_sap = '';
        $produccion->fill($request->all());
        $produccion->save();

        session()->forget('success');

        return redirect()->route('produccion.buscar', session()->get('search_params', []))
        ->with('success', 'Producción actualizada con éxito.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $exidv = $id ? str_pad((string) $id, 20, '0', STR_PAD_LEFT) : null;

        try {
            $produccion = Produccion::findOrFail($exidv);
            $produccion->delete();

            return redirect()->route('produccion.buscar', session()->get('search_params', []))
                             ->with('success', 'Producción eliminada con éxito.');

        } catch (ModelNotFoundException $e) {
            return redirect()->route('produccion.buscar', session()->get('search_params', []))
                             ->withErrors(['error' => 'La producción no fue encontrada.']);
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->withErrors(['error' => 'Error al eliminar la producción.']);
        }


    }

    public function printer(string $id){

        try{
            $exidv = $id ? str_pad((string) $id, 20, '0', STR_PAD_LEFT) : null;

            $nombre_archivo = env('NOMBRE_ARCHIVO_TXT');
            $ruta_archivo = env('RUTA_ARCHIVO_TXT');
            $ipImpresora = env('IMPRESORA_IP');

            $full_path = $ruta_archivo . '/' . $nombre_archivo;

            if(!file_exists($full_path)){
                return redirect()->back()->withErrors(['error' => 'No existe el archivo de impresión']);
            }

            $datos = Produccion::where('uma',$exidv)->firstOrFail();
            // Lee el contenido del archivo
            $contenido = file_get_contents($full_path);
            $reemplazos = [
                    'MATERIAL' => '',
                    'LOTE' => '',
                    'DESCRIPCION' => '',
                    'CANTIDAD' => '',
                    'FECHA' => '',
            ];

            $patron = '/\[(\w+)\]/'; //Busca cualquier palabra entre [];

            $cadenaReemplazada = preg_replace_callback($patron, function($coincidencias) use ($reemplazos) {
                $parametro = $coincidencias[1]; // Obtiene el nombre del parámetro
                return $reemplazos[$parametro] ?? $coincidencias[0]; // Reemplaza si existe, sino mantiene original
            }, $contenido);

            echo $cadenaReemplazada;


        }catch(ModelNotFoundException $e){
            return redirect()->route('produccion.buscar', session()->get('search_params', []))
                                        ->withErrors(['error' => 'La producción no fue encontrada.']);
        }catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->withErrors(['error' => 'Error al eliminar la producción.']);
        }

    }
}
