<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use App\Nova\Metrics\TotalBus;
use Laravel\Nova\Fields\Boolean;
use App\Nova\Metrics\BusEnService;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Lenses\BusesInService;
use App\Nova\Lenses\BusesOutOfOrder;
use App\Nova\Metrics\BusHorsService;
use App\Nova\Filters\BusEstablishment;
use Laravel\Nova\Http\Requests\NovaRequest;
use Nemrutco\NovaGlobalFilter\NovaGlobalFilter;
use Titasgailius\SearchRelations\SearchesRelations;
use App\Nova\Actions\BusesInService as ActionsBusesInService;
use App\Nova\Actions\BusesOutOfOrder as ActionsBusesOutOfOrder;

class Bus extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Bus::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'matricule';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'matricule',
    ];

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'establishment' => ['name'],
    ];

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Transport';

    /**
     * Determine if this resource is available for navigation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function availableForNavigation(Request $request)
    {
        return $request->user()->isAdmin() || $request->user()->isMinister() || $request->user()->isDecider() || $request->user()->isAgentTransport();
    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        if ($request->user()->isDecider() || $request->user()->isAgentTransport()) {
            return $query->where('establishment_id', $request->user()->establishment_id);
        }
        return $query;
    }

    /**
     * Build a "relatable" query for establishments.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Laravel\Nova\Fields\Field  $field
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableEstablishments(NovaRequest $request, $query)
    {
        if ($request->user()->isAdmin()) return $query;
        return $query->where('id', $request->user()->establishment_id);
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Text::make('matricule'),
            BelongsTo::make('establishment'),
            Boolean::make('in service', 'in_service')->default(true)->hideWhenCreating(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        if ($request->user()->isAdmin() || $request->user()->isMinister()) {
            return [
                (new NovaGlobalFilter([
                    new BusEstablishment
                ]))->resettable(),
                new TotalBus(),
                new BusEnService(),
                new BusHorsService()
            ];
        }
        return [
            new TotalBus(),
            new BusEnService(),
            new BusHorsService()
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        if ($request->user()->isAdmin() || $request->user()->isMinister()) {
            return [
                new BusEstablishment
            ];
        }
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [
            new BusesInService(),
            new BusesOutOfOrder()
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            (new ActionsBusesInService())->showOnTableRow()
                ->confirmText('Are you sure you want to do this action?')
                ->confirmButtonText('YES')
                ->cancelButtonText("NO")
                ->canSee(function ($request) {
                    return $request->user()->can('update', Bus::class);
                }),
            (new ActionsBusesOutOfOrder())->showOnTableRow()
                ->confirmText('Are you sure about this action?')
                ->confirmButtonText('YES')
                ->cancelButtonText("NO")
                ->canSee(function ($request) {
                    return $request->user()->can('update', Bus::class);
                }),
        ];
    }
}
