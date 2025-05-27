<?php
namespace App\Http\Controllers;
use App\Models\Paridad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class ParidadController extends Controller
{
    public function index()
    {
        $paridades = Paridad::all();

        $alerta = null;
        $fechaActual = now()->toDateString();
        $existeHoy = $paridades->where('fecha', $fechaActual)->count();

        if ($existeHoy === 0) {
            $alerta = "No se ha ingresado la paridad del día de hoy ($fechaActual).";
        }

        return view('paridades.index', compact('paridades', 'alerta'));
    }

    public function create()
    {
        return view('paridades.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'moneda' => 'required|max:10',
            'valor' => 'required|numeric',
            'fecha' => 'required|date'
        ]);

        Paridad::create($request->all());

        return redirect()->route('paridades.index')->with('success', 'Paridad creada correctamente.');
    }

    public function edit($id)
    {
        $paridad = Paridad::findOrFail($id);
        return view('paridades.edit', compact('paridad'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'moneda' => 'required|max:10',
            'valor' => 'required|numeric',
            'fecha' => 'required|date'
        ]);

        $paridad = Paridad::findOrFail($id);
        $paridad->update($request->all());

        return redirect()->route('paridades.index')->with('success', 'Paridad actualizada correctamente.');
    }

    public function destroy($id)
    {
        $paridad = Paridad::findOrFail($id);
        $paridad->delete();

        return redirect()->route('paridades.index')->with('success', 'Paridad eliminada correctamente.');
    }
}
