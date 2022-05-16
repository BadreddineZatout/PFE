<?php

namespace App\Rules;

use App\Models\Line;
use App\Models\Rotation;
use Illuminate\Contracts\Validation\Rule;

class RotationNumberInLine implements Rule
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
        if (!$value) return false;
        return Rotation::where('line_id', $value)->count() < Line::findOrFail($value)->rotations_number;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Can't add more rotations to this line.";
    }
}
