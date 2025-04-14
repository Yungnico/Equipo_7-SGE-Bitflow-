<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all();
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {   
        return view('clientes.create');
    }

    public function store(StoreClienteRequest $request)
    {
        
        $data = $request->validated();
        // Guardar logo si viene
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }
        #echo "<script>console.log('Debug Objects: " . $data . "' );</script>";
        $cliente = Cliente::create($data);
        return redirect()->route('clientes.index')->with('success', 'Cliente registrado correctamente: ' . $cliente->razon_social . ' (' . $cliente->rut . ')');
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        $data = $request->validated();

        // Reemplazar logo si se sube uno nuevo
        if ($request->hasFile('logo')) {
            if ($cliente->logo && Storage::disk('public')->exists($cliente->logo)) {
                Storage::disk('public')->delete($cliente->logo);
            }

            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $cliente->update($data);

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente: ' . $cliente->razon_social . ' (' . $cliente->rut . ')');
    }

    public function destroy(Cliente $cliente)
    {
        // Eliminar logo del storage
        if ($cliente->logo && Storage::disk('public')->exists($cliente->logo)) {
            Storage::disk('public')->delete($cliente->logo);
        }

        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente: ' . $cliente->razon_social . ' (' . $cliente->rut . ')');
    }
}
