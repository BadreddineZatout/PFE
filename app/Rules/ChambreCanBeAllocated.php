<?php

namespace App\Rules;

use App\Models\Place;
use App\Models\Resident;
use Illuminate\Contracts\Validation\Rule;

class ChambreCanBeAllocated implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Resident::where('place_id', $value)->count() < Place::find($value)->first()->capacity;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "This Chambre is full so it can't be allocated.";
    }
}
