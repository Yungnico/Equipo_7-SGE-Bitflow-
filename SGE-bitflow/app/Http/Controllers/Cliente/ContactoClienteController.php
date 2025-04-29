<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Contacto;

class ContactoClienteController extends Controller
{
    // Mostrar todos los contactos de un cliente
    public function index($clienteId)
    {
        $cliente = Cliente::with('contactos')->findOrFail($clienteId);
        return view('clientes.contactos.index', compact('cliente'));

    }
    public function create($clienteId)
    {
        $cliente = Cliente::findOrFail($clienteId);
        return view('clientes.contactos.create', compact('cliente'));
    }


    // Guardar un nuevo contacto
    public function store(Request $request, $clienteId)
    {
        $request->validate([
            'nombre_contacto' => 'nullable|string|max:50',
            'email_contacto' => 'nullable|email|unique:contactos,email_contacto',
            'telefono_contacto' => 'nullable|digits_between:1,15|numeric',
            'tipo_contacto' => 'nullable|in:Comercial,TI,Contable',
        ], [
            'email_contacto.email' => 'El correo no tiene un formato válido.',
            'email_contacto.unique' => 'Este correo ya está registrado.',
            'telefono_contacto.numeric' => 'El teléfono solo debe contener números.',
        ]);

        $cliente = Cliente::findOrFail($clienteId);
        $cliente->contactos()->create([
            'nombre_contacto' => $request->nombre_contacto,
            'email_contacto' => $request->email_contacto,
            'telefono_contacto' => $request->telefono_contacto,
            'tipo_contacto' => $request->tipo_contacto,
        ]);

        return redirect()->route('clientes.contactos.index', $clienteId)
            ->with('success', 'Contacto agregado exitosamente.');
    }
}

