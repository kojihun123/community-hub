<?php

namespace Database\Factories;

use App\Models\Board;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $board = Board::query()
            ->where('slug', '!=', 'notice')
            ->inRandomOrder()
            ->first();
        $user = User::query()->inRandomOrder()->first();

        return [
            'board_id' => $board?->id,
            'user_id' => $user?->id,
            'title' => fake()->sentence(),
            'content' => fake()->realText(1200),
            'author_name_snapshot' => $user?->name ?? fake()->userName(),
            'status' => 'published',
            'is_notice' => false,
            'is_pinned' => false,
            'view_count' => fake()->numberBetween(0, 5000),
            'like_count' => fake()->numberBetween(0, 300),
            'comment_count' => fake()->numberBetween(0, 100),
        ];
    }
}
