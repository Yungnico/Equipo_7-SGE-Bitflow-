<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategoriaCostos extends Model
{
    protected $table = 'subcategorias_costos';
    protected $fillable = ['nombre', 'categoria_id'];

    public function categoria()
    {
        return $this->belongsTo(CategoriaCostos::class, 'categoria_id');
    }
}
