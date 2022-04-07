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
use App\Helpers\DurationalOfReading;
use Mockery;
use Mockery\MockInterface;

use function PHPUnit\Framework\assertEquals;

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

    public function testGetReadingDurationAttribute()
    {
        $post = Post::factory()->make();
        $dor = new DurationalOfReading();
        $dor->setText($post->description);
        $this->assertEquals($post->readingDuration,$dor->getTimePerMinute());
    }

    public function testGetReadingDurationAttributeWithMocking()
    {
        $this->withoutExceptionHandling();
        $post = Post::factory()->make();
        $mock = $this->mock(DurationalOfReading::class,function(MockInterface $mock) use($post){
            $mock
            ->shouldReceive('setText')
            ->with($post->description)
            ->once()
            ->andReturn($mock);

           $mock
           ->shouldReceive('getTimePerMinute')
           ->once()
           ->andReturn(20);

        });

        $this->assertEquals(20,$post->readingDuration);
    }
}
