<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoriaCostos;
use App\Models\Costos;

class CategoriaCostoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:255',
        ]);

        CategoriaCostos::create($request->all());

        return redirect()->back()->with('success', 'Categoría creada exitosamente.');
    }
    public function update(Request $request, CategoriaCostos $categoria)
    {
        $request->validate([
            'nombre' => 'required|max:255',
        ]);

        $categoria->update($request->all());

        return redirect()->back()->with('success', 'Categoría actualizada exitosamente.');
    }

    public function destroy($id)
    {
        $categoria = CategoriaCostos::findOrFail($id);
        $cantidad_costos = Costos::where('categoria_id', $categoria->id)->count();
        if ($cantidad_costos > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar la categoría porque tiene costos asociados.');
        }

        $categoria->delete();
        return redirect()->back()->with('success', 'Categoría eliminada correctamente.');
    }
}
