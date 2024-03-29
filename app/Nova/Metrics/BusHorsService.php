<?php

namespace App\Nova\Metrics;

use App\Models\Bus;
use Laravel\Nova\Metrics\Value;
use App\Nova\Filters\BusEstablishment;
use Laravel\Nova\Http\Requests\NovaRequest;
use Nemrutco\NovaGlobalFilter\GlobalFilterable;

class BusHorsService extends Value
{
    use GlobalFilterable;

    public $name = 'Buses out of order';

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
        return $this->count($request, $model->where('in_service', false));
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
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'bus-hors-service';
    }
}
