<?php
namespace App\Http\Controllers;
use App\Models\Servicio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ServicioController extends Controller
{
    public function create()
    {
        return view('servicios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_servicio' => 'required|string|max:150|unique:servicios',
            'descripcion' => 'required|string|max:300',
            'precio' => 'required|integer',
            'moneda' => 'required|in:UF,USD,CLP',
        ]);

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
            'precio' => 'required|integer',
            'moneda' => 'required|in:UF,USD,CLP',
        ]);

        $servicio->update($validated);

        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado correctamente');
    }

    public function index()
    {
        $servicios = Servicio::all(); // Obtiene todos los servicios
        return view('servicios.index', compact('servicios'));
    }
    public function toggleEstado($id)
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->estado = !$servicio->estado; // cambia el estado
        $servicio->save();
    
        return redirect()->route('servicios.index')->with('success', 'Estado del servicio actualizado');
    }
}

