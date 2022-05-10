<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Actions\Actionable;

class TransportReservation extends Model
{
    use HasFactory, Actionable;

    protected $casts = [
        'date' => 'date'
    ];

    public function rotation()
    {
        return $this->belongsTo(Rotation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
