<?php

namespace App\Nova\Metrics;

use App\Models\Signalement;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class ReportedIncidentsType extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $model = ($request->user()->isDecider() || $request->user()->isAgentIncident())
            ? Signalement::where('establishment_id', $request->user()->establishment_id)
            : Signalement::class;

        return $this->count($request, $model, 'is_anonymous')
            ->label(function ($value) {
                switch ($value) {
                    case 1:
                        return 'Anonymous';
                    case 0:
                        return 'Known';
                }
            })->colors([
                1 => '#4055B2',
                0 => '#D7E1F3',
            ]);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'reported-incidents-type';
    }
}
