<?php

namespace App\Rules;

use Carbon\Carbon;
use App\Models\Line;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;

class RotationEnd implements Rule, DataAwareRule
{
    /**
     * All of the data under validation.
     *
     * @var array
     */
    protected $data = [];

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
     * Set the data under validation.
     *
     * @param  array  $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
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
        $line = Line::findOrFail($this->data['line']);
        $start_time = Carbon::createFromTimeString($line->start_time);
        $end_time = Carbon::createFromTimeString($line->end_time);
        return $value > $start_time->format('H:i') && $value <= $end_time->format('H:i');
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The end time must be between the line start & end time.';
    }
}
