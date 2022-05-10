<?php

namespace App\Nova\Metrics;

use App\Models\FoodReservation;
use App\Nova\Filters\Establishment;
use App\Nova\Filters\MealType;
use App\Nova\Filters\Wilaya;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use Nemrutco\NovaGlobalFilter\GlobalFilterable;

class ConsumedByDay extends Trend
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
        $model = $this->globalFiltered(FoodReservation::class, [
            Establishment::class,
            MealType::class
        ]);
        if ($request->user()->isDecider()) {
            $model->join('menus', 'menu_id', 'menus.id')
                ->join('structures', 'menus.structure_id', 'structures.id')
                ->where('structures.establishment_id', $request->user()->establishment_id);
        }
        return $this->countByDays($request, $model->where('has_ate', true));
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
        return 'consumed-by-day';
    }

    /**
     * Get the displayable name of the metric
     *
     * @return string
     */
    public function name()
    {
        return 'Consumed Meals';
    }
}
