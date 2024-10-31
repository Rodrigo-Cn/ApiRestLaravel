<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Daily extends Model
{
    /** @use HasFactory<\Database\Factories\DailyFactory> */
    use HasFactory;

    protected $fillable = [
        'reserve_id',
        'date',
        'value'
    ];

    public function reserve()
    {
        return $this->belongsTo(Reserve::class);
    }
}
