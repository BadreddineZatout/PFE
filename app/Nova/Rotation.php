<?php

namespace App\Nova;

use App\Models\Bus;
use App\Models\Line;
use App\Models\Role;
use App\Models\User;
use App\Rules\RotationEnd;
use Laravel\Nova\Fields\ID;
use App\Rules\RotationStart;
use Illuminate\Http\Request;
use App\Rules\RotationDriver;
use App\Rules\BusNumberInLine;
use App\Rules\StartEndTimeRule;
use App\Nova\Filters\RotationBus;
use App\Nova\Filters\RotationDate;
use App\Nova\Filters\RotationLine;
use Laravel\Nova\Fields\BelongsTo;
use App\Rules\RotationNumberInLine;
use Laraning\NovaTimeField\TimeField;
use Laravel\Nova\Http\Requests\NovaRequest;
use Titasgailius\SearchRelations\SearchesRelations;
use Orlyapps\NovaBelongsToDepend\NovaBelongsToDepend;
use App\Nova\Filters\RotationDriver as FiltersRotationDriver;

class Rotation extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Rotation::class;

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
        'line' => ['name'],
        'user' => ['name'],
        'bus' => ['matricule']
    ];

    /**
     * The relationships that should be eager loaded on index queries.
     *
     * @var array
     */
    public static $with = ['line', 'bus', 'user'];

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
        if ($request->user()->isAdmin() || $request->user()->isMinister()) return $query;
        return $query->join('lines', 'line_id', 'lines.id')
            ->join('plans', 'lines.plan_id', 'plans.id')
            ->where('plans.establishment_id', $request->user()->establishment_id)
            ->select('rotations.*');
    }

    /**
     * Build a "relatable" query for Buses.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Laravel\Nova\Fields\Field  $field
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableLines(NovaRequest $request, $query)
    {
        return $query->join('plans', 'plan_id', 'plans.id')
            ->where('plans.establishment_id', $request->user()->establishment_id)
            ->select('lines.*');
    }

    /**
     * Build a "relatable" query for Buses.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Laravel\Nova\Fields\Field  $field
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableBuses(NovaRequest $request, $query)
    {
        return $query->where('establishment_id', $request->user()->establishment_id);
    }

    /**
     * Build a "relatable" query for Drivers.
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
        return $query->where([
            'role_id' => Role::where('name', 'Driver')->first()->id,
            'establishment_id' => $request->user()->establishment_id
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
        return ($request->user()->isAdmin() || $request->user()->isMinister())
            ? $this->AdminFields()
            : $this->NonAdminFields($request->user()->establishment_id);
    }

    /**
     * Get the fields displayed by the resource
     * when the user is admin or minister.
     *
     * @return array
     */
    private function AdminFields()
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            NovaBelongsToDepend::make('line')
                ->placeholder('Select Line')
                ->options(Line::all())
                ->rules('required', new RotationNumberInLine),
            NovaBelongsToDepend::make('bus', 'bus', '\App\Nova\Bus')
                ->placeholder('Select Bus')
                ->optionsResolve(function ($line) {
                    return Bus::where('establishment_id', $line->plan->establishment_id)
                        ->get();
                })
                ->dependsOn('line')
                ->rules(new BusNumberInLine),
            NovaBelongsToDepend::make('driver', 'user', '\App\Nova\User')
                ->placeholder('Select Driver')
                ->optionsResolve(function ($line) {
                    return User::where([
                        'role_id' => Role::where('name', 'Driver')->first()->id,
                        'establishment_id' => $line->plan->establishment_id
                    ])->get();
                })
                ->dependsOn('line')
                ->rules(new RotationDriver),
            TimeField::make('start time', 'start_time')
                ->rules('required', new StartEndTimeRule, new RotationStart),
            TimeField::make('end time', 'end_time')
                ->rules('required', new StartEndTimeRule, new RotationEnd),
        ];
    }

    /**
     * Get the fields displayed by the resource
     * when the user is decider or agent transport.
     *
     * @param  mixed $establishment_id
     * @return array
     */
    private function NonAdminFields($establishment_id)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make('line')
                ->rules(new RotationNumberInLine),
            BelongsTo::make('bus', 'bus', '\App\Nova\Bus')
                ->rules(new BusNumberInLine),
            BelongsTo::make('driver', 'user', '\App\Nova\User')
                ->rules(new RotationDriver),
            TimeField::make('start time', 'start_time')
                ->rules('required', new StartEndTimeRule, new RotationStart),
            TimeField::make('end time', 'end_time')
                ->rules('required', new StartEndTimeRule, new RotationEnd),
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
                new RotationLine,
                new RotationBus,
                new FiltersRotationDriver,
                new RotationDate
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
