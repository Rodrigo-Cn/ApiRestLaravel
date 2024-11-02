<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomRequest extends FormRequest
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
     * @return array<string,
     */
    public function rules()
    {
        $isUpdate = $this->isMethod('patch') || $this->isMethod('put');

        if ($isUpdate) {
            return [
                'hotel_id' => 'sometimes|required|exists:hotels,hotel_id',
                'name' => 'sometimes|required|string|max:70',
            ];
        } else {
            return [
                'hotel_id' => 'required|exists:hotels,hotel_id',
                'name' => 'required|string|max:70',
            ];
        }
    }

    public function messages()
    {
        return [
            'hotel_id.required' => 'O campo hotel_id é obrigatório.',
            'hotel_id.exists' => 'O hotel especificado não foi encontrado.',
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O campo nome deve ser uma string.',
            'name.max' => 'O campo nome não pode ter mais que 70 caracteres.',
        ];
    }
}
