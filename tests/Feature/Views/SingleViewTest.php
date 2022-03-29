<?php

namespace Tests\Feature\Views;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use DOMDocument;
use DOMXPath;

class SingleViewTest extends TestCase
{
    use RefreshDatabase;

    public function testSingleViewRenderdWhenUserLoggedIn()
    {
       // $this->withoutExceptionHandling();
        $post = Post::factory()->create();
        $comments = [];

        $viwe = (string)$this->actingAs(User::factory()->create())->view(
            'single',
            compact('post','comments')
        );

        $dom = new \DOMDocument();
        $dom->loadHTML($viwe);
        $dom = new \DOMXPath($dom);
        $action = route('single.comment',$post);

        $this->assertCount(
            1,
            $dom->query("//form[@method='post'][@action='$action']/textarea[@name='text']")
        );

    }


    public function testSingleViewRenderdWhenUserNotLoggedIn()
    {
       // $this->withoutExceptionHandling();
        $post = Post::factory()->create();
        $comments = [];

        $viwe = (string)$this->view(
            'single',
            compact('post','comments')
        );

        $dom = new \DOMDocument();
        $dom->loadHTML($viwe);
        $dom = new \DOMXPath($dom);
        $action = route('single.comment',$post);

        $this->assertCount(
            0,
            $dom->query("//form[@method='post'][@action='$action']/textarea[@name='text']")
        );

    }
}
