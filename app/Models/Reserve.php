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
        return $this->belongsToMany(Guest::class, 'reserve_guests');
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
