<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BoardGroupSeeder::class,
            BoardSeeder::class,
        ]);

        User::updateOrCreate(
            ['email' => 'admin@email.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('123123123'),
            ]
        );
        
        User::factory(10)->create();
        Post::factory(50)->create();
    }
}
