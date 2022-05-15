<?php

namespace App\Nova;

use App\Nova\Filters\EquipmentResidence;
use App\Rules\MinEquipmentQuantity;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Equipment extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Equipment::class;

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
     * The relationships that should be eager loaded on index queries.
     *
     * @var array
     */
    public static $with = ['establishment'];


    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Hebergement';

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        if ($request->user()->isResidenceDecider() || $request->user()->isAgentHebergement()) {
            return $query->where('establishment_id', $request->user()->establishment_id);
        }
        return $query;
    }

    /**
     * Build a "relatable" query for residences.
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
        if ($request->user()->isAdmin()) return $query->where('type', 'résidence');
        return $query->where([
            'id' => $request->user()->establishment_id,
            'type' => 'résidence'
        ]);
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
            Text::make('name')
                ->rules('required', 'string', 'max:50'),
            BelongsTo::make('residence', 'establishment')
                ->searchable()
                ->withSubtitles()
                ->rules('required'),
            Number::make('quantity')
                ->rules('required', new MinEquipmentQuantity),
            Boolean::make('bookable')
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
        if ($request->user()->isAdmin()) {
            return [
                new EquipmentResidence
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
