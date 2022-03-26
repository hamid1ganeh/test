<?php

namespace Tests\Feature\controllers;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SingleControllerTest extends TestCase
{

    public function testIndexMethod()
    {
        $post  = Post::factory()->hasComments(rand(0,3))->create();

        $response = $this->get(route('single',$post));

        $response->assertOk(200);
        $response->assertViewIs('single');
        $response->assertViewHasAll([
            'post'=>$post,
            'comments'=> $post->comments()->latest()->paginate(15)
        ]);
    }
}
