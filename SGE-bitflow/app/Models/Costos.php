<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Costos extends Model
{
    protected $fillable = ['concepto', 'categoria_id', 'subcategoria_id', 'frecuencia_pago'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function subcategoria()
    {
        return $this->belongsTo(SubCategoriaCostos::class);
    }

    public function detalles()
    {
        return $this->hasMany(CostosDetalle::class, 'costo_id');
    }
}
