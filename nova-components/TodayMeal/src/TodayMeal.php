<?php

namespace Badi\TodayMeal;

use App\Models\Menu;
use App\Models\Structure;
use Carbon\Carbon;
use Laravel\Nova\Card;

class TodayMeal extends Card
{

    public function __construct($establishment_id)
    {
        $this->getTodayMeal($establishment_id);
    }
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = '1/3';

    public function getTodayMeal($establishment_id)
    {
        $restaurant = Structure::where([
            'type' => 'restaurant',
            'establishment_id' => $establishment_id
        ])->first();
        $menu = Menu::where([
            'structure_id' => $restaurant->id,
            'date' => Carbon::now()->format('Y-m-d')
        ])->first();
        return $this->withMeta([
            'menu' => $menu
        ]);
    }

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'today-meal';
    }
}
