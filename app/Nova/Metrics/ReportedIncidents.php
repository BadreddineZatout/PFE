<?php

namespace App\Nova\Metrics;

use App\Models\Signalement;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Http\Requests\NovaRequest;

class ReportedIncidents extends Trend
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        if ($request->user()->isDecider() || $request->user()->isAgentIncident())
            return $this->countByDays($request, Signalement::where('establishment_id', $request->user()->establishment_id));
        return $this->countByDays($request, Signalement::class);
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            7 => __('Week'),
            30 => __('30 Days'),
            60 => __('60 Days'),
            90 => __('90 Days'),
            365 => __('Year'),
        ];
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
        return 'reported-incidents';
    }
}
