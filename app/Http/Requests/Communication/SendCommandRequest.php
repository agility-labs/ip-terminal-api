<?php

namespace App\Http\Requests\Communication;

use Illuminate\Foundation\Http\FormRequest;

class SendCommandRequest extends FormRequest
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
            'device_id' => 'string|exists:devices,device_id',
            'message' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'device_id.exists' => 'O ID do dispositivo não existe',
            'device_id.string' => 'ID dos dispositivo está com status inválidos',
            'message.required' => 'A mensagem é obrigatória',
            'message.string' => 'A mensagem está em formato inválido',
        ];
    }
}
