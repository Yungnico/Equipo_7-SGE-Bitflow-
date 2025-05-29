<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paridad extends Model
{
    protected $table = 'paridades';
    protected $fillable = ['moneda', 'valor', 'fecha'];

    protected $dates = ['fecha'];

    public static function obtenerActual($moneda)
    {
        return self::where('moneda', $moneda)
            ->orderBy('fecha', 'desc')
            ->first();
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }
}
