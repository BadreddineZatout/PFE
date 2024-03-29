<?php

namespace App\Nova;

use App\Nova\Actions\ReturnEquipment;
use App\Nova\Filters\ReturnDateFilter;
use App\Nova\Filters\TakeDateFilter;
use App\Nova\Lenses\ReturnedEquipment;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;
use Titasgailius\SearchRelations\SearchesRelations;

class TakenEquipment extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\TakenEquipment::class;

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
        'resident.user' => ['firstname', 'lastname'],
        'equipment' => ['name'],
    ];

    /**
     * The relationships that should be eager loaded on index queries.
     *
     * @var array
     */
    public static $with = ['resident', 'equipment'];

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Accommodation';

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
            return $query->join('residents', 'resident_id', 'residents.id')
                ->where('establishment_id', $request->user()->establishment_id)
                ->select('taken_equipment.*');
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
            BelongsTo::make('resident')->required(),
            BelongsTo::make('equipment')->required(),
            Number::make('quantity')->required(),
            Date::make('take date', 'take_date')->required(),
            Date::make('return date', 'return_date')->nullable()
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
            new TakeDateFilter,
            new ReturnDateFilter
        ];
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
            new ReturnedEquipment()
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
            (new ReturnEquipment())->showOnTableRow()
                ->confirmText('Are you sure you want to accept this request?')
                ->confirmButtonText('Accept')
                ->cancelButtonText("Don't accept")
                ->canSee(function ($request) {
                    return $request->user()->can('update', TakenEquipment::class);
                }),
        ];
    }
}
