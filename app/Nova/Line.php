<?php

namespace App\Nova;

use App\Nova\Filters\BusEstablishment;
use App\Nova\Filters\LineEstablishment;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laraning\NovaTimeField\TimeField;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;
use Titasgailius\SearchRelations\SearchesRelations;

class Line extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Line::class;

    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name'
    ];

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'plan.establishment' => ['name_fr'],
    ];

    /**
     * The relationships that should be eager loaded on index queries.
     *
     * @var array
     */
    public static $with = ['plan'];

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
            return $query->join('plans', 'plan_id', 'plans.id')
                ->where('plans.establishment_id', $request->user()->establishment_id)
                ->select('lines.*');
        }
        return $query;
    }

    /**
     * Build a "relatable" query for plans.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Laravel\Nova\Fields\Field  $field
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatablePlans(NovaRequest $request, $query)
    {
        if ($request->user()->isDecider() || $request->user()->isAgentTransport())
            return $query->where('establishment_id', $request->user()->establishment_id);
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
            BelongsTo::make('plan'),
            Text::make('name'),
            Text::make('arab name', 'name_arabe')->hideFromIndex(),
            TimeField::make('start time', 'start_time'),
            TimeField::make('end time', 'end_time'),
            Number::make('Rotations Number', 'rotations_number'),
            Number::make('bus number', 'bus_number'),
            Boolean::make('aller retour', 'aller_retour'),
            BelongsToMany::make('stops')
                ->fields(function ($request, $relatedModel) {
                    return [
                        Text::make('order'),
                    ];
                }),
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
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        if ($request->user()->isAdmin() || $request->user()->isMinister())
            return [
                new LineEstablishment
            ];
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
        return [];
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
