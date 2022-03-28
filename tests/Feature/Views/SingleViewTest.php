<?php

namespace Tests\Feature\Views;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;

class SingleViewTest extends TestCase
{
    use RefreshDatabase;

    public function testSingleViewRenderdWhenUserLoggedIn()
    {
        $post = Post::factory()->create();
        $comments = [];

        $viwe = (string)$this->actingAs(User::factory()->create())->view(
            'single',
            compact('post','comments')
        );

        $dom = new \DOMDocument();
        $dom->loadHTML($viwe);
        $dom = new \DOMXPath($dom);
        $route = route('single.comment',$post);
        dd($dom->query("//frm[@method='post'][@action=$route]/textarea[@name='text']"));

    }
}
