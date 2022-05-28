<?php

namespace App\Nova\Metrics;

use App\Models\AccessRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Http\Requests\NovaRequest;

class AcceptedAccessRequestsTotal extends Value
{
    public $name = 'Accepted';
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        if ($request->user()->isDecider() || $request->user()->isAgentRestauration()) {
            return $this->count($request, AccessRequest::where([
                'establishment_id' => $request->user()->establishment_id,
                'state' => 'accepté'
            ]));
        }
        return $this->count($request, AccessRequest::where('state', 'accepté'));
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
            365 => __('365 Days'),
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
        return 'accepted-access-requests-total';
    }
}
