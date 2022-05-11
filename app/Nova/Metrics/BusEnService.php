<?php

namespace App\Nova\Metrics;

use App\Models\Bus;
use Laravel\Nova\Metrics\Value;
use App\Nova\Filters\BusEstablishment;
use Laravel\Nova\Http\Requests\NovaRequest;
use Nemrutco\NovaGlobalFilter\GlobalFilterable;

class BusEnService extends Value
{
    use GlobalFilterable;

    public $name = 'Buses in service';

    public $refreshWhenActionRuns = true;

    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $model = $this->globalFiltered(Bus::class, [
            BusEstablishment::class
        ]);
        if ($request->user()->isDecider() || $request->user()->isAgentTransport())
            $model->where('establishment_id', $request->user()->establishment_id);
        return $this->count($request, $model->where('in_service', true));
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            'ALL' => 'All Time',
            30 => __('30 Days'),
            60 => __('60 Days'),
            365 => __('365 Days'),
            'TODAY' => __('Today'),
            'MTD' => __('Month To Date'),
            'QTD' => __('Quarter To Date'),
            'YTD' => __('Year To Date'),
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
        return 'bus-en-service';
    }
}
