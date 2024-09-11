<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class limiteprd extends Model
{
    use HasFactory;


    protected $table = 'LIMITEPRD';

    protected $primaryKey = 'material';

    public $timestamps = false;


    protected $fillable = [
        'material',
        'descripcion',
        'unm',
        'cajas',
    ];

}
