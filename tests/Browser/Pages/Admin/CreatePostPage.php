<?php

namespace Tests\Browser\Pages\Admin;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class CreatePostPage extends Page
{

    public function url()
    {
        return '/admin/post/create';
    }

    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url())
        ->assertInputPresent('title')
        ->assertInputPresent('description')
        ->assertInputPresent('tags')
        ->assertInputPresent('image')
        ->assertAttribute('select[name="tags"]','multiple','true')
        ->assertPresent('@postImageInput');
    }


    public function elements()
    {
        return [
            '@postImageInput' => 'input[type="file"]#postImageInput',
        ];
    }
}
