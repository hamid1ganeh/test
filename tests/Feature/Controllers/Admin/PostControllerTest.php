<?php

namespace Tests\Feature\Controllers\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Session;


class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $middlewares = ['web','admin'];

    public function testIndexMethod()
    {
        //$this->withoutExceptionHandling();
        Post::factory()->count(100)->create();

        $this
        ->actingAs(User::factory()->admin()->create())
        ->get(route('post.index'))
        ->assertOk()
        ->assertViewIs('admin.post.index')
        ->assertViewHas('posts',Post::latest()->paginate(15));

        $this->assertEquals(
            request()->route()->middleware(),
            $this->middlewares
        );
    }

    public function testCreateMethod()
    {
        $this->withoutExceptionHandling();
        $this
        ->actingAs(User::factory()->admin()->create())
        ->withoutExceptionHandling();
        Tag::factory()->count(20)->create();

        $this->get(route('post.create'))
        ->assertOk()
        ->assertViewIs('admin.post.create')
        ->assertViewHas('tags',Tag::latest()->get());

        $this->assertEquals(
            request()->route()->middleware(),
            $this->middlewares
        );
    }

    public function testEditMethod()
    {
        $this
        ->actingAs(User::factory()->admin()->create())
        ->withoutExceptionHandling();
        Tag::factory()->count(20)->create();
        $post = Post::factory()->create();

        $this->get(route('post.edit',$post))
        ->assertOk()
        ->assertViewIs('admin.post.edit')
        ->assertViewHasAll([
            'tags'=>Tag::latest()->get(),
            'post'=>$post
        ]);

        $this->assertEquals(
            request()->route()->middleware(),
            $this->middlewares
        );

    }

    public function testStoreMethod()
    {
        //$this->withoutExceptionHandling();
        $user = User::factory()->admin()->create();
        $tags = Tag::factory()->count(rand(1,5))->create();
        $data= Post::factory()
        ->state(['user_id'=>$user->id])
        ->make()
        ->toArray();

        $this
        ->actingAs($user)
        ->post(
            route('post.store'),
            array_merge(
                ['tags'=> $tags->pluck('id')->toArray()],
                $data
            )
       )->assertSessionHas('message','new post has been created')
        ->assertRedirect(route('post.index'));

        $this->assertDatabaseHas('posts',$data);

        $this->assertEquals(
            $tags->pluck('id')->toArray(),
            Post::where($data)->first()->tags()->pluck('id')->toArray()
        );

        $this->assertEquals(
            request()->route()->middleware(),
            $this->middlewares
        );
    }

    public function testUpdateMethod()
    {

        $user = User::factory()->admin()->create();

        $data= Post::factory()
        ->state(['user_id'=>$user->id])
        ->make()
        ->toArray();

        $post = Post::factory()
        ->state(['user_id'=>$user->id])
        ->hasTags(rand(1,5))
        ->create();

        $tags = Tag::factory()->count(rand(1,5))->create();

        $this
        ->actingAs($user)
        ->patch(
            route('post.update',$post),
            array_merge(
                ['tags'=> $tags->pluck('id')->toArray()],
                $data
            )
       )
        ->assertSessionHas('message','The post has been updated')
        ->assertRedirect(route('post.index'));

        $this->assertDatabaseHas('posts',array_merge(['id'=>$post->id],$data));
        $this->assertEquals(
            $tags->pluck('id')->toArray(),
            Post::where($data)->first()->tags()->pluck('id')->toArray()
        );

        $this->assertEquals(
            request()->route()->middleware(),
            $this->middlewares
        );

    }

    public function testValidationRequesData()
    {
        $user = User::factory()->admin()->create();
        $data = [];
        $errors = [
            'title' => 'The title field is required.',
            'description' => 'The description field is required.',
            'image' => 'The image field is required.',
            'tags' => 'The tags field is required.'
        ];

        //validate for store method
        $this
        ->actingAs($user)
        ->post(route('post.store'),$data)
        ->assertSessionHasErrors($errors);

        //validate for update method
        $this
        ->actingAs($user)
        ->patch(route('post.update',Post::factory()->create()),$data)
        ->assertSessionHasErrors($errors);
    }

    public function testValidationRequestDescriptionasMinumumRule()
    {
        $user = User::factory()->admin()->create();
        $data = ['description'=>'lord'];
        $errors = [
            'description' => 'The description must be at least 5 characters.',
        ];

        //validate for store method
        $this
        ->actingAs($user)
        ->post(route('post.store'),$data)
        ->assertSessionHasErrors($errors);

        //validate for update method
        $this
        ->actingAs($user)
        ->patch(route('post.update',Post::factory()->create()),$data)
        ->assertSessionHasErrors($errors);
    }

    public function testValidationRequestImageHasUrlRule()
    {
        $user = User::factory()->admin()->create();
        $data = ['image'=>'lord'];
        $errors = [
            'image' => 'The image must be a valid URL.',
        ];

        //validate for store method
        $this
        ->actingAs($user)
        ->post(route('post.store'),$data)
        ->assertSessionHasErrors($errors);

        //validate for update method
        $this
        ->actingAs($user)
        ->patch(route('post.update',Post::factory()->create()),$data)
        ->assertSessionHasErrors($errors);
    }

    public function testValidationRequestTagsHasArrayRule()
    {
        $user = User::factory()->admin()->create();
        $data = ['tags'=>'lord'];
        $errors = [
            'tags' => 'The tags must be an array.',
        ];

        //validate for store method
        $this
        ->actingAs($user)
        ->post(route('post.store'),$data)
        ->assertSessionHasErrors($errors);

        //validate for update method
        $this
        ->actingAs($user)
        ->patch(route('post.update',Post::factory()->create()),$data)
        ->assertSessionHasErrors($errors);
    }

    public function testValidationRequestTagsExistInTagsTable()
    {
        //$this->withoutExceptionHandling();
        $user = User::factory()->admin()->create();
        $data = ['tags'=>[0]];
        $errors = [
            'tags.0' => 'The selected tags.0 is invalid.',
        ];

        //validate for store method
        $this
        ->actingAs($user)
        ->post(route('post.store'),$data)
        ->assertSessionHasErrors($errors);

        //validate for update method
        $this
        ->actingAs($user)
        ->patch(route('post.update',Post::factory()->create()),$data)
        ->assertSessionHasErrors($errors);
    }

    public function testDestroyMethod()
    {
        $post = Post::factory()
        ->hasTags(5)
        ->hasComments(1)
        ->create();

        $comment = $post->comments()->first();

        $this
        ->actingAs(User::Factory()->admin()->create())
        ->delete(route('post.destroy',$post))
        ->assertSessionHasAll(['message'=>'The post has been deleted.'])
        ->assertRedirect(route('post.index'));


        $this
        ->assertDeleted($post)
        ->assertDeleted($comment)
        ->assertEmpty($post->tags);

        $this->assertEquals($this->middlewares,request()->route()->middleware());


    }

}
