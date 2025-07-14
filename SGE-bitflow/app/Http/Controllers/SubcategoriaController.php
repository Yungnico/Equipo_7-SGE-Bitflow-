<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubCategoriaCostos;
use App\Models\CategoriaCostos;

class SubcategoriaController extends Controller
{
    // Mostrar listado (usualmente no se necesita porque lo haces desde CategoriaController)
    public function index()
    {
        $subcategorias = SubCategoriaCostos::with('categoria')->get();
        $categorias = CategoriaCostos::all();
        return view('CostosCategorias.gestion-categorias', compact('subcategorias', 'categorias'));
    }

    // Guardar nueva subcategoría
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias_costos,id',
        ]);

        SubCategoriaCostos::create([
            'nombre' => $request->nombre,
            'categoria_id' => $request->categoria_id,
        ]);

        return redirect()->back()->with('success', 'Subcategoría creada exitosamente.');
    }

    // Mostrar formulario de edición (opcional si usas modales)
    public function edit($id)
    {
        $subcategoria = SubCategoriaCostos::findOrFail($id);
        $categorias = CategoriaCostos::all();
        return view('CostosCategorias.editar-subcategoria', compact('subcategoria', 'categorias'));
    }

    // Actualizar subcategoría
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias_costos,id',
        ]);

        $subcategoria = SubCategoriaCostos::findOrFail($id);
        $subcategoria->update([
            'nombre' => $request->nombre,
            'categoria_id' => $request->categoria_id,
        ]);

        return redirect()->back()->with('success', 'Subcategoría actualizada exitosamente.');
    }

    // Eliminar subcategoría
    public function destroy($id)
    {
        $subcategoria = SubCategoriaCostos::findOrFail($id);
        $subcategoria->delete();

        return redirect()->back()->with('success', 'Subcategoría eliminada exitosamente.');
    }

    // Obtener subcategorías por categoría (ya lo tienes)
    public function getPorCategoria($categoriaId)
    {
        $subcategorias = SubCategoriaCostos::where('categoria_id', $categoriaId)->get();
        return response()->json($subcategorias);
    }
}
