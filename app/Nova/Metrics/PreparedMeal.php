<?php

namespace App\Nova\Metrics;

use Carbon\Carbon;
use App\Models\Menu;
use App\Nova\Filters\Establishment;
use App\Nova\Filters\MealType;
use App\Nova\Filters\Wilaya;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Http\Requests\NovaRequest;
use Nemrutco\NovaGlobalFilter\GlobalFilterable;

class PreparedMeal extends Value
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
        $model = $this->globalFiltered(Menu::class, [
            Establishment::class,
            MealType::class
        ]);
        $todayMenu = $model->where('date', Carbon::now()->format('Y-m-d'))->first();
        $preparedMeals = $todayMenu ? $todayMenu->quantity : 0;
        return $this->result($preparedMeals)->format('0,0');
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [];
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
        return 'prepared-meal';
    }

    /**
     * Get the displayable name of the metric
     *
     * @return string
     */
    public function name()
    {
        return "Plats Préparés Aujourd'hui";
    }
}
