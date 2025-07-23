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
            'frecuencia_pago' => 'required|in:único,mensual,trimestral,semestral,anual',
            'moneda_id' => 'required|integer',
            'monto' => 'required|numeric',
            'fecha_inicio' => 'required|date'
        ]);
        $costo = Costos::create($request->only(['concepto', 'categoria_id', 'frecuencia_pago']));

        $frecuencia = $request->frecuencia_pago;
        $monto = $request->monto;
        $moneda_id = $request->moneda_id;
        $fecha_inicio = Carbon::parse($request->fecha_inicio);
        $periodos = match ($frecuencia) {
            'anual', 'único' => 1,
            'semestral' => 2,
            'trimestral' => 4,
            'mensual' => 12,
        };

        for ($i = 0; $i < $periodos; $i++) {
            CostosDetalle::create([
                'costo_id' => $costo->id,
                'moneda_id' => $moneda_id,
                'monto' => $monto,
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
        $monedas = Paridad::all();
        $detalle = $costo->detalles;

        return view('Costos.edit', compact('costo', 'categorias', 'monedas', 'detalle'));
    }
    public function index()
    {
        $costos = Costos::with([
            'categoria',
            'detalles' => function ($query) {
                $query->orderBy('fecha', 'asc'); // puedes cambiar a 'id' si prefieres
            },
            'detalles.moneda'
        ])->get();

        $categorias = CategoriaCostos::all();
        $monedas = Paridad::all();

        return view('Costos.index', compact('costos', 'categorias', 'monedas'));
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
            'frecuencia_pago' => 'required|in:único,mensual,trimestral,semestral,anual',
            'moneda_id' => 'required|integer',
            'monto' => 'required|numeric',
            'fecha_modificacion' => 'required|date' // Se mantiene aunque no se use
        ]);
        
        // Verificar si algún detalle tiene transferencia bancaria
        $tieneTransferencias = $costo->detalles()
            ->whereNotNull('transferencias_bancarias_id')
            ->exists();

        if ($tieneTransferencias) {
            return redirect()->back()->with('error', 'Este costo no se puede modificar porque tiene transferencias asociadas.');
        }

        // Si no hay transferencias, editar el costo
        $costo->update($request->only([
            'concepto',
            'categoria_id',
            'frecuencia_pago'
        ]));

        $monto = $request->monto;
        $moneda_id = $request->moneda_id;

        // Obtener detalles editables
        $detalles_editables = $costo->detalles()
            ->whereNull('transferencias_bancarias_id')
            ->orderBy('fecha')
            ->get();

        foreach ($detalles_editables as $detalle) {
            $detalle->update([
                'moneda_id' => $moneda_id,
                'monto' => $monto,
            ]);
        }

        return redirect()->back()->with('success', 'Costo actualizado correctamente.');
    }
}
