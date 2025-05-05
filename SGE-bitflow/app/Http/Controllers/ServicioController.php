<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Moneda;



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
            'precio' => 'required',
            'moneda' => 'required|string',
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

        $validated = $request->validate([
            'nombre_servicio' => 'required|string|max:150|unique:servicios,nombre_servicio,' . $servicio->id,
            'descripcion' => 'required|string|max:300',
            'precio' => 'required|numeric',
            'moneda' => 'required|in:UF,USD,CLP',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        $servicio->update($validated);

        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado correctamente');
    }

    public function index(Request $request)
    {
        $categorias = Categoria::all();
        $monedas = Moneda::all();

        $query = Servicio::with('categoria');

        if ($request->filled('nombre_servicio')) {
            $query->where('nombre_servicio', 'like', '%' . $request->nombre_servicio . '%');
        }

        if ($request->filled('moneda')) {
            $query->where('moneda', $request->moneda);
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
}
