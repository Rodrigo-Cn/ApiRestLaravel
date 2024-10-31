<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReserveGuest extends Model
{
    use HasFactory;

    protected $table = 'reserve_guests';

    protected $fillable = [
        'reserve_id',
        'guest_id'
    ];

    public function reserve()
    {
        return $this->belongsTo(Reserve::class, 'reserve_id');
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'guest_id');
    }
}
