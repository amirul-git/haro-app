<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create permissions
        Permission::create(['name' => 'create schedules']);
        Permission::create(['name' => 'read schedules']);
        Permission::create(['name' => 'update schedules']);
        Permission::create(['name' => 'delete schedules']);

        // create role
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo('create schedules');
        $adminRole->givePermissionTo('read schedules');
        $adminRole->givePermissionTo('update schedules');
        $adminRole->givePermissionTo('delete schedules');

        $user = User::create([
            'name' => 'admin',
            'email' => 'admin',
            'password' => bcrypt('password')
        ]);

        $user->assignRole($adminRole);
    }
}
