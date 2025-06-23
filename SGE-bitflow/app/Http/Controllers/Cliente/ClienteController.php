<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientesExport;
use Barryvdh\DomPDF\Facade\Pdf;


class ClienteController extends Controller
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

        // Generar el PDF con los datos de los clientes y los contactos filtrados
        $pdf = Pdf::loadView('clientes.pdf', compact('clientes'));

        // Descargar el PDF
        return $pdf->download('clientes.pdf');
    }

    

    public function index(Request $request)
    {
        // Inicia la consulta
        $query = Cliente::query();

        // Aplica filtros si se ingresan datos
        if ($request->filled('razon_social')) {
            $query->where('razon_social', 'like', '%' . $request->razon_social . '%');
        }

        if ($request->filled('rut')) {
            $query->where('rut', 'like', '%' . $request->rut . '%');
        }

        if ($request->filled('nombre_fantasia')) {
            $query->where('nombre_fantasia', 'like', '%' . $request->nombre_fantasia . '%');
        }

        // Si no se ingresó ningún filtro, redirecciona con mensaje de advertencia (opcional)
        if (
            !$request->filled('razon_social') &&
            !$request->filled('rut') &&
            !$request->filled('nombre_fantasia')
        ) {
            // Puedes quitar esta parte si quieres que siempre muestre todo por defecto
            // return redirect()->route('clientes.index')->with('warning', 'Ingrese al menos un criterio de búsqueda.');
        }

        // Pagina el resultado (muy importante)
        $clientes = $query->paginate(10);

        // Retorna la vista con los resultados
        return view('clientes.index', compact('clientes'));
    }



    public function create()
    {   
        return view('clientes.create');
    }


    public function buscar(Request $request)
    {
        $request->validate([
            'razon_social' => 'nullable|string|max:100',
            'rut' => ['nullable', ],
            'nombre_fantasia' => 'nullable|string|max:100',
        ]);

        if (!$request->razon_social && !$request->rut && !$request->nombre_fantasia) {
            return redirect()->back()->with('error', 'Debe ingresar al menos un criterio de búsqueda.');
        }

        $clientes = Cliente::query();

        if ($request->razon_social) {
            $clientes->where('razon_social', 'like', '%' . $request->razon_social . '%');
        }

        if ($request->rut) {
            $clientes->where('rut', $request->rut); // Búsqueda exacta de RUT
        }

        if ($request->nombre_fantasia) {
            $clientes->where('nombre_fantasia', 'like', '%' . $request->nombre_fantasia . '%');
        }

        $resultados = $clientes->get();

        if ($resultados->isEmpty()) {
            return view('clientes.resultados', ['mensaje' => 'No se encontraron resultados']);
        }

        return view('clientes.resultados', ['clientes' => $resultados]);
    }


    public function store(StoreClienteRequest $request)
    {
        
        $data = $request->validated();
        // Guardar logo si viene
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }
        // Normalizar el RUT
        $data['rut'] = $this->normalizarRut($data['rut']);

        #echo "<script>console.log('Debug Objects: " . $data . "' );</script>";
        $cliente = Cliente::create($data);
        return redirect()->route('clientes.index')->with('success', 'Cliente registrado correctamente: ' . $cliente->razon_social . ' (' . $cliente->rut . ')');
    }
    private function normalizarRut($rut)
    {
        // 1. Eliminar todo excepto números y la letra K o k
        $rut = strtoupper(preg_replace('/[^0-9Kk]/', '', $rut));

        // 2. Separar cuerpo y dígito verificador
        $dv = substr($rut, -1);
        $cuerpo = substr($rut, 0, -1);

        // 3. Formatear como 12345678-5
        return $cuerpo . '-' . $dv;
    }
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        $cliente = Cliente::findOrFail($request->id);
        $request->merge([
            'rut' => $this->normalizarRut($request->rut) // Normalizar el RUT antes de la validación
        ]);
        $data = $request->validated();  

        // Reemplazar logo si se sube uno nuevo
        if ($request->hasFile('logo')) {
            if ($cliente->logo && Storage::disk('public')->exists($cliente->logo)) {
                Storage::disk('public')->delete($cliente->logo);
            }

            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }
        $cliente->rut = $this->normalizarRut($data['rut']); // Normalizar el RUT antes de actualizar
        $cliente->razon_social = $data['razon_social'];
        $cliente->nombre_fantasia = $data['nombre_fantasia'];
        $cliente->giro = $data['giro'];
        $cliente->direccion = $data['direccion'] ?? null; // Asegurarse
        $cliente->plazo_pago_habil_dias = $data['plazo_pago_habil_dias'] ?? null; // Asegurarse de que sea nullable
        $cliente->logo = $data['logo'] ?? $cliente->logo; // Mantener el logo existente si no se sube uno nuevo
        $cliente->save();

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente: ' . $cliente->razon_social . ' (' . $cliente->rut . ')');
    }

    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.resultados', ['clientes' => collect([$cliente])]);
    }



    public function getCliente($id)
    {
        $cliente = Cliente::findOrFail($id);
        return response()->json($cliente);
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
