<?php

namespace App\Nova;

use App\Models\Wilaya;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Laravel\Nova\Http\Requests\NovaRequest;
use Orlyapps\NovaBelongsToDepend\NovaBelongsToDepend;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class Establishment extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Establishment::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name_fr';

    /**
     * Get the search result subtitle for the resource.
     *
     * @return string
     */
    public function subtitle()
    {
        return "{$this->wilaya->name} - {$this->commune->name}";
    }


    /**
     * The relationships that should be eager loaded on index queries.
     *
     * @var array
     */
    public static $with = ['wilaya', 'commune', 'establishments'];

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Settings';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name_fr', 'name_arabe'
    ];

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = false;


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
            Text::make('name', 'name_fr'),
            Text::make('name arabe', 'name_arabe'),
            Date::make('creation date', 'creation_date'),
            NovaBelongsToDepend::make('wilaya')
                ->placeholder('Select Wilaya')
                ->options(Cache::rememberForever('wilayas', function () {
                    return Wilaya::all();
                })),
            NovaBelongsToDepend::make('commune')
                ->placeholder('Select Commune')
                ->optionsResolve(function ($wilaya) {
                    return $wilaya->communes()->get(['id', 'name']);
                })
                ->dependsOn('Wilaya'),
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
