<?php

namespace App\Http\Controllers;

use App\Models\Paridad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ParidadController extends Controller
{
    public function index()
    {
        $paridades = Paridad::orderBy('fecha', 'desc')->get();
        $promedio = Paridad::avg('valor');
        $alerta = null;

        foreach ($paridades as $p) {
            if ($p->valor > $promedio * 1.5 || $p->valor < $promedio * 0.5) {
                $alerta = "Valor anómalo detectado en fecha: {$p->fecha} con moneda: {$p->moneda}";
                break;
            }
        }

        return view('paridades.index', compact('paridades', 'alerta'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'moneda' => 'required|string',
            'valor' => 'required|numeric',
            'fecha' => 'required|date',
        ]);

        Paridad::updateOrCreate(
            ['moneda' => $request->moneda, 'fecha' => $request->fecha],
            ['valor' => $request->valor]
        );

        return redirect()->back()->with('success', 'Paridad registrada');
    }
}

