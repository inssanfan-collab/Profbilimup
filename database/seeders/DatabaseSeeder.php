<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Администратор',
            'email' => env('ADMIN_EMAIL', 'admin@bilimup.local'),
            'password' => env('ADMIN_PASSWORD', 'password'),
            'email_verified_at' => now(),
        ]);
    }
}
