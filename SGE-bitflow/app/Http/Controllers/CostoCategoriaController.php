<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoriaCostos;
use App\Models\SubCategoriaCostos;

class CostoCategoriaController extends Controller
{
    public function index()
    {
        $categorias = CategoriaCostos::all();
        $subcategorias = SubCategoriaCostos::with('categoria')->get();
        return view('CostosCategorias.gestion-categorias', compact('categorias', 'subcategorias'));
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:255']);
        CategoriaCostos::create($request->only('nombre'));
        return back()->with('success', 'Categoría creada');
    }

    public function update(Request $request, $id)
    {
        $categoria = CategoriaCostos::findOrFail($id);
        $categoria->update($request->only('nombre'));
        return back()->with('success', 'Categoría actualizada');
    }

    public function destroy($id)
    {
        CategoriaCostos::destroy($id);
        return back()->with('success', 'Categoría eliminada');
    }
}
