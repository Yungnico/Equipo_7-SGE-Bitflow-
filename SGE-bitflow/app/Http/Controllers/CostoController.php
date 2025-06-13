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

        $periodos = match ($request->frecuencia_pago) {
            'anual', 'único' => 1,
            'semestral' => 2,
            'trimestral' => 4,
            'mensual' => 12,
        };

        CostosDetalle::create([
            'costo_id' => $costo->id,
            'año' => $request->año,
            'moneda_id' => $request->moneda_id,
            'periodos' => $periodos,
            'monto' => $request->monto,
        ]);

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
            'monto' => 'required|numeric'
        ]);

        $costo->update($request->only(['concepto', 'categoria_id', 'subcategoria_id', 'frecuencia_pago']));

        $costo->detalles()->delete(); // Borramos el anterior detalle

        $periodos = match ($request->frecuencia_pago) {
            'anual', 'único' => 1,
            'semestral' => 2,
            'trimestral' => 4,
            'mensual' => 12,
        };

        CostosDetalle::create([
            'costo_id' => $costo->id,
            'año' => $request->año,
            'moneda_id' => $request->moneda_id,
            'periodos' => $periodos, // O renómbralo a 'periodos'
            'monto' => $request->monto,
        ]);

        return redirect()->back()->with('success', 'Costo actualizado correctamente.');
    }
}
