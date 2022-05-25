<?php

namespace App\Nova\Metrics;

use App\Models\Bus;
use Laravel\Nova\Metrics\Value;
use App\Nova\Filters\BusEstablishment;
use Laravel\Nova\Http\Requests\NovaRequest;
use Nemrutco\NovaGlobalFilter\GlobalFilterable;

class TotalBus extends Value
{
    use GlobalFilterable;

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
        if ($request->user()->isAdmin() || $request->user()->isMinister())
            return $this->count($request, $model);

        $model->where('establishment_id', $request->user()->establishment_id);
        return $this->count($request, $model);
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
            365 => __('This Year'),
        ];
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'total-bus';
    }
}
