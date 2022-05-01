<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Actions\Actionable;

class EquipmentRequest extends Model
{
    use HasFactory, Actionable;

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
