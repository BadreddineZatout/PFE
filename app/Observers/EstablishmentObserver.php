<?php

namespace App\Observers;

use App\Models\Establishment;
use Illuminate\Support\Facades\Cache;

class EstablishmentObserver
{
    /**
     * Handle the Establishment "created" event.
     *
     * @param  \App\Models\Establishment  $establishment
     * @return void
     */
    public function created(Establishment $establishment)
    {
        Cache::forget('establishments');
        Cache::forget('residences');
    }

    /**
     * Handle the Establishment "updated" event.
     *
     * @param  \App\Models\Establishment  $establishment
     * @return void
     */
    public function updated(Establishment $establishment)
    {
        Cache::forget('establishments');
        Cache::forget('residences');
    }

    /**
     * Handle the Establishment "deleted" event.
     *
     * @param  \App\Models\Establishment  $establishment
     * @return void
     */
    public function deleted(Establishment $establishment)
    {
        Cache::forget('establishments');
        Cache::forget('residences');
    }
}
