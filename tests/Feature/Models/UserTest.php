<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;

class UserTest extends TestCase
{
    use RefreshDatabase,ModelHelperTesting;

    protected function model(): Model
    {
        return new User();
    }


    public function testUserRelationShipWithPost()
    {
        $count = rand(1,10);

        $user = User::factory()->hasPosts($count)->create();

        $this->assertCount($count,$user->posts);
        $this->assertTrue($user->posts->first() instanceof Post);
    }

    public function testUserRelationShipWithComment()
    {
        $count = rand(1,10);

        $user = User::factory()->hasComments($count)->create();

        $this->assertCount($count,$user->comments);
        $this->assertTrue($user->comments->first() instanceof Comment);
    }
}
