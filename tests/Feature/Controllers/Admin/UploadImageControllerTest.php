<?php

namespace Tests\Feature\Controller\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UploadImageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUploadMethodCanUploadImage()
    {
        $this->withoutExceptionHandling();

        $image = UploadedFile::fake()->image('image.png');

        $this
        ->actingAs(User::factory()->admin()->create())
        ->withHeaders([
            'HTTP_X-Requested-with'=> 'XMLHttpRequest'
        ])
        ->postJson(route('upload'),compact('image'))
        ->assertOk()
        ->assertJson(['url'=>"/upload/{$image->hashName()}"]);

        $this->assertFileExists(public_path("/upload/{$image->hashName()}"));

        $this->assertEquals(
            request()->route()->middleware(),
            ['web','admin']
        );
    }

    public function testUploadMethodValidationRequestImageDataHasImageRule()
    {
       // $this->withoutExceptionHandling();

        $image = UploadedFile::fake()->create('image.txt');

        $this
        ->actingAs(User::factory()->admin()->create())
        ->withHeaders([
            'HTTP_X-Requested-with'=> 'XMLHttpRequest'
        ])
        ->postJson(route('upload'),compact('image'))
        ->assertJsonValidationErrors([
            'image'=> 'The image must be an image.'
        ]);

        $this->assertFileDoesNotExist(public_path("/upload/{$image->hashName()}"));

    }

    public function testUploadMethodValidationRequestImageDataHasMaxSizeRule()
    {
       // $this->withoutExceptionHandling();

        $image = UploadedFile::fake()->create('image.png',251);

        $this
        ->actingAs(User::factory()->admin()->create())
        ->withHeaders([
            'HTTP_X-Requested-with'=> 'XMLHttpRequest'
        ])
        ->postJson(route('upload'),compact('image'))
        ->assertJsonValidationErrors([
            'image'=> 'The image must not be greater than 250 kilobytes.'
        ]);

        $this->assertFileDoesNotExist(public_path("/upload/{$image->hashName()}"));

    }

    public function testUploadMethodValidationRequestImageDataHasMaxDimensionsRule()
    {
       // $this->withoutExceptionHandling();

        $image = UploadedFile::fake()->image('image.png',101,201)->size(50);

        $this
        ->actingAs(User::factory()->admin()->create())
        ->withHeaders([
            'HTTP_X-Requested-with'=> 'XMLHttpRequest'
        ])
        ->postJson(route('upload'),compact('image'))
        ->assertJsonValidationErrors([
            'image'=> 'The image has invalid image dimensions.'
        ]);

        $this->assertFileDoesNotExist(public_path("/upload/{$image->hashName()}"));

    }
}
