<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signalement extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'date'
    ];

    protected $with = [
        'user',
        'establishment',
        'structure',
        'place'
    ];

    public function getNameAttribute()
    {
        return $this->user->name ?? $this->establishment->name;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
