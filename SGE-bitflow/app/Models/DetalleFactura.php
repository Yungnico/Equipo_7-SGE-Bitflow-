<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Facturacion;

class DetalleFactura extends Model
{
    use HasFactory;

    protected $table = 'detalle_factura';

    protected $fillable = [
        'factura_id',
        'descripcion',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    public function factura()
    {
        return $this->belongsTo(Facturacion::class, 'factura_id', 'id');
    }
}
