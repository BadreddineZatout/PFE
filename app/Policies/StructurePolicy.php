<?php

namespace App\Policies;

use App\Models\Structure;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StructurePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Structure  $structure
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Structure $structure)
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
     * @param  \App\Models\Structure  $structure
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Structure $structure)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Structure  $structure
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Structure $structure)
    {
        return $user->isAdmin();
    }
}
