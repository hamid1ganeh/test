<?php

namespace Tests\Feature\Views;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LayoutViewTest extends TestCase
{
    use RefreshDatabase;

    public function testLayoutViewRenderdWhenIsAdmin()
    {
        $user = User::factory()->state(['type'=>'admin'])->create();
        $this->actingAs($user);
         $view = $this->view('layouts.layout');
         $view->assertSee('<a href="/admin/dashboard">Admin Panel</a>',false);
    }

    public function testLayoutViewRenderdWhenIsNotAdmin()
    {
        $user = User::factory()->state(['type'=>'user'])->create();
        $this->actingAs($user);
         $view = $this->view('layouts.layout');
         $view->assertDontSee('<a href="/admin/dashboard">Admin Panel</a>',false);
    }
}
