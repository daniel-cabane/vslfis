<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class testUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cpe = User::firstOrCreate([
            'name'=>'CPE',
            'email'=>'cpe@email.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'preferences' => ['notifications' => 'all', 'title' => 'M.']
        ]);
        $cpe->assignRole('cpe');

        for($i=1 ; $i < 5 ; $i++){
            User::firstOrCreate([
                'name'=>"VS$i",
                'email'=>"vs$i@email.com",
                'email_verified_at' => now(),
                'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
                'preferences' => ['notifications' => 'all', 'title' => 'M.']
            ]);
        }
    }
}
