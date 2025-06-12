<?php

namespace App\Http\Controllers;

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
            'año' => 'required|integer',
            'moneda_id' => 'required|integer',
            'monto' => 'required|numeric'
        ]);

        $costo = Costos::create($request->only(['concepto', 'categoria_id', 'subcategoria_id', 'frecuencia_pago']));

        $frecuencia = $request->frecuencia_pago;
        $meses = match ($frecuencia) {
            'anual' => [1],
            'semestral' => [1, 7],
            'trimestral' => [1, 4, 7, 10],
            'mensual' => range(1, 12),
            'único' => [null],
        };

        foreach ($meses as $mes) {
            CostosDetalle::create([
                'costo_id' => $costo->id,
                'año' => $request->año,
                'moneda_id' => $request->moneda_id,
                'mes' => $mes,
                'monto' => $request->monto,
            ]);
        }

        return redirect()->back()->with('success', 'Costo creado correctamente.');
    }
    public function edit(Costos $costo)
    {
        $categorias = CategoriaCostos::all();
        $subcategorias = SubCategoriaCostos::all();
        $monedas = Paridad::all();
        $detalle = $costo->detalles()->get();

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
}
