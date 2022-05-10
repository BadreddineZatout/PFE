<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasFactory;

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function getNameAttribute()
    {
        return $this->establishment->name . ' (' . $this->start_date->format('Y-m-d') . '/' . $this->end_date->format('Y-m-d') . ')';
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }
}
