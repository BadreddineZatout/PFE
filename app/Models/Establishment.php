<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Establishment extends Model
{
    use HasFactory;

    public function establishments()
    {
        return $this->belongsToMany(Establishment::class, 'connected_establishments', 'establishment_id', 'connected_establishment_id');
    }
}
