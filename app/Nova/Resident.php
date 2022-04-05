<?php

namespace App\Nova;

use App\Models\Role;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Metrics\ResidentsTotal;
use App\Nova\Metrics\ResidentsRenouvles;
use App\Nova\Metrics\ResidentsNonRenouvles;
use Laravel\Nova\Http\Requests\NovaRequest;
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
    public function title()
    {
        return $this->user->fullname();
    }

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'user' => ['firstname', 'lastname'],
    ];

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
                ->options(\App\Models\Establishment::where('type', '=', 'résidence')->get())
                ->dependsOn('user'),
            NovaBelongsToDepend::make('block')
                ->placeholder('Select Block') // Add this just if you want to customize the placeholder
                ->optionsResolve(function ($residence) {
                    // Reduce the amount of unnecessary data sent
                    return $residence->blocks()->get();
                })
                ->dependsOn('establishment'),
            Number::make('chambre'),
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
        return [
            new ResidentsTotal(),
            new ResidentsRenouvles(),
            new ResidentsNonRenouvles()
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
        return [
            new LensesResidentsRenouvles(),
            new LensesResidentsNonRenouvles()
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
        return [];
    }
}
