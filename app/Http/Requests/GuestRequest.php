<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuestRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'room_id' => 'required|exists:rooms,id',
            'first_name' => 'required|string|max:60',
            'last_name' => 'required|string|max:60',
            'phone' => 'required|string|min:11|max:14',
        ];
    }

    public function messages(): array
    {
        return [
            'room_id.required' => 'O campo room_id é obrigatório.',
            'room_id.exists' => 'O quarto especificado não foi encontrado.',
            'first_name.required' => 'O campo primeiro nome é obrigatório.',
            'first_name.string' => 'O campo primeiro nome deve ser uma string.',
            'first_name.max' => 'O campo primeiro nome não pode ter mais que 60 caracteres.',
            'last_name.required' => 'O campo sobrenome é obrigatório.',
            'last_name.string' => 'O campo sobrenome deve ser uma string.',
            'last_name.max' => 'O campo sobrenome não pode ter mais que 60 caracteres.',
            'phone.required' => 'O campo telefone é obrigatório.',
            'phone.string' => 'O campo telefone deve ser uma string.',
            'phone.min' => 'O campo telefone deve ter pelo menos 11 caracteres.',
            'phone.max' => 'O campo telefone não pode ter mais que 14 caracteres.',
        ];
    }
}
