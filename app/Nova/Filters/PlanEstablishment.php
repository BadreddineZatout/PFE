<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use App\Models\Establishment;
use Laravel\Nova\Filters\Filter;

class PlanEstablishment extends Filter
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
        return $query->where('establishment_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        $establishments = [];
        Establishment::all()->each(function ($e) use (&$establishments) {
            $establishments[$e->name_fr] = $e->id;
        });
        return $establishments;
    }
}
