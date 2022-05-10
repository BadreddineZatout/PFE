<?php

namespace App\Nova\Filters;

use App\Models\Rotation;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class TransportRotation extends Filter
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
        return $query->where('rotation_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        $rotations = [];
        if ($request->user()->isAdmin() || $request->user()->isMinister()) {
            Rotation::all()->each(function ($e) use (&$rotations) {
                $rotations[$e->name] = $e->id;
            });
            return $rotations;
        }
        Rotation::join('lines', 'line_id', 'lines.id')
            ->join('plans', 'lines.plan_id', 'plans.id')
            ->where('plans.establishment_id', $request->user()->establishment_id)
            ->select('rotations.*')
            ->get()
            ->each(function ($e) use (&$rotations) {
                $rotations[$e->name] = $e->id;
            });
        return $rotations;
    }
}
