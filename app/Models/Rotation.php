<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rotation extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'date'
    ];

    public function getNameAttribute()
    {
        return $this->line->name . ' (' . $this->start_time . '-' . $this->end_time . ')';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function line()
    {
        return $this->belongsTo(Line::class);
    }
}
