<?php

namespace App\Http\Controllers;

use App\Models\paletizadoras;
use Illuminate\Http\Request;

use App\Traits\ObtieneLineasTrait;
use Yajra\DataTables\Facades\DataTables;

class ConfiguracionController extends Controller
{
    use ObtieneLineasTrait;
    /**
     * Display a listing of the resource.
     */
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
                <a href="'. route('configuracion.create') .'" class="btn btn-primary btn-sm">Nuevo</a>
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
