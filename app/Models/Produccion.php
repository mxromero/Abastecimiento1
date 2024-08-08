<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produccion extends Model
{
    use HasFactory;

    protected $table = 'produccion';


    protected $primaryKey = 'uma';

    protected $attributes = [
        'centro' => 'PDBU',
    ];


    public $timestamps = false;

    protected $fillable = [
        'uma',
        'material',
        'lote',
        'centro',
        'almacen',
        'NOrdPrev',
        'VersionF',
        'fecha',
        'hora',
        'cantidad',
        'paletizadora',
        'batch1',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


}
