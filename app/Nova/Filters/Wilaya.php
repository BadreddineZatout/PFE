<?php

namespace App\Nova\Filters;

use App\Models\Wilaya as ModelsWilaya;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class Wilaya extends Filter
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
        return $query->join('menus as m1', 'menu_id', 'm1.id')
            ->join('structures as s1', 'm1.structure_id', 's1.id')
            ->join('establishments', 's1.establishment_id', 'establishments.id')
            ->where('establishments.wilaya_id', $value)
            ->select('food_reservations.*');
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        $wilayas = [];
        ModelsWilaya::all()->each(function ($e) use (&$wilayas) {
            $wilayas[$e->name] = $e->id;
        });
        return $wilayas;
    }
}
