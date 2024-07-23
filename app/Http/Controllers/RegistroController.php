<?php

namespace App\Http\Controllers;

use App\Models\Registrar;
use App\Models\Role;
use Illuminate\Http\Request;


class RegistroController extends Controller
{
    //

    public function index(){
        $roles = Role::all();
        return view('auth.register',compact('roles'));
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'rut' => 'required|string|max:20|unique:users,rut',
            'descripcion' => 'nullable|string|max:1000',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id', // Asumiendo que tienes una tabla roles
        ]);

            // Hash la contraseña antes de guardar
        $validatedData['password'] = bcrypt($validatedData['password']);

        // Crear un nuevo registro
        Registrar::create($validatedData);


        // Redirigir o devolver una respuesta
        return redirect()->route('dashboard')->with('success', 'Registro creado exitosamente');
    }

}
