<?php

namespace Database\Factories;

use App\Models\Board;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $title = fake()->sentence();
        $board = Board::query()
            ->where('slug', '!=', 'notice')
            ->inRandomOrder()
            ->first();
        $user = User::query()->inRandomOrder()->first();

        return [
            'board_id' => $board?->id,
            'user_id' => $user?->id,
            'title' => $title,
            'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(1000, 9999),
            'content' => fake()->realText(1200),
            'excerpt' => fake()->text(120),
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
