<?php

namespace App\Http\Controllers;

use App\Models\Paridad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ParidadController extends Controller
{
    public function index()
    {
        $paridades = Paridad::all();
        return view('paridades.index', compact('paridades'));
    }

    public function store(Request $request)
    {
        $monedas = $request->input('monedas');
        $hoy = date('Y-m-d');

        foreach ($monedas as $moneda) {
            $valor = $this->obtenerValorMoneda($moneda);

            Paridad::updateOrCreate(
                ['moneda' => $moneda],
                ['valor' => $valor, 'fecha' => $hoy]
            );
        }

        return redirect()->route('paridades.index')->with('success', 'Paridades actualizadas correctamente.');
    }

    public function actualizarValores()
    {
        $paridades = Paridad::all();
        $hoy = date('Y-m-d');

        foreach ($paridades as $paridad) {
            $valor = $this->obtenerValorMoneda($paridad->moneda);
            $paridad->update([
                'valor' => $valor,
                'fecha' => $hoy
            ]);
        }

        return redirect()->route('paridades.index')->with('success', 'Paridades actualizadas.');
    }

    public function edit($id)
    {
        $paridad = Paridad::findOrFail($id);
        return response()->json($paridad);
    }

    public function update(Request $request, $id)
    {
        $paridad = Paridad::findOrFail($id);

        $valorApi = $this->obtenerValorMoneda($paridad->moneda);
        $nuevoValor = $request->input('valor');
        $paridad->valor = $nuevoValor;
        $paridad->fecha = date('Y-m-d');
        $paridad->save();

        if ($nuevoValor < $valorApi) {
            return redirect()->route('paridades.index')->with('warning', '¡El nuevo valor es menor que el valor actual de la API ('.$valorApi.')!');
        }

        return redirect()->route('paridades.index')->with('success', 'Paridad actualizada correctamente.');
    }

    private function obtenerValorMoneda($moneda)
    {
        $response = Http::get("https://mindicador.cl/api");
        $data = $response->json();

        $moneda = strtolower($moneda);
        return $data[$moneda]['valor'] ?? 0;
    }
}

