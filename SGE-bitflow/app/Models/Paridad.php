<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paridad extends Model
{
    
    protected $fillable = ['moneda', 'valor', 'fecha'];

    protected $dates = ['fecha'];

    public static function obtenerActual($moneda)
    {
        return self::where('moneda', $moneda)
            ->orderBy('fecha', 'desc')
            ->first();
    }
<<<<<<< HEAD
<<<<<<< HEAD
=======

    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }
>>>>>>> parent of d8c5c8b4 (Merge branch 'Vlillo' of https://github.com/Yungnico/Equipo_7-SGE-Bitflow- into Vlillo)
}
=======
}
>>>>>>> parent of e7e936ae (Merge branch 'Dev' into Vlillo)
