<?php

namespace App\Nova\Metrics;

use Laravel\Nova\Metrics\Value;
use App\Models\TransportStatistic;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Filters\TransportEstablishment;
use Nemrutco\NovaGlobalFilter\GlobalFilterable;

class TransportedStudent extends Value
{
    use GlobalFilterable;
    public $name = 'Transported Students';
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $model = $this->globalFiltered(TransportStatistic::class, [
            TransportEstablishment::class
        ]);
        if ($request->user()->isDecider() || $request->user()->isAgentTransport())
            $model->join('rotations', 'rotation_id', 'rotations.id')
                ->join('lines', 'rotations.line_id', 'lines.id')
                ->join('plans', 'lines.plan_id', 'plans.id')
                ->where('plans.establishment_id', $request->user()->establishment_id)
                ->select('transport_statistics.*');
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
        return 'transported-student';
    }
}
