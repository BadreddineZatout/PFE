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

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }
}
