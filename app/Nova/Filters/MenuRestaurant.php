<?php

namespace App\Nova\Filters;

use App\Models\Structure;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class MenuRestaurant extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where('structure_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        $restaurants = [];
        Structure::where('type', 'restaurant')->get()->each(function ($e) use (&$restaurants) {
            $restaurants[$e->name] = $e->id;
        });
        return $restaurants;
    }
}
