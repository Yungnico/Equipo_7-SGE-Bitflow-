<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaCostos extends Model
{
    protected $table = 'categorias_costos';
    protected $fillable = ['nombre'];

    public function subcategorias()
    {
        return $this->hasMany(SubCategoriaCostos::class);
    }
}
