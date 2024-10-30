<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Daily extends Model
{
    /** @use HasFactory<\Database\Factories\DailyFactory> */
    use HasFactory;

    protected $primaryKey = 'daily_id';

    protected $fillable = [
        'reserve_id', 'date', 'value',
    ];

    public function reserve()
    {
        return $this->belongsTo(Reserve::class, 'reserve_id', 'reserve_id');
    }
}
