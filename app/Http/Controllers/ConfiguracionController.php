<?php

namespace App\Http\Controllers;

use App\Models\paletizadoras;
use App\Services\SapService;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Traits\ObtieneLineasTrait;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ConfiguracionController extends Controller
{
    use ObtieneLineasTrait;
    /**
     * Display a listing of the resource.
     */

     protected $lineasProduccion;


    public function index()
    {
        $Lineas = $this->obtienePaletizadoras();

        return view('configuracion.lineas');
    }

    public function obtienePaletizadoras(){

        $config_linea = paletizadoras::select('paletizadora','NOrdPrev','VersionF','almacen','material_orden')
                                       ->orderBy('paletizadora')
                                       ->get();

        return DataTables::of($config_linea)
        ->addColumn('acciones', function ($linea) {
            return '
                <a href="'. route('configuracion.create', $linea->paletizadora) .'" class="btn btn-primary btn-sm">Editar</a>
                <a href="'. route('configuracion.destroy', $linea->paletizadora) .'" class="btn btn-secondary btn-sm" onclick="return confirm(\'¿Estás seguro de eliminar este registro?\')">Quitar</a>
            ';
        })
        ->rawColumns(['acciones']) // Permite que la columna 'acciones' contenga HTML
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $id)
    {

        $paletizadoras = DB::table('paletizadoras')
                            ->select('paletizadora as paletizadora_alias')
                            ->where('paletizadora', $id)
                            ->orderBy('paletizadora','asc')
                            ->distinct()
                            ->pluck('paletizadora_alias','paletizadora_alias')
                            ->values();

        $Lineas = $paletizadoras;

        return view('configuracion.create', compact('Lineas'));
    }

    public function valida_ordenes(Request $request){

        $ValidaOp = new SapService();


        $parametros = [
            'VgMatnr' => $request->Material,
            'VgPlnum' => $request->OrdenPrev,
            'VgVerid' => $request->Version,
        ];

        $responseArray = $ValidaOp->obtenerDatos($parametros); //

        return $responseArray;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


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
        //
    }

    /**
     * Updaconfiguracion\update\te the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        try{

                $validateData = $request->validate([
                                    'NOrdPrev' => 'numeric|required',
                                    'VersionF' => 'string|size:4|required',
                                    'material_orden' => 'string|max:10|required',
                                    'paletizadora' => 'integer|min:1|max:2|required',
                                    'almacen' => 'string|max:4|required',
                                ]);

               $paletizadora = paletizadoras::where('paletizadora',$id)->firstOrFail();

                // Transformar campos a mayúsculas/minúsculas
                $validateData['almacen'] = strtoupper($validateData['almacen']);
                $validateData['NOrdPrev'] = str_pad($validateData['NOrdPrev'],10,'0',STR_PAD_LEFT);
                $validateData['material_orden'] = strtoupper($validateData['material_orden']);
                $validateData['VersionF'] = trim(strtolower($validateData['VersionF']));
                $validateData['fecha'] = Carbon::now();

                $paletizadora->update($validateData);

                return redirect()->route('configuracion')->with('success', 'Datos de la paletizadora actualizados exitosamente.');

            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return back()->withErrors(['error' => 'Paletizadora no encontrada.']);
            } catch (\Illuminate\Validation\ValidationException $e) {
                return back()->withErrors($e->errors());
            } catch (\Exception $e) {
                return back()->withErrors(['error' => 'Ocurrió un error al actualizar la paletizadora. Por favor, inténtalo de nuevo.']);
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        try {
            //Busca el registro por el id
            $paletizadora = paletizadoras::findOrFail($id);

            //Elimina el registro
            $paletizadora->delete();

            //Redirigo la salida correcta
            return redirect()->route('configuracion.lineas')->with('success','registro eliminado correctamente.');
        } catch (\Throwable $th) {
            return redirect()->route('configuracion.lineas')->with('error','Ocurrio un error al eliminar el registro.');
        }

    }
}
