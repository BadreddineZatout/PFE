<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Nova\Filters\MealType;
use App\Nova\Metrics\Leftovers;
use NovaTesting\NovaAssertions;
use App\Nova\Metrics\PreparedMeal;
use App\Nova\Metrics\ConsumedByDay;
use App\Nova\Metrics\ConsumedMeals;
use App\Nova\Metrics\LeftoverByDay;
use Illuminate\Support\Facades\Auth;
use App\Nova\Metrics\ReservationsByDay;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FoodReservationResourceTest extends TestCase
{
    use NovaAssertions;

    public function test_ReservationResourceIndex()
    {
        $user = User::findOrFail(1);
        Auth::login($user);
        $response = $this->novaIndex('reservations');

        $response->assertStatus(200);

        $response->assertCardCount(7);
        $response->assertCardsInclude(PreparedMeal::class);
        $response->assertCardsInclude(ReservationsByDay::class);
        $response->assertCardsInclude(ConsumedMeals::class);
        $response->assertCardsInclude(Leftovers::class);
        $response->assertCardsInclude(ConsumedByDay::class);
        $response->assertCardsInclude(LeftoverByDay::class);

        $response = $this->novaIndex('reservations', [
            MealType::class => 'breakfast'
        ]);

        $response->assertResourceCount(2);
    }

    public function test_ReservationResourceIndexForDecider()
    {
        $user = User::findOrFail(3);
        Auth::login($user);
        $response = $this->novaIndex('reservations');

        $response->assertStatus(200);
        $response->assertCardCount(7);
        $response->assertCardsInclude(PreparedMeal::class);
        $response->assertCardsInclude(ReservationsByDay::class);
        $response->assertCardsInclude(ConsumedMeals::class);
        $response->assertCardsInclude(Leftovers::class);
        $response->assertCardsInclude(ConsumedByDay::class);
        $response->assertCardsInclude(LeftoverByDay::class);
    }
}
