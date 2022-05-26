<?php

namespace App\Nova\Lenses;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Models\Establishment;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Laravel\Nova\Http\Requests\LensRequest;
use Orlyapps\NovaBelongsToDepend\NovaBelongsToDepend;
use App\Nova\Metrics\ResidentsRenouvles as MetricsResidentsRenouvles;

class ResidentsRenouvles extends Lens
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
        return $request->withOrdering($request->withFilters(
            $query->where('state', 'renouvlé')
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
            BelongsTo::make('Student', 'user', 'App\Nova\User'),
            NovaBelongsToDepend::make('residence', 'establishment')
                ->placeholder('Select Residence')
                ->options(Cache::remember('residences', 60 * 60 * 24, function () {
                    return Establishment::where('type', '=', 'résidence')->get();
                }))
                ->dependsOn('user'),
            NovaBelongsToDepend::make('block', 'structure', 'App\Nova\Structure')
                ->placeholder('Select Block')
                ->optionsResolve(function ($residence) {
                    return $residence->blocks()->get();
                })
                ->dependsOn('establishment'),
            NovaBelongsToDepend::make('chambre', 'place', 'App\Nova\Place')
                ->placeholder('Select Chambre')
                ->optionsResolve(function ($structure) {
                    return $structure->chambres()->get();
                })
                ->dependsOn('structure'),
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
            new MetricsResidentsRenouvles()
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
        return 'residents-renouvles';
    }
}
