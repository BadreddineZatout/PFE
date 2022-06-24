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

    protected $fillable = ['created_at'];

    public function getNameAttribute()
    {
        return $this->main_dish . ' - ' . $this->secondary_dish . ' - ' . $this->dessert;
    }

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }
}
