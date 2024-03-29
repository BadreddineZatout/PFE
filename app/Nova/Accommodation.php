<?php

namespace App\Nova;

use App\Models\Role;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Models\Establishment;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsTo;
use Illuminate\Support\Facades\Cache;
use App\Nova\Actions\AcceptAccommodation;
use App\Nova\Actions\RefuseAccommodation;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Metrics\TotalDemandeHebergement;
use App\Nova\Filters\AccommodationRequestState;
use Titasgailius\SearchRelations\SearchesRelations;
use App\Nova\Metrics\TotalDemandeHebergementRefusee;
use App\Nova\Metrics\TotalDemandeHebergementAcceptee;
use Orlyapps\NovaBelongsToDepend\NovaBelongsToDepend;
use App\Nova\Metrics\TotalDemandeHebergementNonTraitee;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class Accommodation extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Accommodation::class;

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
    public static $with = ['establishment', 'structure', 'place', 'user'];

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
            return $query->where('establishment_id', $request->user()->establishment_id);
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
        return $query->where('role_id', Role::STUDENT);
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
            NovaBelongsToDepend::make('residence', 'establishment')
                ->placeholder('Select Residence')
                ->options(Cache::remember('residences', 60 * 60 * 24, function () {
                    return Establishment::where('type', '=', 'résidence')->get();
                })),
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
            (new TotalDemandeHebergement)->width('1/4'),
            (new TotalDemandeHebergementNonTraitee)->width('1/4'),
            (new TotalDemandeHebergementAcceptee)->width('1/4'),
            (new TotalDemandeHebergementRefusee)->width('1/4')
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
            new AccommodationRequestState
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
            (new AcceptAccommodation())->showOnTableRow()
                ->confirmText('Are you sure you want to accept this request?')
                ->confirmButtonText('Accept')
                ->cancelButtonText("Don't accept")
                ->canSee(function ($request) {
                    return $request->user()->can('update', Accommodation::class);
                }),
            (new RefuseAccommodation())->showOnTableRow()
                ->confirmText('Are you sure you want to refuse this request?')
                ->confirmButtonText('Refuse')
                ->cancelButtonText("Don't refuse")
                ->canSee(function ($request) {
                    return $request->user()->can('update', Accommodation::class);
                }),
            (new DownloadExcel)->onlyOnIndex()->canSee(fn ($request) => $request->user()->isMinister())
        ];
    }
}
