<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{

    /** @use HasFactory<\Database\Factories\GuestFactory> */
    use HasFactory;

    protected $fillable = [
        'reserve_id',
        'first_name',
        'last_name',
        'phone'
    ];

    public function reserve()
    {
        return $this->belongsTo(Reserve::class);
    }
}
