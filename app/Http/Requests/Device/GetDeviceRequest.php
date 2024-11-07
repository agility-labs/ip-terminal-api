<?php

namespace App\Http\Requests\Device;

use Illuminate\Foundation\Http\FormRequest;

class GetDeviceRequest extends FormRequest
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
            'search' => 'string',
            'per_page' => 'integer',
        ];
    }

    public function messages(): array
    {
        return [
            'search.string' => 'Busca inválida',
            'per_page.integer' => 'Quantidade de intens por página inválido',
        ];
    }
}
