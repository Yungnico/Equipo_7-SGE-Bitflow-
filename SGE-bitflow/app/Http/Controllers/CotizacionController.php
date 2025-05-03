<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\ItemLibre;
use App\Models\Servicio;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CotizacionController extends Controller
{
    public function index()
    {
        $cotizaciones = Cotizacion::with('itemsLibres')->get();
        return view('cotizaciones.index', compact('cotizaciones'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $servicios = Servicio::all();
        $ultimoId = Cotizacion::max('id_cotizacion') + 1;

        return view('cotizaciones.create', compact('servicios','clientes', 'ultimoId'));
    }

    public function store(Request $request)
{
    $request->validate([
        'id_cliente' => 'required|exists:clientes,id',
        'estado' => 'required|in:Borrador,Enviada,Aceptada,Facturada,Pagada,Anulada,Rechazada',
        'fecha_cotizacion' => 'required|date',
        'moneda' => 'required|string|max:3',
        'descuento' => 'nullable|numeric|min:0',
        'servicios' => 'array',
        'servicios.*.id' => 'required|exists:servicios,id',
        'servicios.*.cantidad' => 'required|integer|min:1',
        'items_libres' => 'array',
        'items_libres.*.nombre' => 'required|string|max:255',
        'items_libres.*.precio' => 'required|numeric|min:0',
        'items_libres.*.cantidad' => 'required|integer|min:1',
    ]);

    DB::beginTransaction();

    try {
        $cliente = Cliente::findOrFail($request->id_cliente);

        //generar su id personalizado 
        $ultimoId = Cotizacion::max('id_cotizacion') + 1;
        $iniciales = collect(explode(' ', $cliente->razon_social))
                        ->map(fn($palabra) => Str::upper(Str::substr($palabra, 0, 1)))
                        ->implode('');
        $codigoCotizacion = $ultimoId . '-' . $iniciales;
        




        // Crear cotización
        $cotizacion = Cotizacion::create([
            'codigo_cotizacion' => $codigoCotizacion,
            'id_cliente' => $request->id_cliente,
            'total' => 0,
            'moneda' => $request->moneda,
            'estado' => $request->estado,
            'fecha_cotizacion' => $request->fecha_cotizacion,
            'descuento' => $request->descuento ?? 0,
        ]);

        $total = 0;

        // Asociar servicios
        if ($request->has('servicios')) {
            foreach ($request->servicios as $servicio) {
                $servicioModel = Servicio::findOrFail($servicio['id']);
                $subtotal = $servicioModel->precio * $servicio['cantidad'];
                $cotizacion->servicios()->attach($servicioModel->id, [
                    'cantidad' => $servicio['cantidad']
                ]);
                $total += $subtotal;
            }
        }

        // Agregar ítems libres
        if ($request->has('items_libres')) {
            foreach ($request->items_libres as $item) {
                ItemLibre::create([
                    'nombre' => $item['nombre'],
                    'precio' => $item['precio'],
                    'cantidad' => $item['cantidad'],
                    'id_cotizacion' => $cotizacion->id_cotizacion,
                ]);
                $total += $item['precio'] * $item['cantidad'];
            }
        }

        // Aplicar descuento si existe
        if ($cotizacion->descuento) {
            $total -= $cotizacion->descuento;
        }

        $cotizacion->update(['total' => $total]);

        DB::commit();

        return redirect()->route('cotizaciones.index')->with('success', 'Cotización creada exitosamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withErrors(['error' => 'Ocurrió un error: ' . $e->getMessage()]);
    }
}

    public function show($id)
    {
        $cotizacion = Cotizacion::with('itemsLibres')->findOrFail($id);
        return view('cotizaciones.show', compact('cotizacion'));
    }
}