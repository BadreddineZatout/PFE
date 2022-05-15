<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    use HasFactory;

    public function wilaya()
    {
        return $this->belongsTo(Wilaya::class);
    }
    public function establishments()
    {
        return $this->hasMany(Establishment::class);
    }
}
