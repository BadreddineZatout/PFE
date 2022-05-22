<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Nova\Filters\UserRole;
use NovaTesting\NovaAssertions;
use Illuminate\Support\Facades\Auth;

class UserResourceTest extends TestCase
{
    use NovaAssertions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_UserResourceIndex()
    {
        $user = User::findOrFail(1);
        Auth::login($user);

        $response = $this->novaIndex('users');

        $response->assertStatus(200);
        $response->assertResourceCount(User::all()->count());

        $response = $this->novaIndex('users', [
            UserRole::class => Role::ADMIN
        ]);

        $response->assertOk();
        $response->assertResourceCount(User::where('role_id', Role::ADMIN)->count());
    }
    public function test_UserResourceDetails()
    {
        $user = User::findOrFail(1);
        Auth::login($user);

        $response = $this->novaDetail('users', $user->id);
        $response->assertOk();

        $response->assertFieldsInclude([
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'mobile' => $user->mobile,
            'email' => $user->email,
            'birthday' => $user->birthday->format('Y-m-d'),
            'nin' => $user->nin
        ]);
    }

    public function test_UserResourceCreate()
    {
        $user = User::findOrFail(1);
        Auth::login($user);

        $response = $this->novaCreate('users');

        $response->assertRelation('role', function ($roles) {
            return $roles->count() == Role::all()->count();
        });
    }
}
