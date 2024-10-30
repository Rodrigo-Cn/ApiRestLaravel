<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReserveGuest extends Model
{
    /** @use HasFactory<\Database\Factories\ReserveGuestFactory> */
    use HasFactory;

    public $incrementing = false;
    protected $table = 'reserve_guests';

    protected $fillable = ['reserve_id', 'guest_id'];
}
