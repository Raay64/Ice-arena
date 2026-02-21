<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skate extends Model
{
    protected $fillable = ['model', 'size', 'quantity'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
