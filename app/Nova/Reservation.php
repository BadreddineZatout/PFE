<?php

namespace App\Nova;

use App\Models\Structure;
use Laravel\Nova\Fields\ID;
use App\Nova\Filters\Wilaya;
use Illuminate\Http\Request;
use App\Nova\Filters\MealType;
use App\Nova\Metrics\Leftovers;
use Laravel\Nova\Fields\Boolean;
use App\Nova\Metrics\PreparedMeal;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\Establishment;
use App\Nova\Metrics\ConsumedByDay;
use App\Nova\Metrics\ConsumedMeals;
use App\Nova\Metrics\LeftoverByDay;
use Illuminate\Support\Facades\Auth;
use App\Nova\Filters\ReservationDate;
use App\Nova\Metrics\ReservationsByDay;
use Badi\TodayMeal\TodayMeal;
use Laravel\Nova\Http\Requests\NovaRequest;
use Nemrutco\NovaGlobalFilter\NovaGlobalFilter;
use Coroowicaksono\ChartJsIntegration\StackedChart;

class Reservation extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\FoodReservation::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * The relationships that should be eager loaded on index queries.
     *
     * @var array
     */
    public static $with = ['user', 'menu'];

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Restauration';

    public static $tableStyle = 'tight';

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
            BelongsTo::make('user'),
            BelongsTo::make('menu'),
            Boolean::make('has ate', 'has_ate')
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
        $filter = new NovaGlobalFilter([
            new Establishment,
            new MealType
        ]);
        $filter->resettable();
        return [
            new TodayMeal,
            $filter,
            (new PreparedMeal)->width('1/4'),
            (new ReservationsByDay)->width('1/4'),
            (new ConsumedMeals)->width('1/4'),
            (new Leftovers)->width('1/4'),
            (new ConsumedByDay)->width('1/2'),
            (new LeftoverByDay)->width('1/2'),
            (new StackedChart())
                ->title('Plat Reservés Consommés vs Plat Reservés Restants')
                ->model('\App\Models\FoodReservation')
                ->join('menus', 'menus.id', '=', 'food_reservations.menu_id')
                ->series(array([
                    'label' => 'Plats Consommés',
                    'filter' => [
                        'key' => 'has_ate', // State Column for Count Calculation Here
                        'value' => true
                    ],
                    'backgroundColor' => '#4055B2',
                ], [
                    'label' => 'Plats Restés',
                    'filter' => [
                        'key' => 'has_ate', // State Column for Count Calculation Here
                        'value' => 'false'
                    ],
                    'backgroundColor' => '#D7E1F3',
                ]))
                ->options([
                    'queryFilter' => array([
                        'key' => 'structure_id',
                        'operator' => '=',
                        'value' => Structure::where('establishment_id', Auth::user()->establishment_id)->first()->id
                    ]),
                    'uom' => 'day',
                    'latestData' => 7,
                    'showTotal' => false,
                ])
                ->width('full'),
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
            new Establishment,
            new Wilaya,
            new ReservationDate,
            new MealType
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
        return [];
    }
}
