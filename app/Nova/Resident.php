<?php

namespace App\Nova;

use App\Models\Role;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Models\Establishment;
use App\Nova\Actions\ResidentNotRenewed;
use App\Nova\Actions\ResidentRenewed;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Metrics\ResidentsTotal;
use App\Nova\Metrics\ResidentStudents;
use App\Nova\Filters\ResidentResidence;
use App\Nova\Filters\ResidentUniversity;
use App\Nova\Filters\UserUniversity;
use App\Nova\Metrics\ResidentsRenouvles;
use App\Nova\Metrics\ResidentByResidence;
use App\Nova\Metrics\ResidentByUniversity;
use App\Nova\Metrics\ResidentsNonRenouvles;
use Laravel\Nova\Http\Requests\NovaRequest;
use Nemrutco\NovaGlobalFilter\NovaGlobalFilter;
use Titasgailius\SearchRelations\SearchesRelations;
use Orlyapps\NovaBelongsToDepend\NovaBelongsToDepend;
use App\Nova\Lenses\ResidentsRenouvles as LensesResidentsRenouvles;
use App\Nova\Lenses\ResidentsNonRenouvles as LensesResidentsNonRenouvles;

class Resident extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Resident::class;

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
    ];

    /**
     * The relationships that should be eager loaded on index queries.
     *
     * @var array
     */
    public static $with = ['establishment', 'structure', 'place'];

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Hebergement';

    /**
     * Determine if this resource is available for navigation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function availableForNavigation(Request $request)
    {
        return $request->user()->isAdmin() || $request->user()->isMinister() || $request->user()->isDecider() || $request->user()->isAgentHebergement();
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
        $user = $request->user();
        if ($user->isResidenceDecider() || $user->isAgentHebergement()) {
            return $query->where('residents.establishment_id', $user->establishment_id);
        }
        if ($user->isUniversityDecider()) {
            return $query->join('users', 'users.id', 'user_id')
                ->where('users.establishment_id', $user->establishment_id)
                ->whereIn('residents.establishment_id', $user->establishment->establishments->pluck('id'))
                ->select('residents.*');
        }
        return $query;
    }

    /**
     * Build a "relatable" query for Students.
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
        if ($request->user()->isAgentHebergement()) {
            return $query->whereIn('users.establishment_id', $request->user()->establishment->establishments->pluck('id'));
        }
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
            BelongsTo::make('Student', 'user', 'App\Nova\Student'),
            NovaBelongsToDepend::make('residence', 'establishment')
                ->placeholder('Select Residence')
                ->options(Establishment::where('type', '=', 'résidence')->get()),
            NovaBelongsToDepend::make('block', 'structure', 'App\Nova\Structure')
                ->placeholder('Select Block')
                ->optionsResolve(function ($residence) {
                    return $residence->blocks()->get();
                })
                ->dependsOn('establishment'),
            NovaBelongsToDepend::make('chambre', 'place', 'App\Nova\Place')
                ->placeholder('Select Chambre')
                ->optionsResolve(function ($structure) {
                    return $structure->chambres()->get();
                })
                ->dependsOn('structure'),
            Select::make('State')->options([
                'renouvlé' => 'renouvlé',
                'non renouvlé' => 'non renouvlé'
            ]),
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
        if ($request->user()->isUniversityDecider()) {
            return [
                new ResidentsTotal(),
                new ResidentStudents(),
                new ResidentByResidence(),
            ];
        }
        if ($request->user()->isAdmin() || $request->user()->isMinister()) {
            return [
                (new NovaGlobalFilter([
                    new UserUniversity,
                    new ResidentResidence
                ]))->resettable(),
                (new ResidentStudents())->width('1/2'),
                (new ResidentByResidence())->width('1/2'),
                new ResidentsTotal(),
                new ResidentsRenouvles(),
                new ResidentsNonRenouvles(),
            ];
        }
        return [
            new ResidentsTotal(),
            new ResidentsRenouvles(),
            new ResidentsNonRenouvles(),
            new ResidentByUniversity()
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
        if ($request->user()->isAgentHebergement() || $request->user()->isResidenceDecider()) {
            return [
                new ResidentUniversity
            ];
        }

        if ($request->user()->isUniversityDecider()) {
            return [
                new ResidentResidence
            ];
        }

        return [
            new ResidentResidence,
            new ResidentUniversity,
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
        if ($request->user()->isResidenceDecider() || $request->user()->isAgentHebergement()) {
            return [
                new LensesResidentsRenouvles(),
                new LensesResidentsNonRenouvles()
            ];
        }
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
        if ($request->user()->isUniversityDecider()) return [];

        return [
            (new ResidentRenewed())->showOnTableRow()
                ->confirmText('Are you sure you want to do this action?')
                ->confirmButtonText('YES')
                ->cancelButtonText("NO")
                ->canSee(function ($request) {
                    return $request->user()->can('update', Resident::class);
                }),
            (new ResidentNotRenewed())->showOnTableRow()
                ->confirmText('Are you sure about this action?')
                ->confirmButtonText('YES')
                ->cancelButtonText("NO")
                ->canSee(function ($request) {
                    return $request->user()->can('update', Resident::class);
                }),
        ];
    }
}
