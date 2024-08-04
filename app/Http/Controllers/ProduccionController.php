<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProduccionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $paletizadoras = DB::table('paletizadoras')->pluck('paletizadora', 'paletizadora');
        $materiales = DB::table('produccion')->pluck('material', 'material');

        $resultados = [];
              
        return view('produccion.index', compact('paletizadoras','materiales','resultados'));
    }

    /**
     * Show the form for creating a new resource.
     */

     public function buscar(Request $request){

        $resultados = [];

        $query = DB::table('produccion');

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

        // Obtener los resultados
        $resultados = $query->get();

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
