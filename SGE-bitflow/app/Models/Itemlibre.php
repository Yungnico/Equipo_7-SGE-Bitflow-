<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemLibre extends Model
{
    use HasFactory;

    protected $table = 'itemlibres';

    protected $fillable = [
        'nombre',
        'precio',
        'cantidad',
        'id_cotizacion',
    ];

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'id_cotizacion', 'id_cotizacion');
    }
}
