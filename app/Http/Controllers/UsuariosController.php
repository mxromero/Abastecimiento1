<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    //

    public function index(){
        $usuarios = User::all();
        return view('usuarios.index', compact('usuarios'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('usuarios.editar', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'rut' => 'required|string|max:10|unique:users,rut,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->name = $validatedData['name'];;
        $user->rut = $validatedData['rut'];;
        $user->email = $validatedData['email'];

        $user->save();

        return redirect()->route('usuarios')->with('success', 'Perfil actualizado exitosamente');
    }

    public function destroy($id){

        $user =   User::findOrFail($id);
        $user->delete();

        return redirect()->route('usuarios')->with('success', 'Usuario eliminado exitosamente');

    }
}
