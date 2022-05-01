<?php

namespace App\Models;

use Laravel\Nova\Actions\Actionable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Accommodation extends Model
{
    use HasFactory, Actionable;

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
