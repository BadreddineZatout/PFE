<?php

namespace App\Nova\Metrics;

use App\Models\Menu;
use App\Nova\Filters\MealType;
use App\Models\FoodReservation;
use App\Models\Leftover;
use Laravel\Nova\Metrics\Value;
use Illuminate\Support\Facades\DB;
use App\Nova\Filters\Establishment;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\Leftovers as ModelsLeftovers;
use Nemrutco\NovaGlobalFilter\GlobalFilterable;

class Leftovers extends Value
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
        return $this->sum($request, $model, 'leftovers')->format('0');
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
        return 'leftovers';
    }

    /**
     * Get the displayable name of the metric
     *
     * @return string
     */
    public function name()
    {
        return "Plats Restants";
    }
}
