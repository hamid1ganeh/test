<?php

namespace Tests\Feature\MiddleWares;

use App\Http\Middleware\CheckUserIsAdmin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Http\Request;

class CheckUserIsAdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function testWhenUserIsNotAdmin()
    {
        //$this->withoutExceptionHandling();
        $user = User::factory()->user()->create();
        $this->actingAs($user);

        $request = Request::create('/admin','GET');

        $middleware = new CheckUserIsAdmin();

        $response = $middleware->handle($request,function(){});

        $this->assertEquals($response->getStatusCode(),302);
    }

    public function testWhenUserIsAdmin()
    {
        //$this->withoutExceptionHandling();
        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        $request = Request::create('/admin','GET');

        $middleware = new CheckUserIsAdmin();

        $response = $middleware->handle($request,function(){});

        $this->assertEquals($response,null);
    }

    public function testWhenUserIsNotLoggedIn()
    {
        //$this->withoutExceptionHandling();

        $request = Request::create('/admin','GET');

        $middleware = new CheckUserIsAdmin();

        $response = $middleware->handle($request,function(){});

        $this->assertEquals($response->getStatusCode(),302);
    }
}
