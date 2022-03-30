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

    public function wilaya()
    {
        return $this->belongsTo(Wilaya::class);
    }

    public function commune()
    {
        return $this->belongsTo(Commune::class);
    }

    public function blocks()
    {
        return $this->hasMany(Block::class)->where('establishment_id', $this->id);
    }

    public function buses()
    {
        return $this->hasMany(Bus::class);
    }
}
