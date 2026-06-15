<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'worker_id', 'title', 'description', 'category', 'price', 'image'
    ];

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
