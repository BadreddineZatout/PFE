<?php

namespace App\Rules;

use App\Models\Establishment;
use App\Models\Role;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;

class UserEstablishment implements Rule, DataAwareRule
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
        if ($this->data['role'] == Role::ADMIN || $this->data['role'] == Role::MINISTER)
            return false;
        if ($this->data['role'] == Role::AGENT_HEBERGEMENT && !Establishment::findOrFail($this->data['establishment'])->isResidence())
            return false;
        if ($this->data['role'] == Role::STUDENT && Establishment::findOrFail($this->data['establishment'])->isResidence())
            return false;

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if ($this->data['role'] == Role::ADMIN || $this->data['role'] == Role::MINISTER)
            return "An Admin or a minister can't have an establihsment";
        return "This Establishment can't be assigned to this type of user.";
    }
}
