<?php

namespace App\Nova;

use App\Models\Establishment;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use Orlyapps\NovaBelongsToDepend\NovaBelongsToDepend;

class Place extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Place::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
    ];

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Acceuil';

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
            Text::make('name'),
            Select::make('type')->options([
                'chambre' => 'chambre',
                'amphi' => 'amphi',
                'class' => 'class',
                'sanitaire' => 'sanitaire'
            ]),
            NovaBelongsToDepend::make('establishment')
                ->placeholder('Select Establishment') // Add this just if you want to customize the placeholder
                ->options(Establishment::all()),
            NovaBelongsToDepend::make('structure')
                ->placeholder('Select Structure') // Add this just if you want to customize the placeholder
                ->optionsResolve(function ($establishment) {
                    // Reduce the amount of unnecessary data sent
                    return $establishment->structures()->get(['id', 'name']);
                })
                ->dependsOn('Establishment'),
            Number::make('capacity')->hideFromIndex(),
            Number::make('longitude')->hideFromIndex(),
            Number::make('latitude')->hideFromIndex(),
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
