<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{

    /** @use HasFactory<\Database\Factories\GuestFactory> */
    use HasFactory;

    
    protected $primaryKey = 'guest_id';

    protected $fillable = ['first_name', 'last_name', 'phone'];

    // Relacionamento N:M com Reserve
    public function reserves()
    {
        return $this->belongsToMany(Reserve::class, 'reserve_guests', 'guest_id', 'reserve_id');
    }
}
