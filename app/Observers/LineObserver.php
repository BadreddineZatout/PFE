<?php

namespace App\Observers;

use App\Models\Line;
use Illuminate\Support\Facades\Cache;

class LineObserver
{
    /**
     * Handle the Line "created" event.
     *
     * @param  \App\Models\Line  $line
     * @return void
     */
    public function created(Line $line)
    {
        Cache::forget('lines');
    }

    /**
     * Handle the Line "updated" event.
     *
     * @param  \App\Models\Line  $line
     * @return void
     */
    public function updated(Line $line)
    {
        Cache::forget('lines');
    }

    /**
     * Handle the Line "deleted" event.
     *
     * @param  \App\Models\Line  $line
     * @return void
     */
    public function deleted(Line $line)
    {
        Cache::forget('lines');
    }
}
