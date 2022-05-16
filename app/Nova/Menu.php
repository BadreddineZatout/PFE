<?php

namespace App\Nova;

use App\Models\Structure;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Badi\TodayMeal\TodayMeal;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use App\Nova\Filters\MenuDate;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\MenuRestaurant;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Filters\DateFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Lenses\ConnectedEstablishmentsMenus;
use App\Rules\MinMenuQuantity;
use Titasgailius\SearchRelations\SearchesRelations;

class Menu extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Menu::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'main_dish', 'secondary_dish', 'dessert',
    ];

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'structure' => ['name'],
    ];

    /**
     * The relationships that should be eager loaded on index queries.
     *
     * @var array
     */
    public static $with = ['structure'];

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Restauration';

    /**
     * Determine if this resource is available for navigation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function availableForNavigation(Request $request)
    {
        $user = $request->user();
        return $user->isAdmin() || $user->isMinister() || $user->isDecider() || $user->isAgentRestauration();
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
        if ($user->isAdmin() || $user->isMinister()) {
            return $query->join('structures', 'menus.structure_id', 'structures.id')
                ->select('menus.*');
        }
        return $query->join('structures', 'menus.structure_id', 'structures.id')
            ->where('structures.establishment_id', $user->establishment_id)
            ->select('menus.*');
    }

    /**
     * Build a "relatable" query for structures.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Laravel\Nova\Fields\Field  $field
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableStructures(NovaRequest $request, $query)
    {
        if ($request->user()->isAdmin()) return $query->where('type', 'restaurant');
        return $query->where([
            'type' => 'restaurant',
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
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Date::make('date')->rules('required'),
            BelongsTo::make('structure')->rules('required'),
            Select::make('Type')->options([
                'breakfast' => 'breakfast',
                'lunch' => 'lunch',
                'dinner' => 'dinner'
            ])->rules('required'),
            Text::make('Main dish', 'main_dish')
                ->rules('required', 'string', 'max:50'),
            Text::make('Secondairy dish', 'secondary_dish')
                ->rules('required', 'string', 'max:50'),
            Text::make('dessert')
                ->rules('required', 'string', 'max:50'),
            Number::make('quantity')
                ->rules('required', new MinMenuQuantity),
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
        $user = $request->user();
        if ($user->isMinister() || $user->isAdmin()) return [];
        return [
            new TodayMeal(Auth::user()->establishment_id),
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
        if ($request->user()->isAdmin() || $request->user()->isMinister()) {
            return [
                new MenuRestaurant,
                new MenuDate
            ];
        }
        return [
            new MenuDate
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
        if ($request->user()->isUniversityDecider()) return [new ConnectedEstablishmentsMenus()];
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
