<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoriaCostos;

class CategoriaCosto extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:255',
        ]);

        CategoriaCostos::create($request->all());

        return redirect()->route('Costos.index')->with('success', 'Categoría creada exitosamente.');
    }
}
