<?php

namespace App\Nova\Filters;

use App\Models\Establishment as ModelsEstablishment;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class Establishment extends Filter
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
        dd($value);

        if ($query->getModel()::class == 'App\Models\Leftover') {
            return $query->join('menus', 'leftovers.id', 'menus.id')
                ->join('structures', 'menus.structure_id', 'structures.id')
                ->where('structures.establishment_id', $value);
        }
        if ($query->getModel()::class == 'App\Models\Menu') {
            return $query->join('structures', 'menus.structure_id', 'structures.id')
                ->where('structures.establishment_id', $value);
        }
        return $query->join('menus', 'menu_id', 'menus.id')
            ->join('structures', 'menus.structure_id', 'structures.id')
            ->where('structures.establishment_id', $value);
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
        ModelsEstablishment::all()->each(function ($e) use (&$establishments) {
            $establishments[$e->name] = $e->id;
        });
        return $establishments;
    }
}
