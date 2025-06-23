<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'razon_social' => [
                'required',
                'string',
                'max:100',
            ],
            'rut' => [
                'required',
                'string',
            ],
            'nombre_fantasia' => 'required|string|max:100',
            'giro' => 'required|string|max:100',
            'direccion' => 'nullable|string|max:150',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'plazo_pago_habil_dias' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
