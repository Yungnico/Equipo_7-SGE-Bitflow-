<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paridad extends Model
{
    use HasFactory;
    protected $table = 'paridades';

    protected $fillable = ['moneda', 'valor', 'fecha'];
}
