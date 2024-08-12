<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Descripcion extends Model
{
    use HasFactory;


    protected $table = 'DESCRIPCION';



    protected $primaryKey = 'Material';

    protected $attributes = [
        'centro' => 'PDBU',
    ];

    public $timestamps = false;



    protected $fillable = [
        'Material',
        'Descripcion',
        'Marca',
    ];


}
