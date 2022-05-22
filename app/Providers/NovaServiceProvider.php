<?php

namespace App\Providers;

use Laravel\Nova\Nova;
use App\Models\Resident;
use Laravel\Nova\Observable;
use App\Observers\ResidentObserver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Services\DashboardCardsService;
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
                'badi@test.dz'
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
        $user = Auth::user();
        if ($user->isMinister() || $user->isAdmin())
            return DashboardCardsService::getMinisterCards();
        if ($user->isDecider())
            return DashboardCardsService::getDeciderCards();
        if ($user->isAgentRestauration())
            return DashboardCardsService::getRestaurationAgentCards();
        if ($user->isAgentHebergement())
            return DashboardCardsService::getHebergementAgentCards();
        if ($user->isAgentTransport())
            return DashboardCardsService::getTransportAgentCards();
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
