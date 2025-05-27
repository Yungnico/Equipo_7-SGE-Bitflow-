<?php

namespace App\Http\Controllers;

use App\Models\Paridad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ParidadController extends Controller
{
    public function index()
    {
        $paridades = Paridad::orderBy('fecha', 'desc')->get();

        // Alerta simple si alguna paridad es demasiado distinta al promedio
        $promedio = Paridad::avg('valor');
        $alerta = null;
        foreach ($paridades as $p) {
            if ($p->valor > $promedio * 1.5 || $p->valor < $promedio * 0.5) {
                $alerta = "Valor anómalo detectado en fecha: {$p->fecha} con moneda: {$p->moneda}";
                break;
            }
        }

        return view('paridades.index', compact('paridades', 'alerta'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'moneda' => 'required',
            'valor' => 'required|numeric',
            'fecha' => 'required|date',
        ]);

        Paridad::create($request->only(['moneda', 'valor', 'fecha']));

        return redirect()->route('paridades.index')->with('success', 'Paridad registrada');
    }

    public function edit($id)
    {
        $paridad = Paridad::findOrFail($id);
        return view('paridades.edit', compact('paridad'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'moneda' => 'required',
            'valor' => 'required|numeric',
            'fecha' => 'required|date',
        ]);

        $paridad = Paridad::findOrFail($id);
        $paridad->update($request->only(['moneda', 'valor', 'fecha']));

        return redirect()->route('paridades.index')->with('success', 'Paridad actualizada');
    }

    public function destroy($id)
    {
        $paridad = Paridad::findOrFail($id);
        $paridad->delete();

        return redirect()->route('paridades.index')->with('success', 'Paridad eliminada');
    }

    public function convertir(Request $request)
    {
        $request->validate([
            'monto' => 'required|numeric',
            'moneda' => 'required',
            'fecha' => 'required|date',
        ]);

        $paridad = Paridad::where('moneda', $request->moneda)
                           ->where('fecha', $request->fecha)
                           ->first();

        if (!$paridad) {
            return redirect()->route('paridades.index')->with('resultado', 'No se encontró tasa para esa fecha');
        }

        $resultado = $request->monto * $paridad->valor;

        return redirect()->route('paridades.index')->with('resultado', $resultado);
    }

    public function ajustar(Request $request)
    {
        $request->validate([
            'moneda' => 'required',
            'fecha' => 'required|date',
            'valor' => 'required|numeric',
        ]);

        $paridad = Paridad::where('moneda', $request->moneda)
                           ->where('fecha', $request->fecha)
                           ->first();

        if ($paridad) {
            $paridad->update(['valor' => $request->valor]);
        } else {
            Paridad::create($request->only(['moneda', 'valor', 'fecha']));
        }

        return redirect()->route('paridades.index')->with('success', 'Paridad ajustada');
    }
}