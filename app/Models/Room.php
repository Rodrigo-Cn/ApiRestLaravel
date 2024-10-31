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

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'hotel_id');
    }

    public function reserves()
    {
        return $this->hasMany(Reserve::class, 'room_id', 'room_id');
    }
}
