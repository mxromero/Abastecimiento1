<?php

namespace App\Http\Controllers;

use App\Models\Descripcion;
use App\Models\limiteprd;
use App\Models\paletizadoras;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use App\Models\Produccion;
use App\Services\ImpresionService;
use App\Traits\ObtieneLineasTrait;

class ProduccionController extends Controller
{
    use ObtieneLineasTrait;
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
        try{
            $Lineas = $this->obtenerLineas();


        } catch (\Exception $e) {
                Log::error($e);
            return redirect()->back()->with('error', 'Ocurrió un error al obtener los datos de la base de datos.');
        }


        return view('produccion.creacion', compact('Lineas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $Lineas = $this->obtenerLineas();

        //Modifico los datos antes de validar.
        $request->merge([
            'uma' => str_pad($request['uma'], 20, '0', STR_PAD_LEFT),
            'orden_prv' => $request['orden_prv'],
            'version_f' => $request['version_f'],
            'cant_a' => $request['cant_a'],
            'cod_linea' => $request['cod_linea'],
            'batch_a' => $request['batch_a'],
            'cant_a' => $request['cant_a'],
        ]);

        //Realizo la validación
        $ValidateData = $request->validate([
            'uma' => 'required|string|max:20|unique:produccion,uma',
            'material' => 'required|string|max:8',
            'lote' => 'required|string',
            'orden_prv' => 'required|string',
            'version_f' => 'required|string',
            'fecha' => 'required|date',
            'hora' => 'required|string',
            'cant_a' => 'required|string|min:1',
            'cod_linea' => 'required|integer',
            'almacen' => 'required|string|max:4',
            'batch_a' => 'required|string',
        ]);

        $ValidateData['NOrdPrev'] = $ValidateData['orden_prv'];
        $ValidateData['VersionF'] = $ValidateData['version_f'];
        $ValidateData['cantidad'] = $ValidateData['cant_a'];
        $ValidateData['paletizadora'] = $ValidateData['cod_linea'];
        $ValidateData['batch1'] = $ValidateData['batch_a'];
        $ValidateData['cant1'] = $ValidateData['cantidad'];
        $ValidateData['uma'] = str_pad($ValidateData['uma'], 20, '0', STR_PAD_LEFT);

        //Genera el insert
        Produccion::create($ValidateData);

        $this->printer($ValidateData['uma']);

        return view('produccion.creacion', compact('Lineas'));

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function obtenerDatosLinea(Request $request)
    {
        try {
            $SAP_lineas = Paletizadoras::Select('paletizadora','NOrdPrev','VersionF','material_orden','almacen')->where('paletizadora', $request->cod_linea)->first();

            if (!$SAP_lineas) {
                return response()->json(['error' => 'No se encontraron datos en PALETIZADORAS.'], 404);
            }


           $Uma = Produccion::select('uma', 'material', 'lote', 'batch1')
                ->where('paletizadora', $request->cod_linea)
                ->where('NOrdPrev', $SAP_lineas->NOrdPrev)
                ->where('VersionF', $SAP_lineas->VersionF)
                ->where('material', trim($SAP_lineas->material_orden))
                ->orderByDesc('uma')
                ->first();

            if (!$Uma) {
                return response()->json(['error' => 'No se encontraron datos en produccion.'], 404);
            }

            $limiteProduccion = limiteprd::Select('cajas', 'descripcion','unm')->where('material',trim($SAP_lineas->material_orden))->first();



            if(!$limiteProduccion){
                return response()->json(['error' => 'No se encontraron datos de limite produccion'], 404);
            }

            $salida_combinada = array_merge($SAP_lineas->toArray(), $Uma->toArray(), $limiteProduccion->toArray());

            return response()->json($salida_combinada);

        } catch (ModelNotFoundException $e) { // Capturar excepción específica
            return response()->json(['error' => 'No se encontró el registro.'], 404);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json(['error' => 'Ocurrió un error al procesar la solicitud.'], 500);
        }
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
                             ->with('success', 'Unidad Manipulación eliminada con éxito.');

        } catch (ModelNotFoundException $e) {
            return redirect()->route('produccion.buscar', session()->get('search_params', []))
                             ->withErrors(['error' => 'La producción no fue encontrada.']);
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->withErrors(['error' => 'Error al eliminar la producción.']);
        }


    }

    public function printer(string $id){

        try{
            $nombre_archivo = env('NOMBRE_ARCHIVO_TXT');    //Nombre del archivo
            $ruta_archivo = env('RUTA_ARCHIVO_TXT');        //Ruta del archivo
            $ipImpresora = env('IMPRESORA_IP');             //Ip impresora

            //Unidad Manipulación de largo de 20
            $exidv = $id ? str_pad((string) $id, 20, '0', STR_PAD_LEFT) : null;
            //Ruta completa del archivo ZPL
            $full_path = storage_path($ruta_archivo . $nombre_archivo);
            //Valida si existe el archivo
            if(!file_exists($full_path)){
                return redirect()->back()->withErrors(['error' => 'No existe el archivo de impresión']);
            }
            //Busca el detalle a la unidad de manipulación
            $datos = Produccion::where('uma',$exidv)->firstOrFail();
            // Lee el contenido del archivo codigo ZPL
            $contenido = file_get_contents($full_path);
            //Encuentra la unida de mendida desde el materail
            $unidadMedida = $this->buscar_unidadMedida($datos->material);
            $cantidad = $this->buscar_cantidad($datos->material)?? $datos->cantidad;

            //Rescato la descripcion del material desde tabla descripcion
            $tablaDescrip = DB::table('DESCRIPCION')
                                ->select('Descripcion','marca')
                                ->where('material',$datos->material)
                                ->first();
            //Rescato fecha y la marca
            $marcaFecha = $this->buscar_marcaFecha($datos->material,$datos) ?? $tablaDescrip->marca;

            //Reemplaza codigo ZPL que se encuentra entre [] con el texto desde base de datos
            $reemplazos = [
                'UMA_TEXTO' => $datos->uma,
                'LOTE' => $datos->lote,
                'MATERIAL' => $datos->material,
                'PALETIZADORA' => $datos->paletizadora,
                'BATCH' => trim($datos->batch1),
                'FECHA_MARCA' => $marcaFecha,
                'CANTIDAD' => $cantidad,
                'HORA' => trim($datos->hora),
                'FECHA' => Carbon::parse($datos->fecha)->format('d/m/Y'),
                'DESCRIPCION' => $tablaDescrip->Descripcion,
                'UNIDAD_MEDIDA' => $unidadMedida,
                'UMA_12' => substr($datos->uma,0,12),
                'UMA_1' => substr($datos->uma,-1)
            ];

            //Busca cualquier palabra entre [];
            $patron = '/\[(\w+)\]/';

            //Busca y reemplaza los datos desde ZPL y txt
            $cadenaReemplazada = preg_replace_callback($patron, function($coincidencias) use ($reemplazos) {
                $parametro = $coincidencias[1];                      // Obtiene el nombre del parámetro
                return $reemplazos[$parametro] ?? $coincidencias[0]; // Reemplaza si existe, sino mantiene original
            }, $contenido);

            //Envio ZPL a Impresora de Red
            $zpl = $cadenaReemplazada;
            $impresionService = new ImpresionService();
            $impresionService->imprimir($zpl, $ipImpresora);

            return redirect()->route('produccion.buscar', session()->get('search_params',[]))
                              ->with('success', '¡El registro fue impreso exitosamente!');

        }catch(ModelNotFoundException $e){
             return redirect()->route('produccion.buscar', session()->get('search_params', []))
                                        ->withErrors(['error' => 'La producción no fue encontrada.']);
        }catch (\Illuminate\Database\QueryException $e) {
             return redirect()->back()->withErrors(['error' => 'Error al eliminar la producción.']);
        }

    }

    private function buscar_unidadMedida($material){
        $letrasMaterial = substr($material,0,3);
        $letrasMaterial = strtoupper($letrasMaterial);

        if($letrasMaterial === "SPS"){
            $unidadMedida = "KG";
        } elseif(strpos($letrasMaterial,'S') === 0){
            $unidadMedida = "CJ";
        }else{
            $unidadMedida = "LT";
        }
        return $unidadMedida;
    }

    private function buscar_cantidad($material){
        $letrasMaterial = substr($material,0,3);
        $cantidad = null;

        if($letrasMaterial === "SPS"){
            $cantidad = "240";
        }
        return $cantidad;
    }

    private function buscar_marcaFecha($material, $datos){
        //Esta fecha es fecha calulcada x 2 años y si el material
        //es SPS se deben agregar 2 años y cambiar el texto marca
        //por Fecha Vencimiento.
        $letrasMaterial = substr($material,0,3);
        $fecha = null;
        $anios = env('ANIO_VENCIMIENTO');

        if($letrasMaterial === "SPS"){
            $fechaCarbon = Carbon::parse($datos->fecha);
            $nuevaFecha = $fechaCarbon->addYears($anios);
            $fecha =  $nuevaFecha->format('d-m-Y');
        }
        return $fecha;
    }


}

