<?php

namespace App\Http\Controllers;

use App\Models\Descripcion;
use App\Models\Produccion;
use Illuminate\Http\Request;

class ReclasificadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('reclasificado.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $request->merge([
            'unidadManipulacion' => str_pad($request->input('unidadManipulacion'), 20, '0', STR_PAD_LEFT)
        ]);

        $validate = $request->validate([
            'unidadManipulacion' => 'numeric|exists:produccion,uma',
        ]);

        $produccion = Produccion::where('uma', $validate['unidadManipulacion'])
                                ->select('uma', 'lote','material', 'almacen', 'NordPrev', 'VersionF', 'cantidad', 'paletizadora', 'batch1', 'fecha', 'hora')
                                ->get();

        $material = trim($produccion->first()->material);

        $descripcion = Descripcion::where('Material', $material)
                                  ->select('Descripcion')
                                  ->first();

        $texto = $descripcion->Descripcion ?? 'Descripción no encontrada';

        $salida = [];

        foreach ($produccion as $item) {
            $salida[] = [
                'uma' => $item->uma,
                'lote' => $item->lote,
                'material' => $item->material,
                'almacen' => $item->almacen,
                'NordPrev' => $item->NordPrev,
                'VersionF' => $item->VersionF,
                'cantidad' => $item->cantidad,
                'paletizadora' => $item->paletizadora,
                'batch1' => $item->batch1,
                'fecha' => $item->fecha,
                'hora' => $item->hora,
                'descripcion' => $texto
            ];
        }

        return view('reclasificado.create', compact('salida'));
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

        try {

            $unidadManipulacion = str_pad($id,20,'0',STR_PAD_LEFT);

            $request->merge([
                'uma' => str_pad($request->input('uma'), 20, '0', STR_PAD_LEFT),
                'NordPrev' => str_pad($request->input('NordPrev'), 10, '0', STR_PAD_LEFT),
            ]);

            $request->validate([
                    'uma' => 'max:20|required',
                    'material' => 'string|required',
                    'lote' => 'string|required',
                    'almacen' => 'string|required|max:4',

                ]);

            $produccion = produccion::where('uma',$unidadManipulacion)->firstOrFail();


            $produccion->update($request->all());


            return redirect()->route('reclasificado.index')->with('success','Reclasificado actualizado correctamente.');

        } catch (\Throwable $th) {
            //throw $th;
            return back()->withErrors('errors','No fue posible realizar la actualización correctamente.');

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
