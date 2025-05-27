<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Validation\Rule;
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
    public function edit(Contacto $contacto)
    {
        // Si necesitas, puedes cargar el cliente relacionado
        return view('clientes.contactos.edit', compact('contacto'));
    }

    public function update(Request $request, Contacto $contacto)
    {
        $request->validate([
            'nombre_contacto' => 'required|string|max:255',
            'email_contacto' => [
                'nullable',
                'email',
                Rule::unique('contactos', 'email_contacto')->ignore($contacto->id),
                Rule::notIn(\App\Models\User::pluck('email')->toArray()),
            ],

            'telefono_contacto' => 'required|numeric',
            'tipo_contacto' => 'required|in:Comercial,TI,Contable',
            
        ]);

        $contacto->update($request->all());

        return redirect()
            ->route('clientes.contactos.index', $contacto->cliente_id)
            ->with('success', 'Contacto actualizado correctamente.');
    }


    public function store(Request $request, $clienteId)
    {
        $request->validate([
            'nombre_contacto' => 'required|string|max:50',
            'email_contacto' => [
                'nullable',
                'email',
                Rule::unique('contactos', 'email_contacto'),
            ],
            'telefono_contacto' => 'required|digits_between:1,15|numeric',
            'tipo_contacto' => 'required|in:Comercial,TI,Contable',
        ], [
            'email_contacto.email.required' => 'El correo no tiene un formato válido.',
            'email_contacto.unique.required' => 'Este correo no puede ser ingresado.', 
            'telefono_contacto.numeric.required' => 'El teléfono solo debe contener números.',
            'tipo_contacto.required' => 'Debe seleccionar un tipo de contacto.',
            'tipo_contacto.in' => 'El tipo de contacto seleccionado no es válido.',
            
            
        ]);

        $cliente = Cliente::findOrFail($clienteId);

        $cliente->contactos()->create([
            'nombre_contacto' => $request->nombre_contacto,
            'email_contacto' => $request->email_contacto,
            'telefono_contacto' => $request->telefono_contacto,
            'tipo_contacto' => $request->tipo_contacto,
        ]);

        return redirect()->route('clientes.contactos.index' ,$clienteId)->with('success', 'Contacto creado correctamente');
    }
    
    public function destroy($clienteId, $contactoId)
    {
        $contacto = Contacto::where('cliente_id' , $clienteId)->findOrFail($contactoId);
        $contacto->delete();

        return redirect()->route('clientes.contactos.index', $clienteId)
                        ->with('success', 'Contacto eliminado correctamente.');
    }

    public function getContactos($id)
    {
        $contactos = Contacto::where('cliente_id', $id)->get();
        return response()->json($contactos);
    }
}

