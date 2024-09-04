<?php

namespace App\Http\Controllers;

use App\Models\paletizadoras;
use App\Services\SapService;

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
                <a href="'. route('configuracion.create', $linea->paletizadora) .'" class="btn btn-primary btn-sm">Nuevo</a>
                <a href="'. route('configuracion.update', $linea->paletizadora) .'" class="btn btn-info btn-sm">Editar</a>
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
                            ->pluck('paletizadora_alias','paletizadora_alias');
        $paletizadoras->prepend('','0'); //Agrega espacio en blanco

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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        try{


                $validateData = $request->validate([
                                    'NOrdPrev' => 'numeric|min:5|max:5|required',
                                    'VersionF' => 'string|min:4|max:4|required',
                                    'material_orden' => 'string|max:10|required',
                                    'paletizadora' => 'integer|min:1|max:2|required',
                                    'almacen' => 'regex:/^[A-Z]{2}[0-9]{2}$/|required',
                                ]);

                $paletizadora = paletizadoras::findOrFail($id);

                // Transformar campos a mayúsculas/minúsculas
                $validateData['almacen'] = strtoupper($validateData['almacen']);
                $validateData['material_orden'] = strtoupper($validateData['material_orden']);
                $validateData['VersionF'] = strtolower($validateData['VersionF']);

                $paletizadora->update($validateData);

                return redirect()->route('configuracion.lineas')->with('success', 'Datos de la paletizadora actualizados exitosamente.');

            } catch (\Exception $e) {

                return back()->withErrors(['error' => 'Ocurrió un error al actualizar la paletizadora. Por favor, inténtalo de nuevo.']);
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
