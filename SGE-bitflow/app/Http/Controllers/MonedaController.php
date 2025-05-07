<?php

namespace App\Http\Controllers;

use App\Models\Moneda;
use Illuminate\Http\Request;

class MonedaController extends Controller
{
    public function index()
    {
        $monedas = Moneda::all();
        return view('monedas.index', compact('monedas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'valor' => 'required|numeric',
        ]);

        Moneda::create($request->only('nombre', 'valor'));
        return redirect()->back()->with('success', 'Moneda agregada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'valor' => 'required|numeric',
        ]);

        $moneda = Moneda::findOrFail($id);
        $moneda->update($request->only('nombre', 'valor'));

        return redirect()->back()->with('success', 'Moneda actualizada correctamente.');
    }

    public function destroy($id)
    {
        Moneda::destroy($id);
        return redirect()->back()->with('success', 'Moneda eliminada.');
    }
}
