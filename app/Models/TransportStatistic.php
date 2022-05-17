<?php

namespace App\Models;

use Laravel\Nova\Actions\Actionable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransportStatistic extends Model
{
    use HasFactory, Actionable;

    protected $casts = [
        'date' => 'date'
    ];

    public function getNameAttribute()
    {
        return $this->user->name . '/' . $this->rotation->line->name . '/' . $this->date;
    }

    public function rotation()
    {
        return $this->belongsTo(Rotation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
