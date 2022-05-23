<?php

namespace App\Policies;

use App\Models\Signalement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SignalementPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Signalement  $signalement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Signalement $signalement)
    {
        return $user->isAdmin() || $user->isMinister() || $user->isDecider();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Signalement  $signalement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        return $user->isAdmin() || $user->isDecider();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Signalement  $signalement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Signalement $signalement)
    {
        return $user->isAdmin() || $user->isDecider();
    }
}
