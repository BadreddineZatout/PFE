<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    use HasFactory;

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i'
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function stops()
    {
        return $this->belongsToMany(Stop::class, 'lines_stops')->withPivot('order');
    }
}
