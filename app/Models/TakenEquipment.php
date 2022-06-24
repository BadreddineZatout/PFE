<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Actions\Actionable;

class TakenEquipment extends Model
{
    use HasFactory, Actionable;

    protected $fillable = ['resident_id', 'equipment_id', 'quantity', 'take_date', 'return_date'];

    protected $casts = [
        'take_date' => 'date',
        'return_date' => 'date'
    ];

    public function getNameAttribute()
    {
        return $this->equipment->name . ' / ' . $this->resident->user->name .
            ' - ' . $this->resident->establishment->name_fr . ' - ' . $this->resident->structure->name .
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
