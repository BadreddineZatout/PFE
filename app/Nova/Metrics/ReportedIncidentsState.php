<?php

namespace App\Nova\Metrics;

use App\Models\Signalement;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class ReportedIncidentsState extends Partition
{
    public $name = "Treated vs Not Treated";
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Signalement::class, 'is_Treated')
            ->label(function ($value) {
                switch ($value) {
                    case 1:
                        return 'Treated';
                    case 0:
                        return 'Not Treated';
                }
            })->colors([
                1 => '#4055B2',
                0 => '#D7E1F3',
            ]);
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'reported-incidents-state';
    }
}
