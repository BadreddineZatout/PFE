<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class MealType extends Filter
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
        if ($query->getModel()::class == 'App\Models\Leftover') {
            return $query->join('menus as m', 'leftovers.id', 'm.id')
                ->where('m.type', $value);
        }
        if ($query->getModel()::class == 'App\Models\Menu') {
            return $query->where('menus.type', $value);
        }
        return $query->join('menus as m3', 'menu_id', 'm3.id')
            ->where('m3.type', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'breakfast' => 'breakfast',
            'lunch' => 'lunch',
            'dinner' => 'dinner'
        ];
    }
}
