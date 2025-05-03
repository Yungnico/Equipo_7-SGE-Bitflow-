<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteExportController extends Controller
{

    public function exportar(Request $request)
    {
        // Verificar que el formato de exportación es .pdf
        if ($request->input('formato_exportacion') !== 'pdf') {
            return redirect()->route('clientes.index')->with('error', 'Formato no válido');
        }

        // Obtener los parámetros de la solicitud (como los filtros)
        $query = Cliente::query();

        // Aplicar filtros si existen
        if ($request->has('nombre_fantasia')) {
            $query->where('nombre_fantasia', 'like', '%' . $request->nombre_fantasia . '%');
        }
        if ($request->has('razon_social')) {
            $query->where('razon_social', 'like', '%' . $request->razon_social . '%');
        }

        // Obtener los clientes filtrados
        $clientes = $query->get();

        // Verificar si hay clientes para exportar
        if ($clientes->isEmpty()) {
            return back()->with('warning', 'No hay datos para exportar');
        }

        // Generar el PDF con los datos de los clientes
        $pdf = Pdf::loadView('clientes.pdf', compact('clientes'));

        // Descargar el PDF
        return $pdf->download('clientes.pdf');
    }

}


