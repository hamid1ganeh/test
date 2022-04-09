<?php

namespace Tests\Feature\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;

class RegisterControllerTest extends TestCase
{

    public function testUserCanRegister()
    {
        $this->withoutExceptionHandling();

        $data = User::factory()
        ->user()
        ->make()
        ->toArray();


        $password = '12345678';

        Event::fake();

        $response = $this->post(route('register'),array_merge($data,[
            'password'=>$password,
            'password_confirmation'=>$password
        ]));

        $response->assertRedirect();

         $this->assertDatabaseHas('users',$data);
         Event::assertDispatched(Registered::class);

    }
}
