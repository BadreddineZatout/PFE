<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Structure extends Model
{
    use HasFactory;

    protected $casts = [
        'creation_date' => 'date'
    ];

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }
}
