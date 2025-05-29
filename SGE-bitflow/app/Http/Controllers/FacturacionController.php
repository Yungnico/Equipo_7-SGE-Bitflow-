<?php

namespace App\Http\Controllers;

use App\Models\Facturacion;
use Illuminate\Http\Request;
use App\Models\DetalleFactura;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

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
        if (!$request->hasFile('archivo')) {
            return back()->with('error', 'No se seleccionó ningún archivo.');
        }

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
                            $currentFactura = Facturacion::create([
                                'folio' => $folio,
                                'tipo_dte' => trim($cols[0]),
                                'fecha_emision' => $fecha_emision,
                                'rut_receptor' => preg_replace('/[^0-9]/', '', $cols[13]),
                                'razon_social_receptor' => $cols[14],
                                'total_neto' => floatval($cols[19]),
                                'iva' => floatval($cols[21]),
                                'total' => floatval($cols[22]),
                                'estado' => 'emitida',
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
                            $currentFactura = Facturacion::create([
                                'folio' => $folio,
                                'tipo_dte' => trim($cols[0]),
                                'fecha_emision' => $fecha_emision,
                                'rut_receptor' => preg_replace('/[^0-9]/', '', $cols[13]),
                                'razon_social_receptor' => $cols[14],
                                'total_neto' => floatval($cols[19]),
                                'iva' => floatval($cols[21]),
                                'total' => floatval($cols[22]),
                                'estado' => 'emitida',
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
