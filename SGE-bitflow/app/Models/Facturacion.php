<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetalleFactura;
use App\Models\Cliente;

class Facturacion extends Model
{
    use HasFactory;

    protected $table = 'facturacion';

    protected $fillable = [
        'folio',
        'tipo_dte',
        'fecha_emision',
        'rut_receptor',
        'razon_social_receptor',
        'total_neto',
        'iva',
        'total',
        'estado',
    ];
    public function detalles()
    {
        return $this->hasMany(DetalleFactura::class, 'factura_id', 'id');
    }

    // public function cliente()
    // {
    //     return $this->belongsTo(Cliente::class);
    // }
}
