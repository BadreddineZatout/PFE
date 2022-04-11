<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use App\Nova\Metrics\PreparedMeal;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Metrics\ConsumedByDay;
use App\Nova\Metrics\LeftoverByDay;
use Laravel\Nova\Http\Requests\NovaRequest;
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
        return [
            new PreparedMeal,
            new ConsumedByDay,
            new LeftoverByDay,
            (new StackedChart())
                ->title('Plat Consommés vs Plat Restés')
                ->model('\App\Models\FoodReservation')
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
                    'uom' => 'day',
                    'latestData' => 7,
                    'showPercentage' => true,
                    'showTotal' => false,
                ])
                ->width('2/3'),
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
