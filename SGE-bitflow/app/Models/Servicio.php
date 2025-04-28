<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;
    protected $fillable = ['nombre_servicio', 'descripcion', 'precio', 'moneda', 'categoria_id',];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
