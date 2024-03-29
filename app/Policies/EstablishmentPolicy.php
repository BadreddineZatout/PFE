<?php

namespace App\Policies;

use App\Models\Establishment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EstablishmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Establishment  $establishment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Establishment $establishment)
    {
        return true;
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
     * @param  \App\Models\Establishment  $establishment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Establishment $establishment)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Establishment  $establishment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Establishment $establishment)
    {
        return $user->isAdmin();
    }

    public function attachAnyLine(User $user, Establishment $establishment)
    {
        return $user->isAdmin();
    }

    public function attachLine(User $user, Establishment $establishment)
    {
        return $user->isAdmin();
    }

    public function detachLine(User $user, Establishment $establishment)
    {
        return $user->isAdmin();
    }
}
