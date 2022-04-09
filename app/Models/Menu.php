<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'date'
    ];

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }
}
