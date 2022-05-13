<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Actions\Actionable;

class Resident extends Model
{
    use HasFactory, Actionable;

    public function getFullnameAttribute()
    {
        return $this->user->name;
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
