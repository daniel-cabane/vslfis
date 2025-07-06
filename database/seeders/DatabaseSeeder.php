<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Keep in production
        $this->call(RolesAndPermissionsSeeder::class);

        // Dev seeding only
        if (App::environment('local')) {
            $this->call(testUserSeeder::class);
        }
    }
}
