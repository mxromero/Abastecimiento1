<?php

namespace App\Http\Controllers;

use App\Models\Registrar;
use Illuminate\Http\Request;


class RegistroController extends Controller
{
    //

    public function index(){
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'example_field' => 'required|string|max:255',
            // Agrega más reglas de validación según tus necesidades
        ]);

        // Crear un nuevo registro
        Registrar::create($validated);


        // Redirigir o devolver una respuesta
        return redirect()->route('some.route')->with('success', 'Registro creado exitosamente');
    }

}
