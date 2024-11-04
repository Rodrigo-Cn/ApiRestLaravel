<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'method' => 'required|integer',
            'value' => 'required|numeric|min:1|max:3',
        ];
    }

    public function messages(): array
    {
        return [
            'method.required' => 'O método de pagamento é obrigatório.',
            'method.integer' => 'O método de pagamento deve ser um número inteiro.',
            'value.required' => 'O valor do pagamento é obrigatório.',
            'value.numeric' => 'O valor do pagamento deve ser numérico.',
            'value.min' => 'O valor do pagamento deve ser maior ou igual a 0.',
        ];
    }
}
