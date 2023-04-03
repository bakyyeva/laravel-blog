<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => random_int(1,10),
            'article_id' => random_int(23,40),
            'parent_id' => random_int(3,22),
            'comments' => fake()->text,
            'status' => fake()->boolean,
            'like_count' => random_int(1,100),
            'unlike_count' => random_int(1,100)
        ];
    }
}
