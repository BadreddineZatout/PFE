<?php

namespace App\Nova\Metrics;

use App\Models\Resident;
use Laravel\Nova\Metrics\Value;
use App\Nova\Filters\ResidentResidence;
use Laravel\Nova\Http\Requests\NovaRequest;
use Nemrutco\NovaGlobalFilter\GlobalFilterable;

class ResidentsRenouvles extends Value
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
        if ($request->user()->isResidenceDecider() || $request->user()->isAgentHebergement()) {
            return $this->count($request, Resident::where([
                'establishment_id' => $request->user()->establishment_id,
                'state' => 'renouvlé'
            ]));
        }
        // Filter your model with existing filters
        $model = $this->globalFiltered(Resident::class, [
            ResidentResidence::class
        ]);
        return $this->count($request, $model->where('state', 'renouvlé'));
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
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
        return 'residents-renouvles';
    }

    /**
     * Get the displayable name of the metric
     *
     * @return string
     */
    public function name()
    {
        return 'Residents Renouvlés';
    }
}
