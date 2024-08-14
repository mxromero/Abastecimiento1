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
        'n_doc' => ' ',
        'exp_sap' => ' ',
        'li_mb' => ' ',
        'li_fq' => ' ',
        'cant2' => ' ',
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
        'cant1',
        'cant2',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


}
