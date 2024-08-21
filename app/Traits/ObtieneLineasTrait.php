<?php

namespace App\Traits;

use App\Models\paletizadoras;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

trait ObtieneLineasTrait
{
    public function obtenerLineas()
    {
        $Lineas = DB::table('PALETIZADORAS')
            ->select('paletizadora')
            ->orderBy('paletizadora')
            ->get();

        $Lineas->prepend((object)['paletizadora' => '']);

        return $Lineas;
    }


}
