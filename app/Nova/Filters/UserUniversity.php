<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use App\Models\Establishment;
use Laravel\Nova\Filters\Filter;

class UserUniversity extends Filter
{
    public $name = 'Universities';

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
        return $query->where('users.establishment_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        $universities = [];
        Establishment::where('type', '!=', 'résidence')->get()->each(function ($e) use (&$universities) {
            $universities[$e->name_fr] = $e->id;
        });
        return $universities;
    }
}
