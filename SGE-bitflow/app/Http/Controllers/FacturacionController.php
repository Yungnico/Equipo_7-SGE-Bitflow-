<?php

namespace App\Http\Controllers;

use App\Models\Facturacion;
use Illuminate\Http\Request;
use App\Models\DetalleFactura;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FacturaImport;

class FacturacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $facturas = Facturacion::with(['detalles'])->get();
        return view('Facturacion.index', compact('facturas'));
    }

    public function importar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:csv,xls,xlsx',
        ]);

        if (!$request->hasFile('archivo')) {
            return back()->with('error', 'No se seleccionó ningún archivo.');
        }

        $archivo = $request->file('archivo');
        $extension = $archivo->getClientOriginalExtension();

        try {
            if (in_array($extension, ['xls', 'xlsx'])) {
                // Usamos Laravel Excel
                // Excel::import(new FacturaImport, $archivo);
            } elseif ($extension === 'csv') {
                // Procesamiento manual del CSV
                $path = $archivo->getRealPath();
                $file = fopen($path, 'r');
                if (!$file) {
                    return back()->with('error', 'No se pudo abrir el archivo CSV.');
                }

                $currentFactura = null;
                $linea = 0;

                while (($cols = fgetcsv($file, 0, ';')) !== false) {
                    $linea++;

                    // Saltar encabezados o líneas vacías
                    if (count($cols) < 5 || in_array('TipoDTE', $cols) || trim($cols[0]) === 'DETALLE') {
                        continue;
                    }

                    if (is_numeric(trim($cols[0]))) {
                        $folio = intval($cols[1]);
                        if (Facturacion::where('folio', $folio)->exists()) {
                            Log::warning("Factura con folio $folio ya existe en línea $linea. Se omitirá.");
                            $currentFactura = null;
                            continue;
                        }

                        try {
                            $currentFactura = Facturacion::create([
                                'folio' => $folio,
                                'tipo_dte' => trim($cols[0]),
                                'fecha_emision' => \Carbon\Carbon::createFromFormat('d-m-Y', $cols[2])->format('Y-m-d'),
                                'rut_receptor' => preg_replace('/[^0-9]/', '', $cols[13]),
                                'razon_social_receptor' => $cols[14],
                                'total_neto' => floatval($cols[19]),
                                'iva' => floatval($cols[21]),
                                'total' => floatval($cols[22]),
                                'estado' => 'emitida',
                            ]);
                        } catch (\Exception $e) {
                            Log::error("Error insertando factura en línea $linea: " . $e->getMessage());
                            $currentFactura = null;
                        }
                    }

                    elseif ($currentFactura && empty($cols[0]) && !empty($cols[4])) {
                        try {
                            DetalleFactura::create([
                                'factura_id' => $currentFactura->id,
                                'descripcion' => $cols[4],
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

            return back()->with('success', 'Importación completada correctamente.');

        } catch (\Exception $e) {
            Log::error('Error general al importar archivo: ' . $e->getMessage());
            return back()->with('error', 'Error al importar el archivo.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
