<?php

namespace App\Policies;

use App\Models\Bus;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BusPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bus  $bus
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Bus $bus)
    {
        return $user->isAdmin() || $user->isMinister() || $user->isDecider() || $user->isAgentTransport();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->isAdmin() || $user->isAgentTransport();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bus  $bus
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        return $user->isAdmin() || $user->isAgentTransport();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bus  $bus
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Bus $bus)
    {
        return $user->isAdmin() || $user->isAgentTransport();
    }
}
