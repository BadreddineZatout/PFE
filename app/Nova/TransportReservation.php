<?php

namespace App\Nova;

use App\Models\Role;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\TransportDate;
use App\Nova\Filters\TransportEstablishment;
use App\Nova\Metrics\Transportations;
use App\Nova\Filters\TransportRotation;
use App\Nova\Metrics\TransportedStudent;
use Laravel\Nova\Http\Requests\NovaRequest;
use Nemrutco\NovaGlobalFilter\NovaGlobalFilter;
use Coroowicaksono\ChartJsIntegration\LineChart;
use Coroowicaksono\ChartJsIntegration\StackedChart;
use Titasgailius\SearchRelations\SearchesRelations;

class TransportReservation extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\TransportReservation::class;

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
        'user' => ['firstname', 'lastname'],
        'rotation.line' => ['name']
    ];

    /**
     * The relationships that should be eager loaded on index queries.
     *
     * @var array
     */
    public static $with = ['user', 'rotation'];

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Transport';

    public static $tableStyle = 'tight';


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
        if ($request->user()->isAdmin() || $request->user()->isMinister()) return $query;
        if ($request->user()->isUniversityDecider())
            return $query->join('users', 'user_id', 'users.id')
                ->where([
                    'establishment_id' => $request->user()->establishment_id,
                    'role_id' => Role::where('name', 'Student')->first()->id
                ])
                ->select('transport_reservations.*');
        return $query->join('residents', 'transport_reservations.user_id', 'residents.user_id')
            ->where('establishment_id', $request->user()->establishment_id)
            ->select('transport_reservations.*');
    }

    /**
     * Build a "relatable" query for students.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Laravel\Nova\Fields\Field  $field
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableUsers(NovaRequest $request, $query)
    {
        return $query->where('role_id', Role::where('name', 'Student')->first()->id);
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
            Date::make('date'),
            BelongsTo::make('rotation'),
            BelongsTo::make('student', 'user', '\App\Nova\User'),
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
        $stats = [
            new TransportedStudent(),
            (new Transportations)->width('2/3'),
        ];
        if ($request->user()->isAdmin() || $request->user()->isMinister())
            return [
                (new NovaGlobalFilter([
                    new TransportEstablishment
                ]))->resettable(),
                ...$stats
            ];
        return [
            ...$stats,
            (new StackedChart())
                ->title('Reservations')
                ->model('\App\Models\TransportReservation')
                ->join('rotations', 'transport_reservations.rotation_id', '=', 'rotations.id')
                ->join('lines', 'rotations.line_id', '=', 'lines.id')
                ->join('plans', 'lines.plan_id', '=', 'planid')
                ->options([
                    'queryFilter' => array([
                        'key' => 'plans.establishment_id',
                        'operator' => '=',
                        'value' => $request->user()->establishment_id
                    ]),
                    'uom' => 'day',
                    'latestData' => 30,
                    'showTotal' => false,
                ]),
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
            new TransportDate,
            new TransportRotation
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
