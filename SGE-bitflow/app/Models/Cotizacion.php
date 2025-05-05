<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    use HasFactory;

    protected $table = 'cotizaciones'; // nombre de la tabla

    protected $primaryKey = 'id_cotizacion'; // clave primaria personalizada

    protected $keyType = 'int'; // tipo de la clave primaria

    protected $fillable = [
        'codigo_cotizacion',
        'id_cliente',
        'total',
        'moneda',
        'estado',
        'fecha_cotizacion',
        'descuento',
        'email',
        'telefono',
        'moneda_cotizacion',
    ];
    

    // Definición de las relaciones ( cambiar cuando la javi actualice su parte )

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function itemsLibres()
    {
        return $this->hasMany(ItemLibre::class, 'id_cotizacion');
    }

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'cotizacion_servicio', 'cotizacion_id', 'servicio_id')
                    ->withPivot('cantidad', 'precio_unitario')
                    ->withTimestamps();
    }

}
