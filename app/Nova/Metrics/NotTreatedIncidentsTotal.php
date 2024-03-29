<?php

namespace App\Nova\Metrics;

use App\Models\Signalement;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class NotTreatedIncidentsTotal extends Value
{

    public $name = 'Not Treated Incidents';
    public $refreshWhenActionRuns = true;
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        if ($request->user()->isDecider() || $request->user()->isAgentIncident())
            return $this->count($request, Signalement::where([
                'is_treated' => false,
                'establishment_id' => $request->user()->establishment_id
            ]));
        return $this->count($request, Signalement::where('is_treated', false));
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            'TODAY' => __('Today'),
            30 => __('30 Days'),
            60 => __('60 Days'),
            365 => __('Year'),
            'MTD' => __('Month To Date'),
            'QTD' => __('Quarter To Date'),
            'YTD' => __('Year To Date'),
        ];
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'not-treated-incidents-total';
    }
}
