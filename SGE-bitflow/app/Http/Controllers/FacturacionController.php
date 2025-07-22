<?php

namespace App\Http\Controllers;

use App\Models\Facturacion;
use Illuminate\Http\Request;
use App\Models\DetalleFactura;
use App\Models\Cliente;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class FacturacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function rankingBuenosClientesAjax(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $query = DB::table('facturacion')
            ->leftJoin('clientes', 'facturacion.rut_receptor', '=', 'clientes.rut')
            ->select(
                'facturacion.rut_receptor',
                'facturacion.razon_social_receptor',
                DB::raw('COALESCE(clientes.plazo_pago_habil_dias, 30) as plazo_pago'),
                DB::raw('DATE(facturacion.updated_at) as fecha_pago'),
                DB::raw('DATEDIFF(facturacion.fecha_emision, facturacion.created_at) as dias_para_pago')
            )
            ->where('facturacion.estado', 'pagada');

        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('facturacion.updated_at', [$fechaInicio, $fechaFin]);
        }

        $facturas = $query->get();

        $ranking = [];

        foreach ($facturas as $factura) {
            if ($factura->dias_para_pago <= $factura->plazo_pago) {
                $rut = $factura->rut_receptor;
                $fechaPago = $factura->fecha_pago;

                if (!isset($ranking[$rut])) {
                    $ranking[$rut] = [
                        'razon_social' => $factura->razon_social_receptor,
                        'facturas_por_dia' => []
                    ];
                }

                if (!isset($ranking[$rut]['facturas_por_dia'][$fechaPago])) {
                    $ranking[$rut]['facturas_por_dia'][$fechaPago] = 0;
                }

                $ranking[$rut]['facturas_por_dia'][$fechaPago]++;
            }
        }

        $buenosClientes = [];

        foreach ($ranking as $rut => $cliente) {
            $buenosPorDia = array_filter($cliente['facturas_por_dia'], function ($cantidad) {
                return $cantidad > 0;
            });

            if (count($buenosPorDia) > 0) {
                $buenosClientes[] = [
                    'rut' => $rut,
                    'razon_social' => $cliente['razon_social'],
                    'dias_buenos' => count($buenosPorDia),
                    'total_facturas_buenas' => array_sum($buenosPorDia)
                ];
            }
        }

        return response()->json($buenosClientes);
    }

     public function graficoPorCliente(Request $request)
    {
        $inicio = $request->input('inicio');
        $fin = $request->input('fin');
        if (!$inicio || !$fin) {
            return response()->json(['error' => 'Faltan fechas'], 400);
        }
        $data = DB::table('facturacion')
            ->select('razon_social_receptor as cliente', DB::raw('SUM(total) as total_facturado'))
            ->where('estado', 'pagada')
            ->whereBetween('fecha_emision', [$inicio, $fin])
            ->groupBy('razon_social_receptor')
            ->orderByDesc('total_facturado')
            ->get();

        return response()->json($data);
    }

    public function comparativoAnual()
    {
        $datos = DB::table('facturacion')
            ->select(
                DB::raw('MONTH(fecha_emision) as mes'),
                DB::raw('YEAR(fecha_emision) as anio'),
                DB::raw('SUM(total) as total')
            )
            ->where('estado', 'Pagada')
            ->whereIn(DB::raw('YEAR(fecha_emision)'), [now()->year, now()->year - 1])
            ->groupBy(DB::raw('YEAR(fecha_emision)'), DB::raw('MONTH(fecha_emision)'))
            ->orderBy('mes')
            ->get();

        $res = [
            'labels' => ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
            'datasets' => [
                'Año ' . (now()->year - 1) => array_fill(0, 12, 0),
                'Año ' . now()->year => array_fill(0, 12, 0),
            ]
        ];

        foreach ($datos as $d) {
            $res['datasets']['Año ' . $d->anio][$d->mes - 1] = round($d->total, 0);
        }

        return response()->json($res);
    }

    public function facturadoVsIngresos()
    {
        $year = now()->year;

        $query = DB::table('facturacion')
            ->select(
                DB::raw('MONTH(fecha_emision) as mes'),
                DB::raw('SUM(total) as facturado'),
                DB::raw('SUM(CASE WHEN estado = "Pagada" THEN total ELSE 0 END) as ingresos')
            )
            ->whereYear('fecha_emision', $year)
            ->groupBy(DB::raw('MONTH(fecha_emision)'))
            ->orderBy('mes')
            ->get();

        $facturado = array_fill(0, 12, 0);
        $ingresos = array_fill(0, 12, 0);

        foreach ($query as $d) {
            $facturado[$d->mes - 1] = $d->facturado;
            $ingresos[$d->mes - 1] = $d->ingresos;
        }

        return response()->json([
            'labels' => ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
            'facturado' => $facturado,
            'ingresos' => $ingresos
        ]);
    }
     public function kpi(Request $request)
    {
        $inicio = $request->input('inicio');
        $fin = $request->input('fin');

        $query = Facturacion::query();

        if ($inicio && $fin) {
            $query->whereBetween('fecha_emision', [$inicio, $fin]);
        }

        $facturas = $query->get();
        $facturasPagadas = $facturas->where('estado', 'pagada');
        return response()->json([
            'labels' => [
                'Emitidas', 'Pend. Pago', 'Pagadas', 'Anuladas',
                'Total Neto', 'IVA', 'Total Facturado'
            ],
            'data' => [
                $facturas->count(),
                $facturas->where('estado', 'pendiente')->count(),
                $facturas->where('estado', 'pagada')->count(),
                $facturas->where('estado', 'anulada')->count(),
                $facturasPagadas->sum('total_neto'),
                $facturasPagadas->sum('iva'),
                $facturasPagadas->sum('total')
            ]
        ]);
    }

    public function index()
    {
        $clientes = \App\Models\Cliente::all();
        $servicios = \App\Models\Servicio::all();
        $paridades = \App\Models\Paridad::all();
        $facturas = Facturacion::with(['detalles'])->get();
        $maxFolio = Facturacion::max('folio') ?? 0;
        $maxFolio = $maxFolio + 1; // Incrementar el máximo folio para la nueva factura
        return view('Facturacion.index', compact('facturas', 'clientes', 'servicios','paridades', 'maxFolio'));
    }

    public function importar(Request $request)
    {
        if (!$request->hasFile('archivo')) {
            return back()->with('error', 'No se seleccionó ningún archivo.');
        }
        $clientes = null;
        $archivo = $request->file('archivo');
        $extension = strtolower($archivo->getClientOriginalExtension());
        $path = $archivo->getRealPath();

        try {
            $currentFactura = null;
            $linea = 0;

            if ($extension === 'csv') {
                $file = fopen($path, 'r');
                if (!$file) {
                    return back()->with('error', 'No se pudo abrir el archivo CSV.');
                }

                while (($cols = fgetcsv($file, 0, ';')) !== false) {
                    $linea++;

                    if (count($cols) < 5 || in_array('TipoDTE', $cols) || trim($cols[0]) === 'DETALLE') {
                        continue;
                    }

                    if (is_numeric(trim($cols[0]))) {
                        $folio = intval($cols[1]);
                        if (Facturacion::where('folio', $folio)->exists()) {
                            Log::warning("Factura con folio $folio ya existe en línea $linea. Se omitirá.");
                            continue;
                        }

                        // Conversión de fecha robusta
                        $fecha_emision = $this->convertirFecha($cols[2], $linea);
                        if (!$fecha_emision) continue;

                        try {
                            if($cliente= Cliente::where('rut', $cols[13])->first()) {
                                // Si el cliente ya existe, no lo creamos de nuevo
                            } else {
                                $cliente = Cliente::create([
                                    'rut' => $cols[13],
                                    'razon_social' => $cols[14],
                                    'plazo_pago_habil_dias' => 30,
                                ]);
                            }
                            

                            $id_cliente = Cliente::where('RUT', $cols[13])->value('id');

                            
                            $currentFactura = Facturacion::create([
                                'folio' => $folio,
                                'tipo_dte' => trim($cols[0]),
                                'fecha_emision' => $fecha_emision,
                                'rut_receptor' => $cols[13],
                                'razon_social_receptor' => $cols[14],   
                                'total_neto' => floatval($cols[19]),
                                'iva' => floatval($cols[21]),
                                'total' => floatval($cols[22]),
                                'estado' => 'emitida',
                                'id_cliente' => $id_cliente,
                            ]);
                        } catch (\Exception $e) {
                            Log::error("Error insertando factura en línea $linea: " . $e->getMessage());
                        }
                    }

                    elseif (isset($currentFactura) && empty($cols[0]) && !empty($cols[4])) {
                        try {
                            DetalleFactura::create([
                                'factura_id' => $currentFactura->id,
                                'descripcion' => mb_convert_encoding($cols[4], 'UTF-8'),
                                'cantidad' => floatval($cols[5]),
                                'precio_unitario' => floatval($cols[6]),
                                'subtotal' => floatval($cols[10]),
                            ]);
                        } catch (\Exception $e) {
                            Log::error("Error insertando detalle en línea $linea: " . $e->getMessage());
                        }
                    }
                }

                fclose($file);
            }

            // === Si es Excel (xls, xlsx) ===
            else {
                $reader = IOFactory::createReaderForFile($path);
                if ($reader instanceof Csv) {
                    $reader->setDelimiter(';');
                }

                $spreadsheet = $reader->load($path);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();

                foreach ($rows as $cols) {
                    $linea++;

                    if (count($cols) < 5 || in_array('TipoDTE', $cols) || trim($cols[0]) === 'DETALLE') {
                        continue;
                    }

                    if (is_numeric(trim($cols[0]))) {
                        $folio = intval($cols[1]);
                        if (Facturacion::where('folio', $folio)->exists()) {
                            Log::warning("Factura con folio $folio ya existe en línea $linea. Se omitirá.");
                            continue;
                        }

                        $fecha_emision = $this->convertirFecha($cols[2], $linea);
                        if (!$fecha_emision) continue;

                        try {
                            if($cliente= Cliente::where('rut', $cols[13])->first()) {
                                // Si el cliente ya existe, no lo creamos de nuevo
                            } else {
                                $cliente = Cliente::create([
                                    'rut' => $cols[13],
                                    'razon_social' => $cols[14],
                                    'plazo_pago_habil_dias' => 30,
                                ]);
                            }
                            

                            $id_cliente = Cliente::where('RUT', $cols[13])->value('id');
                            
                            $currentFactura = Facturacion::create([
                                'folio' => $folio,
                                'tipo_dte' => trim($cols[0]),
                                'fecha_emision' => $fecha_emision,
                                'rut_receptor' => $cols[13],
                                'razon_social_receptor' => $cols[14],
                                'total_neto' => floatval($cols[19]),
                                'iva' => floatval($cols[21]),
                                'total' => floatval($cols[22]),
                                'estado' => 'emitida',
                                'id_cliente' => $id_cliente,
                            ]);
                        } catch (\Exception $e) {
                            Log::error("Error insertando factura en línea $linea: " . $e->getMessage());
                        }
                    }

                    elseif (isset($currentFactura) && empty($cols[0]) && !empty($cols[4])) {
                        try {
                            DetalleFactura::create([
                                'factura_id' => $currentFactura->id,
                                'descripcion' => mb_convert_encoding($cols[4], 'UTF-8'),
                                'cantidad' => floatval($cols[5]),
                                'precio_unitario' => floatval($cols[6]),
                                'subtotal' => floatval($cols[10]),
                            ]);
                        } catch (\Exception $e) {
                            Log::error("Error insertando detalle en línea $linea: " . $e->getMessage());
                            return back()->with('error', 'Error al importar archivo.');
                        }
                    }
                }
            }

            return back()->with('success', 'Importación completada correctamente.');

        } catch (\Exception $e) {
            Log::error('Error general al importar archivo: ' . $e->getMessage());
            return back()->with('error', 'Error al importar archivo.');
        }
    }
    private function convertirFecha($valor, $linea)
    {
        try {
            if (is_numeric($valor)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($valor)->format('Y-m-d');
            } else {
                try {
                    return \Carbon\Carbon::createFromFormat('d-m-Y', $valor)->format('Y-m-d');
                } catch (\Exception $e1) {
                    return \Carbon\Carbon::parse($valor)->format('Y-m-d');
                }
            }
        } catch (\Exception $e) {
            Log::error("Error al convertir fecha en línea $linea: " . $valor);
            return null;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                'folio' => 'required|integer',
                'tipo_dte' => 'required|string|max:10',
                'fecha_emision' => 'required|date',
                'rut_receptor' => 'required|string|max:20',
                'razon_social_receptor' => 'required|string|max:255',
                'productos' => 'array',
                'itemsL' => 'array',
                'total_neto' => 'required|numeric',
                'iva' => 'required|numeric',
                'total' => 'required|numeric',
            ]);

            $cliente = Cliente::where('rut', $request->rut_receptor)->first();

            $factura = Facturacion::create([
                'folio' => $request->folio,
                'tipo_dte' => $request->tipo_dte,
                'fecha_emision' => Carbon::parse($request->fecha_emision)->format('Y-m-d'),
                'rut_receptor' => $request->rut_receptor,
                'razon_social_receptor' => $request->razon_social_receptor,
                'total_neto' => $request->total_neto,
                'iva' => $request->iva,
                'total' => $request->total,
                'estado' => 'emitida',
                'id_cliente' => $cliente ? $cliente->id : null,
            ]);
            if ($request->has('productos')) {
                foreach ($request->productos as $producto) {
                    DetalleFactura::create([
                        'factura_id' => $factura->id,
                        'descripcion' => $producto['descripcion'],
                        'cantidad' => $producto['cantidad'],
                        'precio_unitario' => $producto['precio'] / $producto['cantidad'],
                        'subtotal' => $producto['precio']  * $producto['cantidad'],
                    ]);
                }
            }
            if ($request->has('itemsL')) {
                foreach ($request->itemsL as $item) {
                    DetalleFactura::create([
                        'factura_id' => $factura->id,
                        'descripcion' => $item['descripcion'],
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $item['precio_itemsL'],
                        'subtotal' => $item['precio_itemsL'] * $item['cantidad'],
                    ]);
                }
            }
            return response()->json([
                'success' => true,
                'message' => 'Factura creada exitosamente.',
                'factura_id' => $factura->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la factura.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|string'
        ]);

        $factura = Facturacion::findOrFail($id);
        $factura->estado = $request->estado;
        $factura->save();

        return redirect()->back()->with('success', 'Estado actualizado correctamente.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
