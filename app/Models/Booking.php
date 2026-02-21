<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'fio',
        'phone',
        'hours',
        'skate_id',
        'total_amount',
        'payment_id',
        'is_paid',
        'has_skates'
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'has_skates' => 'boolean'
    ];

    public function skate()
    {
        return $this->belongsTo(Skate::class);
    }
}
