<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Post;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'=>User::factory(),
            'title'=>$this->faker->title(),
            'text'=>$this->faker->text(),
            'commentable_id'=> Post::factory(),
            'commentable_type'=> Post::class,
        ];
    }
}
