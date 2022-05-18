<?php

namespace App\Providers;

use Laravel\Nova\Nova;
use App\Models\Resident;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Observable;
use App\Nova\Metrics\TotalBus;
use App\Actions\UserWelcomeCard;
use Badi\UserDetails\UserDetails;
use App\Nova\Metrics\StudentTotal;
use App\Nova\Metrics\WorkersTotal;
use App\Observers\ResidentObserver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Nova\Metrics\ResidencesTotal;
use App\Nova\Metrics\RestaurantsTotal;
use Ericlagarda\NovaTextCard\TextCard;
use App\Nova\Metrics\EstablishmentsTotal;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Observable::make(Resident::class, ResidentObserver::class);
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            UserWelcomeCard::getCard(),
            new StudentTotal,
            new EstablishmentsTotal,
            new ResidencesTotal,
            new WorkersTotal,
            new RestaurantsTotal,
            new TotalBus,
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
