<?php

namespace Database\Seeders;

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
                'role' => 'admin'
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin2@email.com'],
            [
                'name' => 'admin2',
                'password' => Hash::make('123123123'),
                'role' => 'admin'
            ]
        );

        User::factory(10)->create();

        $this->call([
            PostSeeder::class,
        ]);
    }
}
