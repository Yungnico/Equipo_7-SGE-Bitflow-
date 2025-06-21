<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paridad;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ParidadController extends Controller
{
    protected $table = 'paridades';
    private const MONEDAS_VALIDAS = ['CLP', 'USD', 'UF', 'UTM', 'EUR', 'GBP', 'CHF', 'JPY', 'HKD', 'CAD', 'CNY', 'AUD', 'BRL', 'RUB', 'MXN'];


    public function store(Request $request)
    {
        $request->validate([
            'moneda' => 'required|string|max:10',
            'valor' => 'required|numeric|min:0',
            'fecha' => 'nullable|date',
        ]);

        $fecha = $request->input('fecha') ?? now()->toDateString();

        $existe = Paridad::where('moneda', $request->moneda)
            ->where('fecha', $fecha)
            ->exists();

        if ($existe) {
            return redirect()->back()->with('error', 'Ya existe una paridad con esa moneda y fecha.');
        }

        Paridad::create([
            'moneda' => $request->moneda,
            'valor' => $request->valor,
            'fecha' => $fecha
        ]);

        return redirect()->route('paridades.index')->with('success', 'Paridad agregada exitosamente.');
    }


    public function index()
    {
        $sub = \DB::table('paridades')
            ->selectRaw('moneda, MAX(fecha) as fecha')
            ->groupBy('moneda');

        $paridades = Paridad::joinSub($sub, 'ultimas', function ($join) {
            $join->on('paridades.moneda', '=', 'ultimas.moneda')
                ->on('paridades.fecha', '=', 'ultimas.fecha');
        })->get();

        $todasLasMonedas = ['CLP', 'USD', 'UF', 'UTM', 'EUR', 'GBP', 'CHF', 'JPY', 'HKD', 'CAD', 'CNY', 'AUD', 'BRL', 'RUB', 'MXN'];
        $monedasUsadas = $paridades->pluck('moneda')->toArray();
        $monedasDisponibles = array_diff($todasLasMonedas, $monedasUsadas);

        return view('paridades.index', compact('paridades', 'monedasDisponibles'));
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
        $moneda = $paridad->moneda;

        // Comportamiento especial para USD y UF
        if (in_array($moneda, ['USD', 'UF'])) {
            $response = Http::get('https://mindicador.cl/api');

            if ($response->successful()) {
                $data = $response->json();
                $clave = $moneda === 'USD' ? 'dolar' : 'uf';

                if (isset($data[$clave]['valor'])) {
                    $valorAPI = $data[$clave]['valor'];

                    if ($nuevoValor < $valorAPI) {
                        Session::flash('warning', "El nuevo valor es menor que el de la API.");
                    } elseif ($nuevoValor > $valorAPI) {
                        Session::flash('warning', "El valor es mayor al valor de la API.");
                    } elseif ($nuevoValor == $valorAPI && $paridad->valor > $valorAPI) {
                        Session::flash('info', "✅ El valor fue actualizado al valor actual de la API.");
                    }
                }
            } else {
                Session::flash('error', 'No se pudo consultar el valor actual de la API.');
            }
        } else {
            // Comparación normal para otras monedas
            if ($nuevoValor < $paridad->valor) {
                Session::flash('warning', "El nuevo valor es menor que el valor anterior.");
            }
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
    public function destroy(Paridad $paridad)
    {
        $paridad->delete();
        return redirect()->route('paridades.index')->with('success', 'Paridad eliminada correctamente.');
    }

}