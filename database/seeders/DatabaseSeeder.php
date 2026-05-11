<?php

namespace Database\Seeders;

use App\Models\ServiceArea;
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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        ServiceArea::create([
        'name' => 'Versmold',
        'country' => 'Germany',
        'postal_code' => '33775',
        'city' => 'Versmold',
        'is_active' => true,
    ]);
    }
}
