<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Models\Establishment;
use App\Nova\Filters\AccessRequestState;
use App\Nova\Metrics\AcceptedAccessRequestsTotal;
use App\Nova\Metrics\AccessRequestsTotal;
use App\Nova\Metrics\NotTreatedAccessRequestsTotal;
use App\Nova\Metrics\RefusedAccessRequestsTotal;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Laravel\Nova\Http\Requests\NovaRequest;
use Orlyapps\NovaBelongsToDepend\NovaBelongsToDepend;

class AccessRequest extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\AccessRequest::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        return $this->user->name;
    }

    public static $searchRelations = [
        'user' => ['name'],
        'establishment' => ['name_fr'],
    ];

    /**
     * The relationships that should be eager loaded on index queries.
     *
     * @var array
     */
    public static $with = ['establishment', 'structure', 'user'];

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Restauration';

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        if ($request->user()->isDecider() || $request->user()->isAgentRestauration()) {
            return $query->where('establishment_id', $request->user()->establishment_id);
        }
        return $query;
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
            BelongsTo::make('Student', 'user', 'App\Nova\User'),
            NovaBelongsToDepend::make('establishment')
                ->placeholder('Select Establishment')
                ->options(
                    Cache::remember('establishments', 60 * 60 * 24, function () {
                        return Establishment::all();
                    })
                ),
            NovaBelongsToDepend::make('Restaurant', 'structure', 'App\Nova\Structure')
                ->placeholder('Select Restaurant')
                ->optionsResolve(function ($establishment) {
                    return $establishment->restaurant()->get();
                })
                ->dependsOn('establishment'),
            Select::make('state')->options([
                'non traité' => 'non traité',
                'accepté' => 'accepté',
                'refusé' => 'refusé'
            ])
                ->default('non traité')
                ->hideWhenCreating(),
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
        return [
            (new AccessRequestsTotal())->width('1/4'),
            (new NotTreatedAccessRequestsTotal())->width('1/4'),
            (new AcceptedAccessRequestsTotal())->width('1/4'),
            (new RefusedAccessRequestsTotal())->width('1/4'),
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
        return [
            new AccessRequestState
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
        return [];
    }
}
