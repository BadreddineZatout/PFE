<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Models\Establishment;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use Orlyapps\NovaBelongsToDepend\NovaBelongsToDepend;
use Titasgailius\SearchRelations\SearchesRelations;

class Incident extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Signalement::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'user' => ['name'],
        'establishment' => ['name_fr'],
        'structure' => ['name'],
        'place' => ['name']
    ];

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Incident';

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
            BelongsTo::make('user'),
            NovaBelongsToDepend::make('establishment')
                ->placeholder('Select Establishment')
                ->options(Establishment::all()),
            NovaBelongsToDepend::make('structure')
                ->placeholder('Select Structure')
                ->optionsResolve(function ($establishment) {
                    return $establishment->structures;
                })->dependsOn('establishment')
                ->nullable(),
            NovaBelongsToDepend::make('place')
                ->placeholder('Select Place')
                ->optionsResolve(function ($structure) {
                    return $structure->places;
                })->dependsOn('structure')
                ->nullable(),
            Text::make('description')->hideFromIndex(),
            Date::make('date'),
            Boolean::make('treated', 'is_treated'),
            Boolean::make('anonymous', 'is_anonymous')->hideFromIndex(),
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
