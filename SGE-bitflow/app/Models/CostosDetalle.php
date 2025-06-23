<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostosDetalle extends Model
{
    protected $table = 'costos_detalle';

    protected $fillable = [
        'costo_id',
        'moneda_id',
        'monto',
        'fecha',
        'transferencias_bancarias_id',
    ];

    public function costo()
    {
        return $this->belongsTo(Costos::class);
    }

    public function moneda()
    {
        return $this->belongsTo(Paridad::class, 'moneda_id');
    }

    public function transferencia()
    {
        return $this->belongsTo(TransferenciaBancaria::class, 'transferencias_bancarias_id');
    }
}
