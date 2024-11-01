<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    /** @use HasFactory<\Database\Factories\RoomFactory> */
    use HasFactory;

    protected $primaryKey = 'room_id';

    protected $fillable = [
        'room_id',
        'hotel_id',
        'name',
    ];

    public function authorize()
    {
        return true;
    }

    public function rules($isUpdate = false)
    {
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
            'name.max' => 'O campo nome não pode ter mais que 100 caracteres.',
        ];
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'hotel_id');
    }

    public function reserves()
    {
        return $this->hasMany(Reserve::class, 'room_id', 'room_id');
    }
}
