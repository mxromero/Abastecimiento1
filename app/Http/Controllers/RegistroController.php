<?php

namespace App\Http\Controllers;

use App\Models\Registrar;
use App\Notifications\UserRegistered;
use Illuminate\Http\Request;
use App\Models\Role; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;


class RegistroController extends Controller
{
    //

    public function index(){

        $roles = Role::all(); // Obtén todos los roles
        return view('auth.register', compact('roles'));
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'rut' => 'required|string|max:20|unique:users,rut',
            'descripcion' => 'nullable|string|max:1000',
            'email' => 'required|string|email|max:255|unique:users,email',
            'role_id' => 'required|exists:roles,id', // Asumiendo que tienes una tabla roles
        ]);

           // Generar una contraseña temporal
        $temporaryPassword = Str::random(10);
            // Hash la contraseña antes de guardar
        $validatedData['password'] = Hash::make($temporaryPassword);
        $validatedData['password_confirmation'] = true;

        // Crear un nuevo registro
        $user = Registrar::create($validatedData);

        Notification::route('mail', $user->email)->notify(new UserRegistered($user, $temporaryPassword));

        // Redirigir o devolver una respuesta
        return redirect()->route('dashboard')->with('success', 'Registro creado exitosamente');
    }

}
