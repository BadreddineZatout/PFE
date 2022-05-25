<?php

namespace App\Nova\Metrics;

use App\Nova\Filters\MealType;
use App\Models\FoodReservation;
use Laravel\Nova\Metrics\Value;
use App\Nova\Filters\Establishment;
use Laravel\Nova\Http\Requests\NovaRequest;
use Nemrutco\NovaGlobalFilter\GlobalFilterable;

class ConsumedMeals extends Value
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
        return $this->count($request, $model->where('has_ate', true));
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
        return 'consumed-meals';
    }

    /**
     * Get the displayable name of the metric
     *
     * @return string
     */
    public function name()
    {
        return "Consumed";
    }
}
