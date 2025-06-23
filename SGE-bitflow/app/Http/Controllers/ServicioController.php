<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Paridad;

class ServicioController extends Controller
{
    public function create()
    {
        return view('servicios.create');
    }

    public function store(Request $request)
    {
        $precio = str_replace(',', '.', $request->input('precio'));

        $validated = $request->validate([
            'nombre_servicio' => 'required|string',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'moneda_id' => 'required|exists:paridades,id',
            'categoria_id' => 'nullable|exists:categorias,id',
        ]);

        $validated['precio'] = (float) $precio;

        Servicio::create($validated);

        return redirect()->route('servicios.index')->with('success', 'Servicio registrado exitosamente');
    }


    public function edit($id)
    {
        $servicio = Servicio::findOrFail($id);
        return view('servicios.edit', compact('servicio'));
    }

    public function update(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);

        $precio = str_replace(',', '.', $request->input('precio'));

        $validated = $request->validate([
            'nombre_servicio' => 'required|string|max:150|unique:servicios,nombre_servicio,' . $servicio->id,
            'descripcion' => 'required|string|max:300',
            'precio' => 'required|numeric',
            'moneda_id' => 'required|exists:paridades,id',
            'categoria_id' => 'nullable|exists:categorias,id',
        ]);

        $validated['precio'] = (float) $precio;

        $servicio->update($validated);

        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado correctamente');
    }


    public function index(Request $request)
    {
        $categorias = Categoria::all();
        $monedas = Paridad::all();

        $query = Servicio::with('categoria');

        if ($request->filled('nombre_servicio')) {
            $query->where('nombre_servicio', 'like', '%' . $request->nombre_servicio . '%');
        }

        if ($request->filled('moneda')) {
            $query->where('moneda_id', $request->moneda_id);
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        $servicios = $query->paginate(10);

        return view('servicios.index', compact('servicios', 'categorias', 'monedas'));
    }


    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->delete();

        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado correctamente');
    }
    public function getServicio($id)
    {
        $servicio = Servicio::with('moneda')->findOrFail($id);
        return response()->json([
            'descripcion' => $servicio->descripcion,
            'precio' => $servicio->precio,
            'moneda' => $servicio->moneda->moneda,
        ]);
    }
}
