<?php

namespace App\Providers;

use App\Models\EquipmentRequest;
use App\Models\Line;
use Laravel\Nova\Nova;
use App\Models\Resident;
use Laravel\Nova\Observable;
use App\Models\Establishment;
use App\Models\Menu;
use App\Observers\EquipmentRequestObserver;
use App\Observers\LineObserver;
use App\Observers\ResidentObserver;
use Illuminate\Support\Facades\Gate;
use App\Services\DashboardCardsService;
use App\Observers\EstablishmentObserver;
use App\Observers\MenuObserver;
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
        Observable::make(Establishment::class, EstablishmentObserver::class);
        Observable::make(Line::class, LineObserver::class);
        Observable::make(Menu::class, MenuObserver::class);
        Observable::make(EquipmentRequest::class, EquipmentRequestObserver::class);
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
                'badi@test.dz',
                'm_koudil@esi.dz'
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
        return DashboardCardsService::getCards();
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
