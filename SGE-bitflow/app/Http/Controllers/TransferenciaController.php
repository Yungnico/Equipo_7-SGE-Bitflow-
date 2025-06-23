<?php

namespace App\Http\Controllers;

use App\Models\TransferenciaBancaria;
use App\Models\Cliente;
use App\Models\Cotizacion;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Models\CostosDetalle;

class TransferenciaController extends Controller
{
    public function index()
    {
        $transferencias = TransferenciaBancaria::all();
        $costosDisponibles = CostosDetalle::with(['costo.categoria', 'costo.subcategoria', 'moneda'])
            ->whereNull('transferencias_bancarias_id')
            ->get();


        $cotizacionesDisponibles = Cotizacion::whereNull('id_transferencia')
            ->whereIn('estado', ['Borrador', 'Aceptada'])
            ->get();

        return view('transferencias.index', compact('transferencias', 'cotizacionesDisponibles', 'costosDisponibles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'rut' => 'nullable|string|max:20',
            'fecha_transaccion' => 'required|date',
            'hora_transaccion' => 'nullable|string|max:10',
            'fecha_contable' => 'nullable|date',
            'numero_cuenta' => 'nullable|string|max:50',
            'tipo_cuenta' => 'nullable|string|max:50',
            'banco' => 'nullable|string|max:100',
            'codigo_transferencia' => 'nullable|string|max:100',
            'tipo_transaccion' => 'nullable|string|max:100',
            'glosa_detalle' => 'nullable|string',
            'ingreso' => 'nullable|string',
            'egreso' => 'nullable|string',
            'saldo_contable' => 'nullable|numeric',
            'comentario_transferencia' => 'nullable|string',
        ]);

        $datos = $request->all();

        $ingreso = !empty($datos['ingreso']) ? floatval(str_replace(['$', '.', ' '], '', $datos['ingreso'])) : 0;
        $egreso = !empty($datos['egreso']) ? floatval(str_replace(['$', '.', ' '], '', $datos['egreso'])) : 0;

        if ($ingreso > 0) {
            $datos['tipo_movimiento'] = 'ingreso';
        } elseif ($egreso > 0) {
            $datos['tipo_movimiento'] = 'egreso';
        }

        TransferenciaBancaria::create($datos);

        return redirect()->back()->with('success', 'Transferencia agregada correctamente.');
    }


    public function importarExcel(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xls,xlsx,csv',
        ]);

        $archivo = $request->file('archivo');
        $spreadsheet = IOFactory::load($archivo);
        $hoja = $spreadsheet->getActiveSheet();

        // Lista en memoria para evitar duplicados en el mismo archivo
        $duplicadosEnArchivo = [];

        foreach ($hoja->getRowIterator() as $i => $row) {
            if ($i === 1) continue; // Saltar encabezado

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $data = [];
            foreach ($cellIterator as $cell) {
                $data[] = $cell->getValue();
            }

            $isEmptyRow = collect($data)->filter(function ($value) {
                return !is_null($value) && $value !== '';
            })->isEmpty();

            if ($isEmptyRow) continue;

            $fechaTransaccion = isset($data[0]) && is_numeric($data[0]) ? Date::excelToDateTimeObject($data[0])->format('Y-m-d') : null;
            $ingreso = isset($data[8]) ? floatval($data[8]) : null;
            $nombre = trim($data[11] ?? '');
            $rut = trim($data[12] ?? '');
            $comentario = trim($data[17] ?? '');

            // Clave única para evitar duplicados dentro del mismo archivo
            $clave = md5($fechaTransaccion . '|' . $ingreso . '|' . $nombre . '|' . $rut . '|' . $comentario);

            if (in_array($clave, $duplicadosEnArchivo)) {
                continue;
            }

            $existe = TransferenciaBancaria::where('fecha_transaccion', $fechaTransaccion)
                ->where('ingreso', $ingreso)
                ->where('nombre', $nombre)
                ->where('rut', $rut)
                ->where('comentario_transferencia', $comentario)
                ->exists();

            if ($existe) {
                continue;
            }

            $egreso = isset($data[9]) ? floatval($data[9]) : 0;
            $tipoMovimiento = null;

            if ($ingreso > 0) {
                $tipoMovimiento = 'ingreso';
            } elseif ($egreso > 0) {
                $tipoMovimiento = 'egreso';
            }

            TransferenciaBancaria::create([
                'fecha_transaccion'        => $fechaTransaccion,
                'hora_transaccion'         => $data[1] ?? null,
                'fecha_contable'           => isset($data[2]) && is_numeric($data[2]) ? Date::excelToDateTimeObject($data[2])->format('Y-m-d') : null,
                'codigo_transferencia'     => $data[4] ?? null,
                'tipo_transaccion'         => $data[5] ?? null,
                'glosa_detalle'            => $data[7] ?? null,
                'ingreso'                  => $ingreso,
                'egreso'                   => isset($data[9]) ? floatval($data[9]) : null,
                'saldo_contable'           => isset($data[10]) ? floatval($data[10]) : null,
                'nombre'                   => $nombre,
                'rut'                      => $rut,
                'numero_cuenta'            => $data[13] ?? null,
                'tipo_cuenta'              => $data[14] ?? null,
                'banco'                    => $data[15] ?? null,
                'comentario_transferencia' => $comentario,
                'tipo_movimiento'          => $tipoMovimiento,
            ]);

            $duplicadosEnArchivo[] = $clave;
        }

        return back()->with('success', 'Archivo importado correctamente.');
    }


    public function conciliarTransferencias()
    {
        $transferencias = TransferenciaBancaria::where('estado', 'Pendiente')->get();

        foreach ($transferencias as $transferencia) {
            $comentario = trim($transferencia->comentario_transferencia);
            $rut = trim(strtolower($transferencia->rut)); // Formato: 12345678-9
            $nombre = trim(strtolower($transferencia->nombre));
            $monto = floatval($transferencia->ingreso);
            $fechaTransferencia = \Carbon\Carbon::parse($transferencia->fecha_transaccion);

            // 1. FILTRO POR FECHAS: Buscar todas las cotizaciones con fechas válidas para esta transferencia
            $cotizacionesVigentes = Cotizacion::where('estado', '!=', 'Pagada')->get()->filter(function ($cot) use ($fechaTransferencia) {
                $fechaCot = \Carbon\Carbon::parse($cot->fecha_cotizacion);
                $plazo = $cot->cliente->plazo_pago_habil_dias;

                if (!is_numeric($plazo)) return false;

                $fechaLimite = $fechaCot->copy()->addWeekdays($plazo);

                return $fechaTransferencia->greaterThanOrEqualTo($fechaCot) &&
                    $fechaTransferencia->lessThanOrEqualTo($fechaLimite);
            });

            // 2A. INTENTAR CONCILIAR POR COMENTARIO (si hay comentario válido)
            if (preg_match('/(\d+)-CDP(\d+)/i', $comentario, $matches)) {
                $idCotizacion = $matches[1]; // Ej: 1007
                $idCliente = $matches[2];    // Ej: 2

                $cotizacion = $cotizacionesVigentes->first(function ($cot) use ($idCotizacion, $idCliente, $monto) {
                    return $cot->id_cotizacion == $idCotizacion &&
                        $cot->id_cliente == $idCliente &&
                        floatval($cot->total_iva) === $monto;
                });

                if ($cotizacion) {
                    $cotizacion->estado = 'Pagada';
                    $cotizacion->id_transferencia = $transferencia->id;
                    $cotizacion->save();

                    $transferencia->estado = 'Conciliada';
                    $transferencia->save();

                    continue; // Conciliada exitosamente por comentario
                } else {
                    // Comentario apunta a una cotización inexistente o pagada → no continuar con RUT/nombre
                    continue;
                }
            }

            // 2B. SI NO HAY COMENTARIO VÁLIDO, BUSCAR POR RUT O NOMBRE
            $cliente = null;

            if (!empty($rut)) {
                $cliente = Cliente::whereRaw('REPLACE(LOWER(rut),".","") = ?', [$rut])->first();
            }

            if (!$cliente && !empty($nombre)) {
                $cliente = Cliente::whereRaw('LOWER(razon_social) = ?', [$nombre])->first();
            }

            if (!$cliente) {
                continue; // No se puede conciliar
            }

            // Buscar cotizaciones vigentes del cliente
            $cotizacionesCliente = $cotizacionesVigentes->filter(function ($cot) use ($cliente) {
                return $cot->id_cliente === $cliente->id;
            });

            foreach ($cotizacionesCliente as $cotizacion) {
                if (floatval($cotizacion->total_iva) === $monto) {
                    $cotizacion->estado = 'Pagada';
                    $cotizacion->id_transferencia = $transferencia->id;
                    $cotizacion->save();

                    $transferencia->estado = 'Conciliada';
                    $transferencia->save();

                    break; // Conciliada por RUT o nombre + monto
                }
            }
        }

        return back()->with('success', 'Transferencias conciliadas correctamente.');
    }

    public function conciliarManual(Request $request)
    {
        // Validación de los campos del formulario
        $request->validate([
            'transferencias_bancarias_id' => 'required|exists:transferencias_bancarias,id',
            'cotizaciones_id_cotizacion' => 'required|exists:cotizaciones,id_cotizacion',
        ]);

        try {
            // Buscar cotización por ID personalizado (id_cotizacion es la clave primaria)
            $cotizacion = Cotizacion::where('id_cotizacion', $request->cotizaciones_id_cotizacion)->firstOrFail();

            // Buscar la transferencia por ID
            $transferencia = TransferenciaBancaria::findOrFail($request->transferencias_bancarias_id);

            // Asignar la transferencia a la cotización
            $cotizacion->id_transferencia = $transferencia->id; // <- este es el campo correcto
            $cotizacion->estado = 'Pagada';
            $cotizacion->save();

            // Marcar la transferencia como conciliada
            $transferencia->estado = 'Conciliada';
            $transferencia->save();

            return redirect()->back()->with('success', 'Cotización conciliada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al conciliar: ' . $e->getMessage());
        }
    }

    public function conciliarEgreso(Request $request)
    {
        $request->validate([
            'costos_detalle_id' => 'required|exists:costos_detalle,id',
            'transferencias_bancarias_id' => 'required|exists:transferencias_bancarias,id',
        ]);

        $detalle = CostosDetalle::findOrFail($request->costos_detalle_id);
        $detalle->transferencias_bancarias_id = $request->transferencias_bancarias_id;
        $detalle->save();

        return redirect()->back()->with('success', 'Egreso conciliado con costo exitosamente.');
    }
}
