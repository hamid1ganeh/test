<?php

namespace Tests\Feature\Controllers\Admin;

use App\Http\Middleware\UserActivity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserControllerTest extends TestCase
{

    public function testShowMethod()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware([UserActivity::class]);

        $user = User::factory()->create();

        Cache::shouldReceive('get')
            ->with("user-{$user->id}-staus")
            ->once()
            ->andReturn('online');

        $this->
            actingAs(User::factory()->admin()->create())
            ->get(route('user.show',$user->id))
            ->assertOk()
            ->assertViewIs('admin.user.show')
            ->assertViewHasAll([
                'user' => $user,
                'userStatus' => 'online'
            ]);
    }
}
