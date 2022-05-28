<?php

namespace App\Nova\Lenses;

use App\Nova\Metrics\TreatedIncidentsTotal;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\LensRequest;

class TreatedIncidents extends Lens
{
    /**
     * Get the query builder / paginator for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\LensRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {
        if ($request->user()->isDecider() || $request->user()->isAgentIncident())
            return $request->withOrdering($request->withFilters(
                $query->where([
                    'is_treated' => true,
                    'establishment_id' => $request->user()->establishment_id
                ])
            ));
        return $request->withOrdering($request->withFilters(
            $query->where('is_treated', true)
        ));
    }

    /**
     * Get the fields available to the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make('user'),
            BelongsTo::make('establishment'),
            BelongsTo::make('structure'),
            BelongsTo::make('place'),
            Text::make('description')->hideFromIndex(),
            Date::make('date'),
        ];
    }

    /**
     * Get the cards available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [
            new TreatedIncidentsTotal()
        ];
    }

    /**
     * Get the actions available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return parent::actions($request);
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'treated-incidents';
    }
}
