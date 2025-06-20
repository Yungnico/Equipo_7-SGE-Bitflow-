<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Costos;
use App\Models\CostosDetalle;
use App\Models\CategoriaCostos;
use App\Models\SubCategoriaCostos;
use App\Models\Paridad;
use Illuminate\Http\Request;

class CostoController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'concepto' => 'required|string',
            'categoria_id' => 'required|exists:categorias_costos,id',
            'subcategoria_id' => 'required|exists:subcategorias_costos,id',
            'frecuencia_pago' => 'required|in:único,mensual,trimestral,semestral,anual',
            'moneda_id' => 'required|integer',
            'monto' => 'required|numeric',
            'fecha_inicio' => 'required|date'
        ]);

        $costo = Costos::create($request->only(['concepto', 'categoria_id', 'subcategoria_id', 'frecuencia_pago']));

        $frecuencia = $request->frecuencia_pago;
        $monto_total = $request->monto;
        $moneda_id = $request->moneda_id;
        $fecha_inicio = Carbon::parse($request->fecha_inicio);
        $periodos = match ($frecuencia) {
            'anual', 'único' => 1,
            'semestral' => 2,
            'trimestral' => 4,
            'mensual' => 12,
        };
        $frecuencia_intervalo = match ($frecuencia) {
            'anual', 'único' => '1 year',
            'semestral' => '6 months',
            'trimestral' => '3 months',
            'mensual' => '1 month',
        };

        $monto_por_periodo = $monto_total / $periodos;

        for ($i = 0; $i < $periodos; $i++) {
            CostosDetalle::create([
                'costo_id' => $costo->id,
                'moneda_id' => $moneda_id,
                'monto' => $monto_por_periodo,
                'fecha' => $fecha_inicio->copy()->addMonths($i * match ($frecuencia) {
                    'anual', 'único' => 12,
                    'semestral' => 6,
                    'trimestral' => 3,
                    'mensual' => 1,
                }),
            ]);
        }

        return redirect()->back()->with('success', 'Costo creado correctamente.');
    }



    public function edit(Costos $costo)
    {
        $categorias = CategoriaCostos::all();
        $subcategorias = SubCategoriaCostos::all();
        $monedas = Paridad::all();
        $detalle = $costo->detalles;

        return view('costos.edit', compact('costo', 'categorias', 'subcategorias', 'monedas', 'detalle'));
    }
    public function index()
    {
        $costos = Costos::with('categoria', 'subcategoria', 'detalles.moneda')->get();
        $categorias = CategoriaCostos::all();
        $subcategorias = SubCategoriaCostos::all();
        $monedas = Paridad::all();

        return view('costos.index', compact('costos', 'categorias', 'subcategorias', 'monedas'));
    }
    public function destroy(Costos $costo)
    {
        $costo->detalles()->delete();

        $costo->delete();

        return redirect()->back()->with('success', 'Costo eliminado correctamente.');
    }

    public function update(Request $request, Costos $costo)
    {
        $request->validate([
            'concepto' => 'required|string',
            'categoria_id' => 'required|exists:categorias_costos,id',
            'subcategoria_id' => 'required|exists:subcategorias_costos,id',
            'frecuencia_pago' => 'required|in:único,mensual,trimestral,semestral,anual',
            'año' => 'required|integer',
            'moneda_id' => 'required|integer',
            'monto' => 'required|numeric',
            'fecha_modificacion' => 'required|date'
        ]);

        $costo->update($request->only(['concepto', 'categoria_id', 'subcategoria_id', 'frecuencia_pago']));

        $frecuencia = $request->frecuencia_pago;
        $monto_total = $request->monto;
        $moneda_id = $request->moneda_id;
        $fecha_modificacion = Carbon::parse($request->fecha_modificacion);

        $periodos = match ($frecuencia) {
            'anual', 'único' => 1,
            'semestral' => 2,
            'trimestral' => 4,
            'mensual' => 12,
        };

        $frecuencia_intervalo = match ($frecuencia) {
            'anual', 'único' => '1 year',
            'semestral' => '6 months',
            'trimestral' => '3 months',
            'mensual' => '1 month',
        };

        $monto_por_periodo = $monto_total / $periodos;

        // Eliminar solo los futuros
        $costo->detalles()->where('fecha', '>=', $fecha_modificacion)->delete();

        // Obtener la última fecha previa
        $ultima_fecha = $costo->detalles()
            ->where('fecha', '<', $fecha_modificacion)
            ->orderBy('fecha', 'desc')
            ->first()?->fecha;

        $inicio = $ultima_fecha
            ? Carbon::parse($ultima_fecha)->addMonths(match ($frecuencia) {
                'anual', 'único' => 12,
                'semestral' => 6,
                'trimestral' => 3,
                'mensual' => 1,
            })
            : $fecha_modificacion;


        // Calcular cuántos faltan
        $existentes = $costo->detalles()->count();
        $faltantes = $periodos - $existentes;

        for ($i = 0; $i < $faltantes; $i++) {
            $fecha_detalle = $inicio->copy()->addMonths($i * match ($frecuencia) {
                'anual', 'único' => 12,
                'semestral' => 6,
                'trimestral' => 3,
                'mensual' => 1,
            });


            CostosDetalle::create([
                'costo_id' => $costo->id,
                'moneda_id' => $moneda_id,
                'monto' => $monto_por_periodo,
                'fecha' => $fecha_detalle,
            ]);
        }

        return redirect()->back()->with('success', 'Costo actualizado correctamente.');
    }
}
