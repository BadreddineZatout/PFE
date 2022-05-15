<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vacation;
use Illuminate\Auth\Access\HandlesAuthorization;

class VacationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Vacation  $vacation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Vacation $vacation)
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
     * @param  \App\Models\Vacation  $vacation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Vacation $vacation)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Vacation  $vacation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Vacation $vacation)
    {
        return $user->isAdmin();
    }
}
