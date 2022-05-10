<?php

namespace App\Nova\Filters;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class RotationDriver extends Filter
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
        return $query->where('user_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        $drivers = [];
        User::where('role_id', Role::where('name', 'Driver')->first()->id)->each(function ($e) use (&$drivers) {
            $drivers[$e->name] = $e->id;
        });
        return $drivers;
    }
}
