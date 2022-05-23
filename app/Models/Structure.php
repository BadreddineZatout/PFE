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

    public function places()
    {
        return $this->hasMany(Place::class)->where('structure_id', $this->id);
    }

    public function chambres()
    {
        return $this->hasMany(Place::class)->where('structure_id', $this->id)->where('type', 'chambre');
    }
}
