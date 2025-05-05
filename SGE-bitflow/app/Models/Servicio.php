<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;
    protected $fillable = ['nombre_servicio', 'descripcion', 'precio', 'moneda', 'estado'];


    public function cotizaciones()
    {
        return $this->belongsToMany(Cotizacion::class, 'cotizacion_servicio', 'servicio_id', 'cotizacion_id')
            ->withPivot('cantidad', 'precio_unitario')
            ->withTimestamps();
    }
}
