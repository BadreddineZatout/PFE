<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Models\Establishment;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Illuminate\Support\Facades\Cache;
use Laravel\Nova\Http\Requests\NovaRequest;
use Orlyapps\NovaBelongsToDepend\NovaBelongsToDepend;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class Place extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Place::class;

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
        'name',
    ];

    /**
     * The relationships that should be eager loaded on index queries.
     *
     * @var array
     */
    public static $with = ['establishment', 'structure'];

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
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Text::make('name'),
            NovaBelongsToDepend::make('establishment')
                ->placeholder('Select Establishment')
                ->options(Cache::remember('establishments', 60 * 60 * 24, function () {
                    return Establishment::all();
                })),
            NovaBelongsToDepend::make('structure')
                ->placeholder('Select Structure')
                ->optionsResolve(function ($establishment) {
                    return $establishment->structures()->get(['id', 'name']);
                })
                ->dependsOn('Establishment'),
            Select::make('type')->options([
                'chambre' => 'chambre',
                'amphi' => 'amphi',
                'class' => 'class',
                'sanitaire' => 'sanitaire'
            ]),
            Number::make('capacity')->hideFromIndex(),
            Number::make('longitude')->hideFromIndex(),
            Number::make('latitude')->hideFromIndex(),
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
            (new DownloadExcel)->onlyOnIndex()->canSee(fn ($request) => $request->user()->isMinister())
        ];
    }
}
