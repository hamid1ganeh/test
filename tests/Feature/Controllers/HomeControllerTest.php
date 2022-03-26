<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Post;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexMethod()
    {
        Post::factory()->count(10)->create();
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewIs('home');
        $response->assertViewHas('posts',Post::latest()->paginate(15));
    }
}
