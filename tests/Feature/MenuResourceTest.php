<?php

namespace Tests\Feature;

use App\Models\Menu;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Badi\TodayMeal\TodayMeal;
use NovaTesting\NovaAssertions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MenuResourceTest extends TestCase
{
    use NovaAssertions;

    public function test_MenuResourceIndex()
    {
        $user = User::findOrFail(1);
        Auth::login($user);

        $response = $this->novaIndex('menus');

        $response->assertStatus(200);
        $response->assertResourceCount(Menu::all()->count());
        $response->assertCardsExclude(new TodayMeal(Auth::user()->establishment_id));
        $response->assertCanView();
        $response->assertCanCreate();
        $response->assertCanUpdate();
        $response->assertCanDelete();

        Auth::logout();

        $user = User::findOrFail(1);
        $user->role_id = Role::DECIDER;
        Auth::login($user);

        $response = $this->novaIndex('menus');

        $response->assertResourceCount(
            Menu::join('structures', 'menus.structure_id', 'structures.id')
                ->where('structures.establishment_id', $user->establishment_id)->count()
        );

        $response->assertCardsInclude(new TodayMeal(Auth::user()->establishment_id));

        $response->assertCanView();
        $response->assertCannotCreate();
        $response->assertCannotUpdate();
        $response->assertCannotDelete();

        Auth::logout();

        $user = User::findOrFail(1);
        $user->role_id = Role::AGENT_RESTAURATION;
        Auth::login($user);

        $response = $this->novaIndex('menus');

        $response->assertResourceCount(
            Menu::join('structures', 'menus.structure_id', 'structures.id')
                ->where('structures.establishment_id', $user->establishment_id)->count()
        );

        $response->assertCardsInclude(new TodayMeal(Auth::user()->establishment_id));

        $response->assertCanView();
        $response->assertCanCreate();
        $response->assertCanUpdate();
        $response->assertCanDelete();
    }
}
