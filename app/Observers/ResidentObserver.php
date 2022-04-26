<?php

namespace App\Observers;

use App\Models\Resident;
use App\Models\User;

class ResidentObserver
{
    /**
     * Handle the Resident "created" event.
     *
     * @param  \App\Models\Resident  $resident
     * @return void
     */
    public function created(Resident $resident)
    {
        User::where('id', $resident->user_id)
            ->update([
                'is_resident' => true
            ]);
    }

    /**
     * Handle the Resident "deleted" event.
     *
     * @param  \App\Models\Resident  $resident
     * @return void
     */
    public function deleted(Resident $resident)
    {
        User::where('id', $resident->user_id)
            ->update([
                'is_resident' => false
            ]);
    }
}
