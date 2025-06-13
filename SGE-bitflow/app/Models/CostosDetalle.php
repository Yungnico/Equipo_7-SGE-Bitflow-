<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostosDetalle extends Model
{
    protected $table = 'costos_detalle';

    protected $fillable = [
        'costo_id',
        'año',
        'moneda_id',
        'periodos',
        'monto'
    ];

    public function costo()
    {
        return $this->belongsTo(Costos::class);
    }
    public function moneda()
    {
        return $this->belongsTo(Paridad::class, 'moneda_id');
    }
}
