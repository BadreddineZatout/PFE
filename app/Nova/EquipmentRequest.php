<?php

namespace App\Nova;

use App\Nova\Actions\AcceptEquipement;
use App\Nova\Actions\RefuseEquipement;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\EquipmentRequestState;
use App\Nova\Metrics\TotalDemandeEquipment;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Filters\EquipmentRequestResidence;
use App\Nova\Metrics\TotalDemandeEquipmentRefusee;
use App\Nova\Metrics\TotalDemandeEquipmentAcceptee;
use Titasgailius\SearchRelations\SearchesRelations;
use App\Nova\Metrics\TotalDemandeEquipmentNonTraitee;

class EquipmentRequest extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\EquipmentRequest::class;

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
            return $query->join('residents', 'resident_id', 'residents.id')
                ->where('establishment_id', $request->user()->establishment_id)
                ->select('equipment_requests.*');
        }
        return $query;
    }

    /**
     * Build a "relatable" query for equipments.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Laravel\Nova\Fields\Field  $field
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableEquipments(NovaRequest $request, $query)
    {
        if ($request->user()->isAdmin()) return $query;
        return $query->where('establishment_id', $request->user()->establishment_id);
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
            BelongsTo::make('resident'),
            BelongsTo::make('equipment'),
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
            (new TotalDemandeEquipment())->width('1/4'),
            (new TotalDemandeEquipmentNonTraitee())->width('1/4'),
            (new TotalDemandeEquipmentAcceptee())->width('1/4'),
            (new TotalDemandeEquipmentRefusee())->width('1/4'),
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
        if ($request->user()->isAdmin()) {
            return [
                new EquipmentRequestResidence,
                new EquipmentRequestState
            ];
        }
        return [
            new EquipmentRequestState
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
        return [
            (new AcceptEquipement())->showOnTableRow()
                ->confirmText('Are you sure you want to accept this request?')
                ->confirmButtonText('Accept')
                ->cancelButtonText("Don't accept")
                ->canSee(function ($request) {
                    return $request->user()->can('update', EquipmentRequest::class);
                })->onlyOnIndex(),
            (new RefuseEquipement())->showOnTableRow()
                ->confirmText('Are you sure you want to refuse this request?')
                ->confirmButtonText('Refuse')
                ->cancelButtonText("Don't refuse")
                ->canSee(function ($request) {
                    return $request->user()->can('update', EquipmentRequest::class);
                })->onlyOnIndex(),
        ];
    }
}
