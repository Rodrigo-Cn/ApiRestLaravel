<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;

    protected $fillable = [
        'reserve_id',
        'method',
        'value'
    ];

    public function reserve()
    {
        return $this->belongsTo(Reserve::class);
    }
}
