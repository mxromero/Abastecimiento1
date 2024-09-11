<?php

namespace App\Http\Controllers;

use App\Models\Descripcion;
use App\Models\limiteprd;
use Illuminate\Http\Request;

class CantidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resultado = [];

        $materiales = Descripcion::select('material')
                                   ->distinct()
                                   ->orderBy('material')
                                   ->get()
                                   ->pluck('material')
                                   ->map(function ($material){
                                        return trim($material);
                                   })
                                   ->toArray();

        foreach ($materiales as $material) {
            $limite = limiteprd::where('material', $material)->first();

            if($limite){
                $resultado[$material] = $limite->cajas;
            }else{
               continue;
            }

        }

        return view('cantidad.index', compact('materiales','resultado'));
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

        try {
            $request->validate([
                'material' => 'string|required|max:10',
                'cantidad' => 'integer|required',
            ]);

            $material = $request->input('material');

            $limiteMaterial = limiteprd::where('material', $material)->first();

            if($limiteMaterial){
                $actualizado = $limiteMaterial->update([
                    'cajas' => $request->input('cantidad'),
                ]);


                if ($actualizado) {
                    // Si la actualización fue exitosa
                    return response()->json(['message' => 'Registro actualizado exitosamente.']);
                } else {
                    // Si la actualización falló
                    return response()->json(['message' => 'No se pudo actualizar el registro.'], 500);
                }


            }else{

                $nuevoRegistro = limiteprd::create([
                    'material' => $material,
                    'descripcion' => '',
                    'cajas' => $request->input('cantidad'),
                ]);


                if ($nuevoRegistro) {
                    // Si la creación fue exitosa
                    return redirect()->route('cantidad.index')->with('success', 'Cantidad del material actualizados exitosamente.');
                } else {
                    // Si la creación falló
                    return redirect()->route('cantidad.index')->with('error', 'ocurrio un problema al momento de actulizar.');
                }
            }


        } catch (\Throwable $th) {
            return redirect()->route('cantidad.index')->with('error',$th->getMessage());
        }


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
