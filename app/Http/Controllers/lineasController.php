<?php

namespace App\Http\Controllers;

use App\Models\paletizadoras;
use Carbon\Carbon;
use Illuminate\Http\Request;

class lineasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('lineas.index');
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

        $validate = $request->validate([
            'paletizadora' => 'integer|unique:paletizadoras,paletizadora'
        ]);

        $validate['NOrdPrev'] = $validate['NOrdPrev'] ?? 0;
        $validate['fecha'] = $validate['fecha'] ?? Carbon::now();
        $validate['VersionF'] = $validate['VersionF'] ?? 0;
        $validate['centro'] = $validate['centro'] ?? 'PDBU';
        $validate['almacen'] = $validate['almacen'] ?? 0;
        $validate['ult_uma'] = $validate['ult_uma'] ?? '';
        $validate['material_orden'] = $validate['material_orden'] ?? '';

        paletizadoras::create($validate);

        return redirect()->route('lineas.index')->with('success','Línea agregada correctamente.');

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
