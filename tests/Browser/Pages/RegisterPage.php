<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;


class RegisterPage extends Page
{

    public function url()
    {
        return '/register';
    }

    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    public function submitForm(Browser $browser,array $data=[])
    {
        $browser
            ->type('name', $data['name'] ?? '')
            ->type('email',$data['email'] ?? '')
            ->type('password', $data['password'] ?? '')
            ->typeSlowly('password_confirmation',$data['password'] ?? '')
            ->click('@submitButton');
    }


    public function elements()
    {
        return [
            '@submitButton' => 'form button[type="submit"]',
        ];
    }
}
