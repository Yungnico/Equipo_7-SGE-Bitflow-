<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubCategoriaCostos;

class SubcategoriaController extends Controller
{
    public function getPorCategoria($categoriaId)
    {
        $subcategorias = SubCategoriaCostos::where('categoria_id', $categoriaId)->get();
        return response()->json($subcategorias);
    }
}
