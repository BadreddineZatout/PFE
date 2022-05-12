<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use App\Models\Establishment;
use Laravel\Nova\Filters\Filter;

class TransportEstablishment extends Filter
{
    public $name = 'Establishment';
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
        return $query->join('rotations', 'rotation_id', 'rotations.id')
            ->join('lines', 'rotations.line_id', 'lines.id')
            ->join('plans', 'lines.plan_id', 'plans.id')
            ->where('plans.establishment_id', $value)
            ->select('transport_reservations.*');
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
            $establishments[$e->name] = $e->id;
        });
        return $establishments;
    }
}
