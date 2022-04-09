<?php

namespace App\Nova;

use App\Models\Wilaya;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\BelongsTo;
use Orlyapps\NovaBelongsToDepend\NovaBelongsToDepend;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        return $this->fullname();
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'firstname', 'lastname', 'email',
    ];

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Settings';


    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('firstname')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('lastname')
                ->sortable()
                ->rules('required', 'max:255'),
            Date::make('birthday')->hideFromIndex(),
            Text::make('NIN', 'nin')->hideFromIndex(),
            Text::make('mobile'),
            Text::make('Email')
                ->hideFromIndex()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),
            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),
            BelongsTo::make('role'),
            BelongsTo::make('establishment', 'establishment')->nullable()->searchable(),
            NovaBelongsToDepend::make('Wilaya')
                ->placeholder('Select Wilaya') // Add this just if you want to customize the placeholder
                ->options(Wilaya::all()),
            NovaBelongsToDepend::make('Commune')
                ->placeholder('Select Commune') // Add this just if you want to customize the placeholder
                ->optionsResolve(function ($wilaya) {
                    // Reduce the amount of unnecessary data sent
                    return $wilaya->communes()->get(['id', 'name']);
                })
                ->dependsOn('Wilaya'),
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
        return [];
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
