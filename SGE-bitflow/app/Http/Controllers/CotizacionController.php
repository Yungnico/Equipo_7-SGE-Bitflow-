<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\ItemLibre;
use App\Models\Servicio;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\CotizacionMailable;
use Illuminate\Http\Response;
class CotizacionController extends Controller
{
    public function prepararPDF($id){
        return view('cotizaciones.prepararPDF', compact('id'));
    }

    public function generarPDF($id)
    {
        $cotizacion = Cotizacion::with(['cliente', 'servicios', 'itemsLibres'])->findOrFail($id);

        $pdf = Pdf::loadView('cotizaciones.pdf', compact('cotizacion'));
        Storage::disk('public')->put($cotizacion->codigo_cotizacion . '.pdf', $pdf->output());
        return $pdf->stream('cotizacion.pdf');
    }
    
    public function prepararEmail($id)
    {
        $cotizacion = Cotizacion::with(['cliente', 'servicios', 'itemsLibres'])->findOrFail($id);
        return view('cotizaciones.cotizacionMail', compact('cotizacion'));
    }

    public function enviarEmail($id)
    {
        $cotizacion = Cotizacion::with(['cliente', 'servicios', 'itemsLibres'])->findOrFail($id);
        Mail::to($cotizacion->email)->send(new CotizacionMailable($cotizacion));
        Storage::disk('public')->delete($cotizacion->codigo_cotizacion . '.pdf');
        return response()->json(['success' => true, 'message' => 'Email enviado exitosamente.']);
    }



    public function getCotizacion($id)
    {
        $cotizacion = Cotizacion::with(['cliente', 'itemsLibres', 'servicios'])->findOrFail($id);
        return response()->json($cotizacion);
    }
    

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
        try {
            $request->validate([
                'id_cliente' => 'required|exists:clientes,id',
                'estado' => 'required|in:Borrador,Enviada,Aceptada,Facturada,Pagada,Anulada,Rechazada',
                'fecha_cotizacion' => 'required|date',
                'moneda' => 'required|string|max:3',
                'descuento' => 'nullable|numeric|min:0',
                'servicios' => 'array',
                'servicios.*.servicio' => 'required|exists:servicios,id',
                'servicios.*.cantidad' => 'required|integer|min:1',
                'items_libres' => 'array',
                'items_libres.*.nombre' => 'required|string|max:255',
                'items_libres.*.precio' => 'required|numeric|min:0',
                'items_libres.*.cantidad' => 'required|integer|min:1',
                'email' => 'nullable|email',
                'telefono' => 'nullable|string|max:20',
            ]);

            DB::beginTransaction();

            $cliente = Cliente::findOrFail($request->id_cliente);
            $ultimoId = Cotizacion::max('id_cotizacion') + 1;
            $iniciales = collect(explode(' ', $cliente->razon_social))
                            ->map(fn($p) => Str::upper(Str::substr($p, 0, 1)))
                            ->implode('');
            $codigoCotizacion = $ultimoId . '-' . $iniciales;

            $cotizacion = Cotizacion::create([
                'codigo_cotizacion' => $codigoCotizacion,
                'id_cliente' => $request->id_cliente,
                'total' => 0,
                'moneda' => $request->moneda,
                'estado' => $request->estado,
                'fecha_cotizacion' => $request->fecha_cotizacion,
                'descuento' => $request->descuento ?? 0,
                'email' => $request->email,
                'telefono' => $request->telefono,
            ]);

            $total = 0;

            foreach ($request->input('servicios', []) as $servicio) {
                $servicioModel = Servicio::findOrFail($servicio['servicio']);
                $subtotal = $servicioModel->precio * $servicio['cantidad'];
                $cotizacion->servicios()->attach($servicioModel->id, [
                    'cantidad' => $servicio['cantidad'],
                    'precio_unitario' => $servicioModel->precio,
                ]);
                $total += $subtotal;
            }

            foreach ($request->input('items_libres', []) as $item) {
                ItemLibre::create([
                    'nombre' => $item['nombre'],
                    'precio' => $item['precio'],
                    'cantidad' => $item['cantidad'],
                    'id_cotizacion' => $cotizacion->id_cotizacion,
                ]);
                $total += $item['precio'] * $item['cantidad'];
            }

            if ($cotizacion->descuento) {
                $total = $total - ($total * $cotizacion->descuento/100);
            }

            $cotizacion->update(['total' => $total]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cotización creada exitosamente.',
                'cotizacion_id' => $cotizacion->id_cotizacion,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la cotización',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $cotizacion = Cotizacion::with('itemsLibres')->findOrFail($id);
        return view('cotizaciones.show', compact('cotizacion'));
    }
}