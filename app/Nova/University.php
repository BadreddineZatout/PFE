<?php

namespace App\Nova;

use App\Models\Establishment as EstablishmentModel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class University extends Establishment
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Establishment::class;


    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Acceuil';

    /**
     * Determine if this resource is available for navigation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function availableForNavigation(Request $request)
    {
        return $request->user()->isAdmin() || $request->user()->isMinister();
    }

    /**
     * Build a "relatable" query for Establishments.
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
        return $query->where('id', '!=', $request->resourceId)
            ->where('type', 'résidence')
            ->whereNotIn('id', EstablishmentModel::find($request->resourceId)->establishments->pluck('id'));
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
        return $query->where('type', '!=', 'résidence');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $fields =  parent::fields($request);
        array_splice($fields, 4, 0, [Select::make('Type')->options([
            'université' => 'université',
            'école superieure' => 'école superieure',
            'institue' => 'institue',
        ])->hideFromIndex()]);
        $fields[] = BelongsToMany::make('Residences', 'Establishments');

        return $fields;
    }
}
