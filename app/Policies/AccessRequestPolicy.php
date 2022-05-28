<?php

namespace App\Policies;

use App\Models\AccessRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccessRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        $user->isAdmin() || $user->isDecider() || $user->isAgentRestauration();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccessRequest  $accessRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, AccessRequest $accessRequest)
    {
        $user->isAdmin() || $user->isDecider() || $user->isAgentRestauration();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        $user->isAdmin() || $user->isDecider() || $user->isAgentRestauration();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccessRequest  $accessRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        $user->isAdmin() || $user->isDecider() || $user->isAgentRestauration();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccessRequest  $accessRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, AccessRequest $accessRequest)
    {
        $user->isAdmin() || $user->isDecider() || $user->isAgentRestauration();
    }
}
