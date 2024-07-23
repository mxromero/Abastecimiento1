<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ControllerDescargaPdf extends Controller
{
    public function descargarPdf($rut, $anio, $mes, $filename)
    {
        $rutaBase = env('PDF_BASE_PATH');
        $rutaCompleta = $rutaBase . $rut . '\\' . $anio . '\\' . $mes . '\\' . $filename;

        if (!file_exists($rutaCompleta)) {
            abort(404);
        }

        return response()->file($rutaCompleta, ['Content-Disposition' => 'attachment; filename="' . $filename . '"']);
    }
    /**
     * Show the form for creating the resource.
     */
    public function create(): never
    {
        abort(404);
    }

    /**
     * Store the newly created resource in storage.
     */
    public function store(Request $request): never
    {
        abort(404);
    }

    /**
     * Display the resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the resource from storage.
     */
    public function destroy(): never
    {
        abort(404);
    }
}
