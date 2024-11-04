<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReserveRequest extends FormRequest
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
            'hotel_id' => 'required|exists:hotels,hotel_id',
            'room_id' => 'required|exists:rooms,room_id',
            'guest' => 'required|array',
            'guest.*.first_name' => 'required|string|max:60',
            'guest.*.last_name' => 'required|string|max:60',
            'guest.*.phone' => 'required|string|min:11|max:14',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'daily' => 'nullable|array',
            'daily.*.date' => 'required|date',
            'daily.*.value' => 'required|numeric|min:0',
            'payments' => 'nullable|array',
            'payments.*.method' => 'required|integer',
            'payments.*.value' => 'required|numeric|min:0',
        ];
    }
 
    public function messages(): array
    {
        return [
            'hotel_id.required' => 'O campo hotel_id é obrigatório.',
            'hotel_id.exists' => 'O hotel especificado não foi encontrado.',
            'room_id.required' => 'O campo room_id é obrigatório.',
            'room_id.exists' => 'O quarto especificado não foi encontrado.',
            'guest.required' => 'Os dados do hóspede são obrigatórios.',
            'guest.array' => 'Os dados do hóspede devem ser um array.',
            'guest.*.first_name.required' => 'O campo primeiro nome do hóspede é obrigatório.',
            'guest.*.last_name.required' => 'O campo sobrenome do hóspede é obrigatório.',
            'guest.*.phone.required' => 'O campo telefone do hóspede é obrigatório.',
            'check_in.required' => 'O campo check-in é obrigatório.',
            'check_out.required' => 'O campo check-out é obrigatório.',
            'check_out.after' => 'A data de check-out deve ser posterior à de check-in.',
            'daily.required' => 'As diárias são obrigatórias.',
            'daily.array' => 'As diárias devem ser um array.',
            'daily.*.date.required' => 'A data da diária é obrigatória.',
            'daily.*.value.required' => 'O valor da diária é obrigatório.',
            'payments.array' => 'Os pagamentos devem ser um array.',
            'payments.*.method.required' => 'O método de pagamento é obrigatório.',
            'payments.*.value.required' => 'O valor do pagamento é obrigatório.',
        ];
    }
}
