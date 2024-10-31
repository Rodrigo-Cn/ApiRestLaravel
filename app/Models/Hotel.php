<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    /** @use HasFactory<\Database\Factories\HotelFactory> */
    use HasFactory;

    protected $primaryKey = 'hotel_id';

    protected $fillable = [
        'hotel_id',
        'name',
    ];


    public function rooms()
    {
        return $this->hasMany(Room::class, 'hotel_id', 'hotel_id');
    }

    public function reserves()
    {
        return $this->hasMany(Reserve::class, 'hotel_id', 'hotel_id');
    }
}
