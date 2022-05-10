<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    use HasFactory;

    public function lines()
    {
        return $this->belongsToMany(Line::class, 'lines_stops')->withPivot('order');
    }
}
