<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registrar extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'rut',
        'name',
        'descripcion',
        'email',
        'password',
        'role_id',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];
}
