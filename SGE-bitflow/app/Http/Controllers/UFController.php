<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UF;

class UFController extends Controller
{

    public function update(Request $request, $id)
    {
        $request->validate([
            'valor' => 'required|numeric|min:0',
        ]);

        $uf = UF::findOrFail($id);
        $uf->valor = $request->valor;
        $uf->save();

        return redirect()->route('servicios.index')->with('success', 'Valor de UF actualizado correctamente.');
    }
}
