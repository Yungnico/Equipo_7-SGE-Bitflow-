<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UF extends Model
{
    protected $table = 'ufs'; // Asegúrate que el nombre coincide con tu tabla

    protected $fillable = ['valor'];
}
