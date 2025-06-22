<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Costos extends Model
{
    protected $fillable = ['concepto', 'categoria_id', 'subcategoria_id', 'frecuencia_pago'];

    public function categoria()
    {
        return $this->belongsTo(CategoriaCostos::class);
    }

    public function subcategoria()
    {
        return $this->belongsTo(SubCategoriaCostos::class, 'subcategoria_id');
    }

    public function detalles()
    {
        return $this->hasOne(CostosDetalle::class, 'costo_id');
    }
}
