<?php

namespace App\Http\Controllers;

use App\Models\TransferenciaBancaria;
use App\Models\Cliente;
use App\Models\Cotizacion;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class TransferenciaController extends Controller
{
    public function index()
    {
        $transferencias = TransferenciaBancaria::all();

        $cotizacionesDisponibles = Cotizacion::whereNull('id_transferencia')
            ->whereIn('estado', ['Borrador', 'Aceptada'])
            ->get();

        return view('transferencias.index', compact('transferencias', 'cotizacionesDisponibles'));
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
            'ingreso' => 'nullable|numeric',
            'egreso' => 'nullable|numeric',
            'saldo_contable' => 'nullable|numeric',
            'comentario_transferencia' => 'nullable|string',
        ]);

        TransferenciaBancaria::create($request->all());

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

            // Evitar duplicados dentro del mismo archivo
            if (in_array($clave, $duplicadosEnArchivo)) {
                continue;
            }

            // Evitar duplicados en la base de datos
            $existe = TransferenciaBancaria::where('fecha_transaccion', $fechaTransaccion)
                ->where('ingreso', $ingreso)
                ->where('nombre', $nombre)
                ->where('rut', $rut)
                ->where('comentario_transferencia', $comentario)
                ->exists();

            if ($existe) {
                continue;
            }

            // Si pasó ambos chequeos, guardamos y agregamos a la lista en memoria
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
            ]);

            $duplicadosEnArchivo[] = $clave;
        }

        return back()->with('success', 'Archivo importado correctamente.');
    }

    public function conciliarTransferencias()
    {
        $transferencias = TransferenciaBancaria::where('estado', 'Pendiente')->get();

        foreach ($transferencias as $transferencia) {
            $rut = trim(strtolower($transferencia->rut));
            $nombre = trim(strtolower($transferencia->nombre));
            $comentario = trim($transferencia->comentario_transferencia);
            $monto = floatval($transferencia->ingreso);

            $conciliada = false;

            // (1) Buscar por ID en el comentario
            if (preg_match('/\d+/', $comentario, $matches)) {
                $idCotizacion = $matches[0];
                $cotizacion = Cotizacion::where('id_cotizacion', $idCotizacion)
                    ->where('estado', '!=', 'Pagada')
                    ->first();

                if ($cotizacion && floatval($cotizacion->total) === $monto) {
                    $cotizacion->estado = 'Pagada';
                    $cotizacion->id_transferencia = $transferencia->id;
                    $cotizacion->save();

                    $transferencia->estado = 'Conciliada';
                    $transferencia->save();

                    $conciliada = true;
                    continue; // Pasar a la siguiente transferencia
                }
            }

            // (2 y 3) Buscar cliente por RUT o nombre
            $cliente = null;

            if (!$conciliada && !empty($rut)) {
                $cliente = Cliente::whereRaw('LOWER(rut) = ?', [$rut])->first();
            }

            if (!$cliente && !empty($nombre)) {
                $cliente = Cliente::whereRaw('LOWER(nombre_fantasia) = ?', [$nombre])
                    ->orWhereRaw('LOWER(razon_social) = ?', [$nombre])
                    ->first();
            }

            if (!$cliente) {
                continue; // No se encontró cliente por ningún criterio
            }

            // Buscar cotizaciones no pagadas del cliente
            $cotizaciones = Cotizacion::where('id_cliente', $cliente->id)
                ->where('estado', '!=', 'Pagada')
                ->get();

            foreach ($cotizaciones as $cotizacion) {
                if (floatval($cotizacion->total) === $monto) {
                    $cotizacion->estado = 'Pagada';
                    $cotizacion->id_transferencia = $transferencia->id;
                    $cotizacion->save();

                    $transferencia->estado = 'Conciliada';
                    $transferencia->save();

                    break;
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

        // Buscar cotización por su clave primaria personalizada
        $cotizacion = Cotizacion::where('id_cotizacion', $request->cotizaciones_id_cotizacion)->firstOrFail();

        // Asignar la transferencia a la cotización
        $cotizacion->id_transferencia = $request->transferencias_bancarias_id;
        $cotizacion->estado = 'Pagada';
        $cotizacion->save();

        // Buscar la transferencia y marcarla como conciliada
        $transferencia = TransferenciaBancaria::findOrFail($request->transferencias_bancarias_id);
        $transferencia->estado = 'Conciliada';
        $transferencia->save();

        // Redireccionar con mensaje
        return redirect()->back()->with('success', 'Cotización conciliada exitosamente.');
    }
}
