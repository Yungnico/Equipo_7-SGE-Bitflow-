<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
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
                'required', 'string', 'max:100',
                Rule::unique('clientes')->ignore($this->cliente),
            ],
            'rut' => [
                'required', 'string',
                'regex:/^\d{1,2}\.\d{3}\.\d{3}-[0-9kK]{1}$/',
                Rule::unique('clientes')->ignore($this->cliente),
            ],
            'nombre_fantasia' => 'nullable|string|max:100',
            'giro' => 'nullable|string|max:100',
            'direccion' => 'nullable|string|max:150',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

}
