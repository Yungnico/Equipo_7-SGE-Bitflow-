<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\ItemLibre;
use App\Models\Servicio;
use Illuminate\Http\Request;

class CotizacionController extends Controller
{
    public function index()
    {
        $cotizaciones = Cotizacion::with('itemsLibres')->get();
        return view('cotizaciones.index', compact('cotizaciones'));
    }

    public function create()
    {
        #$clientes = Cliente::all();
        $servicios = Servicio::all();

        return view('cotizaciones.create', compact('servicios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_cliente' => 'required|exists:clientes,id',
            'fecha_cotizacion' => 'required|date',
            'moneda' => 'required|string|max:3',
            'descuento' => 'nullable|numeric|min:0',
            'servicios' => 'required|array|min:1',
            'servicios.*.id' => 'required|exists:servicios,id',
            'servicios.*.cantidad' => 'required|integer|min:1',
            'servicios.*.precio' => 'required|numeric|min:0',
        ]);

        // Crear cotización sin total aún
        $cotizacion = Cotizacion::create([
            'id_cliente' => $request->id_cliente,
            'fecha_cotizacion' => $request->fecha_cotizacion,
            'moneda' => $request->moneda,
            'estado' => 'Borrador',
            'descuento' => $request->descuento ?? 0,
            'total' => 0, // temporal
        ]);

        $total = 0;

        foreach ($request->servicios as $servicio) {
            $subtotal = $servicio['precio'] * $servicio['cantidad'];
            $total += $subtotal;

            $cotizacion->servicios()->attach($servicio['id'], [
                'cantidad' => $servicio['cantidad'],
                'precio_unitario' => $servicio['precio'],
            ]);
        }

        // Aplicar descuento
        $totalFinal = $total - ($request->descuento ?? 0);

        $cotizacion->update(['total' => $totalFinal]);

        return redirect()->route('cotizaciones.index')
            ->with('success', 'Cotización creada correctamente.');
    }

    public function show($id)
    {
        $cotizacion = Cotizacion::with('itemsLibres')->findOrFail($id);
        return view('cotizaciones.show', compact('cotizacion'));
    }
}