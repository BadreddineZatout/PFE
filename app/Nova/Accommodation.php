<?php

namespace App\Nova;

use App\Models\Role;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Models\Establishment;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Metrics\TotalDemandeHebergement;
use Titasgailius\SearchRelations\SearchesRelations;
use App\Nova\Metrics\TotalDemandeHebergementRefusee;
use App\Nova\Metrics\TotalDemandeHebergementAcceptee;
use Orlyapps\NovaBelongsToDepend\NovaBelongsToDepend;
use App\Nova\Metrics\TotalDemandeHebergementNonTraitee;

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
        return $this->user->fullname();
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $searchRelations = [
        'user' => ['firstname', 'lastname'],
        'establishment' => ['name'],
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
        return $query->where('role_id', Role::where('name', 'student')->first()->id);
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