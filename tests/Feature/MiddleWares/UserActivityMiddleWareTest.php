<?php

namespace Tests\Feature\Middlewares;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Middleware\UserActivity;
use Illuminate\Support\Facades\Cache;

class UserActivityMiddleWareTest extends TestCase
{

    public function testCanSetUserActivityInCacheWhenUserLoggedIn()
    {
        //$this->withoutExceptionHandling();
        $user = User::factory()->user()->create();
        $this->actingAs($user);

        $request = Request::create('/','GET');

        $middleware = new UserActivity();

        $response = $middleware->handle($request,function(){});

        $this->assertNull($response);

        $this->assertEquals('online',Cache::get("user-{$user->id}-staus"));

        $this->travel(11)->second();
        $this->assertNull(Cache::get("user-{$user->id}-staus"));
    }

    public function testCanSetUserActivityInCacheWhenUserNotLoggedIn()
    {
        //$this->withoutExceptionHandling();
        $request = Request::create('/','GET');
        $middleware = new UserActivity();
        $response = $middleware->handle($request,function(){});
        $this->assertNull($response);
    }

    public function testUSerActivityMiddlewareSerInWebMiddlewareGroup()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this
        ->actingAs($user)
        ->get(route('home'))
        ->assertOk();

        $this->assertEquals('online',Cache::get("user-{$user->id}-staus"));
        $this->assertEquals(request()->route()->middleware(),['web']);
    }
}
