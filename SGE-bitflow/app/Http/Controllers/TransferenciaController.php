<?php

namespace App\Http\Controllers;

use App\Models\TransferenciaBancaria;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class TransferenciaController extends Controller
{
    public function index()
    {
        $transferencias = TransferenciaBancaria::all();
        return view('transferencias.index', compact('transferencias'));
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

        foreach ($hoja->getRowIterator() as $i => $row) {
            if ($i === 1) continue; // Saltar encabezado

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $data = [];
            foreach ($cellIterator as $cell) {
                $data[] = $cell->getValue();
            }

            // Verificar si la fila está vacía
            $isEmptyRow = collect($data)->filter(function ($value) {
                return !is_null($value) && $value !== '';
            })->isEmpty();

            if ($isEmptyRow) {
                continue; // Saltar filas vacías
            }

            TransferenciaBancaria::create([
                'fecha_transaccion'        => isset($data[0]) && is_numeric($data[0]) ? Date::excelToDateTimeObject($data[0])->format('Y-m-d') : null,
                'hora_transaccion'         => $data[1] ?? null,
                'fecha_contable'           => isset($data[2]) && is_numeric($data[2]) ? Date::excelToDateTimeObject($data[2])->format('Y-m-d') : null,
                'codigo_transferencia'     => $data[4] ?? null,
                'tipo_transaccion'         => $data[5] ?? null,
                'glosa_detalle'            => $data[7] ?? null,
                'ingreso'                  => isset($data[8]) ? floatval($data[8]) : null,
                'egreso'                   => isset($data[9]) ? floatval($data[9]) : null,
                'saldo_contable'           => isset($data[10]) ? floatval($data[10]) : null,
                'nombre'                   => $data[11] ?? null,
                'rut'                      => $data[12] ?? null,
                'numero_cuenta'            => $data[13] ?? null,
                'tipo_cuenta'              => $data[14] ?? null,
                'banco'                    => $data[15] ?? null,
                'comentario_transferencia' => $data[17] ?? null,
            ]);
        }

        return back()->with('success', 'Archivo importado correctamente.');
    }
}
