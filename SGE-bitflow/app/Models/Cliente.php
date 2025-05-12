<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ContactoCliente;


// app/Models/Cliente.php
class Cliente extends Model
{
    protected $fillable = [
        'razon_social',
        'rut',
        'nombre_fantasia',
        'giro',
        'direccion',
        'logo'
    ];
    
    public function contactos()
    {
        return $this->hasMany(ContactoCliente::class);
    }

}
