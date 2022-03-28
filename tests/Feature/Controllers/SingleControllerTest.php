<?php

namespace Tests\Feature\controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class SingleControllerTest extends TestCase
{

    public function testIndexMethod()
    {
        $this->withoutExceptionHandling();
        $post  = Post::factory()->hasComments(rand(0,3))->create();

        $response = $this->get(route('single',$post));

        $response->assertOk(200);
        $response->assertViewIs('single');
        $response->assertViewHasAll([
            'post'=>$post,
            'comments'=> $post->comments()->latest()->paginate(15)
        ]);
    }

    public function testCommentMethodWhenUserLoggedIn()
    {
       $this->withoutExceptionHandling();
       $user = User::factory()->create();
       $post = Post::factory()->create();

       $date = Comment::factory()->state([
           'user_id' => $user->id,
           'commentable_id' => $post->id
       ])->make()->toArray();

        //    $response = $this->actingAs($user)->post(
        //        route('single.comment',$post),
        //        ['text'=>$date['text'],'title'=>$date['title']]
        //    );
        //$response->assertRedirect(route('single',$post));

        $response = $this->actingAs($user)->
            withHeaders([
                'HTTP_X-Requested-with'=> 'XMLHttpRequest'
            ])->postJson(
                route('single.comment',$post),
            ['text'=>$date['text'],'title'=>$date['title']]
        );

       $response->assertOk()->assertJson([
           'created'=>true
       ]);
       $this->assertDatabaseHas('comments',$date);

    }

    public function testCommentMethodWhenUserNotLoggedIn()
    {
      // $this->withoutExceptionHandling();

       $post = Post::factory()->create();

       $date = Comment::factory()->state([
           'commentable_id' => $post->id
       ])->make()->toArray();

       unset($date['user_id']);

        //    $response = $this->post(
        //        route('single.comment',$post),
        //        ['text'=>$date['text'],'title'=>$date['title']]
        //    );

        //    $response->assertRedirect(route('login'));


        $response = $this->withHeaders([
                'HTTP_X-Requested-with'=> 'XMLHttpRequest'
        ])->postJson(
                route('single.comment',$post),
        ['text'=>$date['text'],'title'=>$date['title']]
        );

        //$response->assertRedirect(route('login'));
        $response->assertUnauthorized();

       $this->assertDatabaseMissing('comments',$date);

    }



    public function testCommentMethodWhenValidRequiredData()
    {
       // $this->withoutExceptionHandling();

       $post = Post::factory()->create();


    //    $response = $this->actingAs(User::factory()->create())
    //     ->post(
    //         route('single.comment',$post),
    //         ['text'=>'d']
    //     );

    //     $response->assertSessionHasErrors([
    //         'text'=>'The text field is required.'
    //     ]);


        $response = $this->actingAs(User::factory()->create())
        ->withHeaders([
                'HTTP_X-Requested-with'=> 'XMLHttpRequest'
        ])->postJson(
            route('single.comment',$post),
            ['text'=>'']
        );

        $response->assertJsonValidationErrors([
            'text'=>'The text field is required.'
        ]);
    }
}
