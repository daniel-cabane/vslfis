<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

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

        $rootUser = User::firstOrCreate([
            'name'=>'Daniel CABANE',
            'email'=>'dcabane@g.lfis.edu.hk',
            'email_verified_at' => now(),
            'password' => Hash::make(Str::password()),
            'preferences' => ['notifications' => 'all', 'title' => 'M.']
        ]);
        $rootUser->assignRole('admin');
    }
}
