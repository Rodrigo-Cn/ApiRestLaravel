<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DailyRequest extends FormRequest
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
            'date' => 'required|date',
            'value' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'room_id.required' => 'O campo room_id é obrigatório.',
            'room_id.exists' => 'O quarto especificado não foi encontrado.',
            'date.required' => 'O campo data é obrigatório.',
            'date.date' => 'O campo data deve ser uma data válida.',
            'value.required' => 'O campo valor é obrigatório.',
            'value.numeric' => 'O campo valor deve ser numérico.',
            'value.min' => 'O campo valor deve ser maior ou igual a 0.',
        ];
    }
}
