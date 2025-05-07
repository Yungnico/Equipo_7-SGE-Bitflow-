<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Moneda extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'valor'];

    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }
}
