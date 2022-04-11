<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\Tag;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $middlewares = ['web', 'admin'];

    public function testIndexMethod()
    {
       // $this->withoutExceptionHandling();
        Tag::factory()->count(100)->create();

        $this
            ->actingAs(User::factory()->admin()->create())
            ->get(route('tag.index'))
            ->assertOk()
            ->assertViewIs('admin.tag.index')
            ->assertViewHas('tags', Tag::latest()->paginate(15));

        $this->assertEquals(request()->route()->middleware(),$this->middlewares);
    }

    public function testCreatMethod()
    {
         // $this->withoutExceptionHandling();
        $this
            ->actingAs(User::factory()->admin()->create())
            ->get(route('tag.create'))
            ->assertOk()
            ->assertViewIs('admin.tag.create');

        $this->assertEquals(request()->route()->middleware(),$this->middlewares);
    }

    public function testEditMethod()
    {
         // $this->withoutExceptionHandling();
        $tag = Tag::factory()->create();

        $this
            ->actingAs(User::factory()->admin()->create())
            ->get(route('tag.edit', $tag))
            ->assertOk()
            ->assertViewIs('admin.tag.edit')
            ->assertViewHasAll(['tag' => $tag]);

        $this->assertEquals(request()->route()->middleware(),$this->middlewares);
    }

    public function testStoreMethod()
    {
        //$this->withoutExceptionHandling();
        $data = Tag::factory()->make()->toArray();

        $this
            ->actingAs(User::factory()->admin()->create())
            ->post(route('tag.store'), $data)
            ->assertSessionHas('message', 'new tag has been created')
            ->assertRedirect(route('tag.index'));

        $this->assertDatabaseHas('tags', $data);
        $this->assertEquals(request()->route()->middleware(),$this->middlewares);
    }

    public function testUpdateMethod()
    {
        //$this->withoutExceptionHandling();
        $data = Tag::factory()->make()->toArray();
        $tag = Tag::factory()->create();

        $this
            ->actingAs(User::factory()->admin()->create())
            ->patch(route('tag.update', $tag), $data)
            ->assertSessionHas('message', 'the tag has been updated')
            ->assertRedirect(route('tag.index'));

        $this->assertDatabaseHas('tags', array_merge(['id' => $tag->id], $data));
        $this->assertEquals(request()->route()->middleware(),$this->middlewares);
    }

    public function testValidationRequestRequiredData()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->admin()->create();
        $errors = ['name' => 'The name field is required.'];
        $data = [];

        // store method
        $this
            ->actingAs($user)
            ->post(route('tag.store'), $data)
            ->assertSessionHasErrors($errors);

        // update method
        $this
            ->actingAs($user)
            ->patch(route('tag.update', Tag::factory()->create()->id), $data)
            ->assertSessionHasErrors($errors);
    }

    public function testDestroyMethod()
    {
        // $this->withoutExceptionHandling();
        $tag = Tag::factory()
            ->hasPosts(5)
            ->create();

        $this
            ->actingAs(User::factory()->admin()->create())
            ->delete(route('tag.destroy', $tag->id))
            ->assertSessionHasAll(['message' => 'the tag has been deleted'])
            ->assertRedirect(route('tag.index'));

        $this
            ->assertDeleted($tag)
            ->assertEmpty($tag->posts);

        $this->assertEquals($this->middlewares, request()->route()->middleware());
    }
}
