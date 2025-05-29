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
use App\Models\CotizacionDetalle;
class CotizacionController extends Controller
{
    public function prepararPDF($id){
        $cotizacion = Cotizacion::with(['cliente', 'itemsLibres', 'servicios'])->findOrFail($id);
        return view('cotizaciones.prepararPDF', compact('cotizacion'));
    }

    public function generarPDF(Request $request,$id)
    {
        $cotizacion = Cotizacion::with(['cliente', 'servicios', 'itemsLibres'])->findOrFail($id);
        $observaciones = $request->input('observaciones', '');
        $cotizacion->update(['observaciones' => $observaciones]);
        $cotizacion->save();
        $pdf = Pdf::loadView('cotizaciones.pdf', compact('cotizacion','observaciones'));
        Storage::disk('public')->put($cotizacion->codigo_cotizacion . '.pdf', $pdf->output());
        if ($request->input('accion')) {
            return $pdf->download("cotizacion_{$cotizacion->codigo_cotizacion}.pdf");
        }
    
        return $pdf->stream("cotizacion_{$cotizacion->codigo_cotizacion}.pdf");
    }
    
    public function prepararEmail($id)
    {
        $cotizacion = Cotizacion::with(['cliente', 'servicios', 'itemsLibres'])->findOrFail($id);
        
        return view('cotizaciones.cotizacionMail', compact('cotizacion'));
    }

    public function enviarCorreo(Request $request, $id)
    {
        $request->validate([
            'correo_destino' => 'required|email',
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string',
        ]);

        $correo = $request->correo_destino;
        $asunto = $request->asunto;
        $mensaje = $request->mensaje;
        $adjuntarPdf = $request->adjuntarPdf;

        $cotizacion = Cotizacion::with(['cliente', 'servicios', 'itemsLibres'])->findOrFail($id);
        $cotizacion->update(['estado' => 'Enviada']);
        $cotizacion->save();
        if ($adjuntarPdf == 1) {
            $pdf = Pdf::loadView('cotizaciones.pdf', compact('cotizacion'));
            Storage::disk('public')->put($cotizacion->codigo_cotizacion . '.pdf', $pdf->output());
        }

        $correoMailable = new CotizacionMailable($asunto, $mensaje, $cotizacion->codigo_cotizacion, $adjuntarPdf);
        


        Mail::to($correo)->send($correoMailable);
        Storage::disk('public')->delete($cotizacion->codigo_cotizacion . '.pdf'); // Eliminar el PDF después de enviarlo
        $cotizaciones = Cotizacion::with(['cliente', 'servicios', 'itemsLibres'])->get();
        return view('cotizaciones.index',compact('cotizaciones'))->with('success', 'Correo enviado correctamente.');
    }



    public function getCotizacion($id)
    {
        $cotizacion = Cotizacion::with(['cliente', 'itemsLibres', 'servicios'])->findOrFail($id);
        return response()->json($cotizacion);
    }
    
    public function update(Request $request, $id)
    {
        $cotizacion = Cotizacion::findOrFail($id);
        $request->validate([
            'estado' => 'required|string',
            'motivo' => 'nullable|string',
            'archivo_cliente.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx',
            'facturas' => 'nullable|string',
        ]);

        $cotizacion->estado = $request->estado;
        $cotizacion->save();

        // Guardar detalle
        $detalle = new CotizacionDetalle([
            'estado' => $request->estado,
            'motivo' => $request->motivo,
            'factura_asociada' => $request->facturas,
        ]);
        $detalle->id_cotizacion = $cotizacion->id_cotizacion;
        $detalle->save();
        // Guardar archivo si hay
        if ($request->hasFile('archivo_cliente')) {
            $path = $request->file('archivo_cliente')[0]->store('cotizaciones/' . $cotizacion->id_cotizacion, 'public');
            $detalle->archivo = $path;
        }

        $cotizacion->detalles()->save($detalle);
        $cotizacion->estado = $request->estado;
        $cotizacion->save();
        return redirect()->route('cotizaciones.index', $cotizacion->id_cotizacion)
                        ->with('success', 'Cotización actualizada y detalle guardado.');
    }

    public function index()
    {
        $cotizaciones = Cotizacion::all();
        return view('cotizaciones.index', compact('cotizaciones'));
    }
    public function showBorrador(){
        $cotizaciones = Cotizacion::where('estado', 'Borrador')->get();
        return view('cotizaciones.borradores', compact('cotizaciones'));
    }
    public function create()
    {
        $clientes = Cliente::all();
        $servicios = Servicio::all();
        if (cotizacion::max('id_cotizacion') == null) {
            $ultimoId = 1000;
        } else {
            $ultimoId = Cotizacion::max('id_cotizacion') + 1;
        }

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
            if (cotizacion::max('id_cotizacion') == null) {
                $ultimoId = 1000;
            } else {
                $ultimoId = Cotizacion::max('id_cotizacion') + 1;
            }
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
                    'precio_unitario' => $servicio['precio'],
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

    
    public function destroy($id)
    {
        $cotizacion = Cotizacion::findOrFail($id);
        $cotizacion->delete();
        return redirect()->route('cotizaciones.index')->with('success', 'Cotización eliminada correctamente.');
    }   

    public function edit($id)
    {
        $cotizacion = Cotizacion::with(['cliente', 'servicios', 'itemsLibres'])->findOrFail($id);
        $clientes = Cliente::all();
        $servicios = Servicio::all();
        return view('cotizaciones.edit', compact('cotizacion', 'clientes', 'servicios'));
    }
}