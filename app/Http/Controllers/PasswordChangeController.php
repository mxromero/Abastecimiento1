<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PasswordChangeController extends Controller
{
    public function showChangeForm()
    {
        return view('auth.passwords.change');
    }

    public function change(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->password_temporary = false;
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Contraseña cambiada exitosamente.');
    }
}
