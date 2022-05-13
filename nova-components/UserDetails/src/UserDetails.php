<?php

namespace Badi\UserDetails;

use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Card;

class UserDetails extends Card
{
    public function __construct()
    {
        $user = Auth::user();
        $this->withMeta([
            'user' => $user,
            'role' => $user->role->name,
            'establishment' => $user->establishment->name_fr
        ]);
    }
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = '1/3';

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'user-details';
    }
}
