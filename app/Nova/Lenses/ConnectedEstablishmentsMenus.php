<?php

namespace App\Nova\Lenses;

use App\Nova\Menu;
use App\Models\Structure;
use App\Nova\Filters\ConnectedEstablishmentsRestaurants;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Lenses\Lens;
use App\Nova\Filters\MenuDate;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\LensRequest;

class ConnectedEstablishmentsMenus extends Lens
{
    /**
     * Get the query builder / paginator for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\LensRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {

        $connected_establishments = [];
        $request->user()->establishment->establishments->each(function ($e) use (&$connected_establishments) {
            $connected_establishments[] = $e->id;
        });
        $structures = Structure::where('type', 'restaurant')->whereIn('establishment_id', $connected_establishments)->select('id')->get();
        return $request->withOrdering($request->withFilters(
            $query->whereIn('structure_id', $structures)
        ));
    }

    /**
     * Get the fields available to the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Date::make('date'),
            BelongsTo::make('structure'),
            Select::make('Type')->options(['breakfast' => 'breakfast', 'lunch' => 'lunch', 'dinner' => 'dinner']),
            Text::make('Plat Principal', 'main_dish'),
            Text::make('Plat Secondaire', 'secondary_dish'),
            Text::make('dessert'),
            Number::make('quantity'),
        ];
    }

    /**
     * Get the filters available for the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new ConnectedEstablishmentsRestaurants,
            new MenuDate
        ];
    }

    /**
     * Get the actions available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return parent::actions($request);
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'connected-establishments-menus';
    }
}
