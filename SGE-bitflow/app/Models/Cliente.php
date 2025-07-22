<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contacto;


// app/Models/Cliente.php
class Cliente extends Model
{
    protected $fillable = [
        'razon_social',
        'rut',
        'nombre_fantasia',
        'giro',
        'direccion',
        'logo',
        'plazo_pago_habil_dias',
    ];

    public function contactos()
    {
        return $this->hasMany(Contacto::class);
    }

    public function facturas()
    {
        return $this->hasMany(Facturacion::class, 'id_cliente');
    }
}
