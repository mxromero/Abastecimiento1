<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class paletizadoras extends Model
{
    use HasFactory;

    protected $table = 'PALETIZADORAS';


    protected $primaryKey = 'paletizadora';

    protected $attributes = [
        'centro' => 'PDBU',
    ];


    public $timestamps = false;

    protected $fillable = [
        'paletizadora',
        'NOrdPrev',
        'fecha',
        'VersionF',
        'centro',
        'almacen',
        'ult_uma',
        'material_orden',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];
}
