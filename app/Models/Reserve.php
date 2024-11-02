<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserve extends Model
{
    /** @use HasFactory<\Database\Factories\ReserveFactory> */
    use HasFactory;

    protected $primaryKey = 'reserve_id';

    protected $fillable = [
        'hotel_id',
        'room_id',
        'check_in',
        'check_out',
        'total'
    ];

    public function rules()
    {
        return [
            'hotel_id' => 'required|exists:hotels,hotel_id',
            'room_id' => 'required|exists:rooms,room_id',
            'guest.first_name' => 'required|string|max:60',
            'guest.last_name' => 'required|string|max:60',
            'guest.phone' => 'required|string|max:11',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'daily' => 'required|array',
            'daily.*.date' => 'required|date',
            'daily.*.value' => 'required|numeric|min:0',
            'payments' => 'nullable|array',
            'payments.*.method' => 'required|integer',
            'payments.*.value' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'hotel_id.required' => 'O campo hotel_id é obrigatório.',
            'hotel_id.exists' => 'O hotel especificado não foi encontrado.',
            'room_id.required' => 'O campo room_id é obrigatório.',
            'room_id.exists' => 'O quarto especificado não foi encontrado.',
            'guest.first_name.required' => 'O campo primeiro nome do hóspede é obrigatório.',
            'guest.last_name.required' => 'O campo sobrenome do hóspede é obrigatório.',
            'guest.phone.required' => 'O campo telefone do hóspede é obrigatório.',
            'check_in.required' => 'O campo check-in é obrigatório.',
            'check_out.required' => 'O campo check-out é obrigatório.',
            'check_out.after' => 'A data de check-out deve ser posterior à de check-in.',
            'daily.required' => 'As diárias são obrigatórias.',
            'daily.*.date.required' => 'A data da diária é obrigatória.',
            'daily.*.value.required' => 'O valor da diária é obrigatório.',
            'payments.*.method.required' => 'O método de pagamento é obrigatório.',
            'payments.*.value.required' => 'O valor do pagamento é obrigatório.',
        ];
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }

    public function dailies()
    {
        return $this->hasMany(Daily::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
