<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    // use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = 'password';

        if (app()->isProduction()) {
            // Never create a guessable admin on the live server
            $password = $this->command->secret('Admin password (min 12 chars)');
            if (!$password || strlen($password) < 12) {
                $this->command->error('Password too short. Seeding aborted.');
                return;
            }
        }

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@saja.com',
            'password' => Hash::make($password),
            'is_admin' => true,
        ]);

        // Demo data only for local development
        if (app()->isProduction()) {
            return;
        }

        Category::factory(5)->create()->each(function ($category) {
            Product::factory(10)->create([
                'category_id' => $category->id,
            ]);
        });
    }
}
