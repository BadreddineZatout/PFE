<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Actions\Actionable;

class EquipmentRequest extends Model
{
    use HasFactory, Actionable;

    public function getNameAttribute()
    {
        return $this->equipment->name . ' / ' . $this->resident->user->name .
            ' - ' . $this->resident->establishment->name . ' - ' . $this->resident->structure->name .
            ' - ' . $this->resident->place->name;
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
