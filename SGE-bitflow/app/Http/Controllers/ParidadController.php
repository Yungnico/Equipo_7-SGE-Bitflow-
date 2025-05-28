<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paridad;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ParidadController extends Controller
{
    private const MONEDAS_VALIDAS = ['USD', 'UF'];

    public function index()
    {
        $paridades = Paridad::whereIn('moneda', self::MONEDAS_VALIDAS)
            ->orderBy('fecha', 'desc')
            ->get()
            ->groupBy(function ($item) {
                return $item->moneda . '-' . \Carbon\Carbon::parse($item->fecha)->format('Y-m'); // Agrupar por mes
            });

        return view('paridades.index', compact('paridades'));
    }


    public function fetchFromAPI()
    {
        $response = Http::get('https://mindicador.cl/api');
        if ($response->successful()) {
            $data = $response->json();
            $fechaHoy = now()->toDateString();

            foreach (['dolar' => 'USD', 'uf' => 'UF'] as $apiKey => $moneda){
                $valor = $data[$apiKey]['valor'];

                $paridad = Paridad::where('moneda', $moneda)
                    ->whereDate('fecha', $fechaHoy)
                    ->first();

                if ($paridad) {
                    if ($valor < $paridad->valor) {
                        Session::flash('warning', "⚠️ La nueva paridad de {$moneda} es menor que la anterior.");
                    }
                    $paridad->update(['valor' => $valor]);
                } else {
                    Paridad::create([
                        'moneda' => $moneda,
                        'valor' => $valor,
                        'fecha' => $fechaHoy
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Paridades actualizadas correctamente.');
        }

        return redirect()->back()->with('error', 'No se pudo conectar con la API.');
    }

    public function edit(Paridad $paridad)
    {
        return view('paridades.edit', compact('paridad'));
    }

    // Actualizar valor de paridad manualmente
    public function update(Request $request, Paridad $paridad)
    {
        $request->validate([
            'valor' => 'required|numeric|min:0',
        ]);

        $nuevoValor = $request->input('valor');

        if ($nuevoValor < $paridad->valor) {
            Session::flash('warning', "El nuevo valor es menor que el valor anterior.");
        }

        $paridad->valor = $nuevoValor;
        $paridad->save();

        Session::flash('success', 'Paridad actualizada correctamente.');

        return redirect()->route('paridades.index');
    }

    public function checkRecordatorioAnual()
    {
        $anio = now()->year;
        $faltan = !Paridad::whereYear('fecha', $anio)
            ->whereIn('moneda', self::MONEDAS_VALIDAS)
            ->exists();

        if ($faltan) {
            Session::flash('warning', '🕒 Aún no se han ingresado paridades USD o UF para este año. Recuerda ajustarlas manualmente.');
        }

        return redirect()->route('paridades.index');
    }
}
