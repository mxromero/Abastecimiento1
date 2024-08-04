<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //

    public function index(){
        $user = Auth::user();

        $ExpSAP = DB::table('produccion')->where('exp_sap','<>','X')->count();
        
        $OPCargadas = DB::table('paletizadoras')->orderBy('paletizadora')->get();

        return view('dashboard', compact('user','ExpSAP','OPCargadas'));
    }

    public function redirectToDashboard(){
        return redirect()->route('dashboard');
    }
}
