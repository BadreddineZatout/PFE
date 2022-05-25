<?php

namespace App\Nova\Metrics;

use App\Models\FoodReservation;
use App\Models\Leftover;
use App\Nova\Filters\Establishment;
use App\Nova\Filters\MealType;
use App\Nova\Filters\Wilaya;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Http\Requests\NovaRequest;
use Nemrutco\NovaGlobalFilter\GlobalFilterable;

class LeftoverByDay extends Trend
{
    use GlobalFilterable;

    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        // Filter your model with existing filters
        $model = $this->globalFiltered(Leftover::class, [
            Establishment::class,
            MealType::class
        ]);
        if ($request->user()->isDecider()) {
            $model->join('menus', 'leftovers.id', 'menus.id')
                ->join('structures', 'menus.structure_id', 'structures.id')
                ->where('structures.establishment_id', $request->user()->establishment_id);
        }
        return $this->sumByDays($request, $model, 'leftovers')->format('0');
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
            90 => __('90 Days'),
        ];
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'leftover-by-day';
    }

    /**
     * Get the displayable name of the metric
     *
     * @return string
     */
    public function name()
    {
        return 'Leftovers';
    }
}
