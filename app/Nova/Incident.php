<?php

namespace App\Nova;

use App\Models\Signalement;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Models\Establishment;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use App\Rules\AnonymousUserRule;
use Laravel\Nova\Fields\Boolean;
use App\Nova\Filters\IncidentDate;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Actions\TreatIncident;
use App\Nova\Metrics\IncidentsTotal;
use App\Nova\Lenses\AnonymousReports;
use App\Nova\Lenses\TreatedIncidents;
use App\Nova\Metrics\ReportedIncidents;
use App\Nova\Lenses\NotTreatedIncidents;
use App\Nova\Filters\IncidentEstablishment;
use App\Nova\Metrics\ReportedIncidentsType;
use App\Nova\Metrics\TreatedIncidentsTotal;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Metrics\ReportedIncidentsState;
use App\Nova\Metrics\NotTreatedIncidentsTotal;
use Titasgailius\SearchRelations\SearchesRelations;
use Orlyapps\NovaBelongsToDepend\NovaBelongsToDepend;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

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
     * Determine if this resource is available for navigation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function availableForNavigation(Request $request)
    {
        return $request->user()->isAdmin()
            || $request->user()->isMinister()
            || $request->user()->isDecider()
            || $request->user()->isAgentIncident();
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
        if ($request->user()->isDecider() || $request->user()->isAgentIncident())
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
            BelongsTo::make('user')
                ->rules(new AnonymousUserRule)
                ->nullable(),
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
        return [
            new IncidentsTotal(),
            new TreatedIncidentsTotal(),
            new NotTreatedIncidentsTotal(),
            (new ReportedIncidentsState())->width('1/2'),
            (new ReportedIncidentsType())->width('1/2'),
            (new ReportedIncidents())->width('full'),
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
        if ($request->user()->isAdmin() || $request->user()->isMinister())
            return [
                new IncidentDate,
                new IncidentEstablishment
            ];
        return [new IncidentDate];
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
            new TreatedIncidents(),
            new NotTreatedIncidents(),
            new AnonymousReports()
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
            (new TreatIncident())->showOnTableRow()
                ->confirmText('Are you sure you want to do this action?')
                ->confirmButtonText('Confirm')
                ->cancelButtonText("Cancel")
                ->canSee(function ($request) {
                    return $request->user()->can('update', Signalement::class);
                }),
            (new DownloadExcel)->onlyOnIndex()->canSee(fn ($request) => $request->user()->isMinister())
        ];
    }
}
