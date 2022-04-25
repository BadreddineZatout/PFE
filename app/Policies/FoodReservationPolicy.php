<?php

namespace App\Policies;

use App\Models\FoodReservation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FoodReservationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FoodReservation  $foodReservation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, FoodReservation $foodReservation)
    {
        return $user->isAdmin() || $user->isDecider() || $user->isMinister() || $user->isAgentRestauration();
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
     * @param  \App\Models\FoodReservation  $foodReservation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, FoodReservation $foodReservation)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FoodReservation  $foodReservation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, FoodReservation $foodReservation)
    {
        return $user->isAdmin();
    }
}
