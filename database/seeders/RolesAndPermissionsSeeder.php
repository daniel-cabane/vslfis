<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\App;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'cpe']);
        Role::create(['name' => 'aed']);

        $password = App::environment('local') ? '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm' : Hash::make(Str::password());
        // '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm' = 'secret'
        $rootUser = User::firstOrCreate([
            'name'=>'Daniel CABANE',
            'email'=>'dcabane@g.lfis.edu.hk',
            'email_verified_at' => now(),
            'password' => $password,
            'preferences' => ['notifications' => 'all', 'title' => 'M.']
        ]);
        $rootUser->assignRole('admin');
    }
}
