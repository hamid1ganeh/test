<?php

namespace Tests\Feature\Models;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Tag;


class PostTest extends TestCase
{
    use RefreshDatabase,ModelHelperTesting;

    protected function model(): Model
    {
        return new Post();
    }

    public function testPostRelationShipWithUser()
    {
        $post = Post::factory()->for(User::factory())->create();

        $this->assertTrue(isset($post->user->id));
        $this->assertTrue($post->user instanceof User);
    }

    public function testPostRelationShipWithTag()
    {
        $count = rand(1,10);

        $post = Post::factory()->hasTags($count)->create();

        $this->assertCount($count,$post->tags);
        $this->assertTrue($post->tags->first() instanceof Tag);
    }

    public function testPostRelationShipWithComment()
    {
        $count = rand(1,10);

        $post = Post::factory()->hasComments($count)->create();

        $this->assertCount($count,$post->comments);
        $this->assertTrue($post->comments->first() instanceof Comment);
    }
}
