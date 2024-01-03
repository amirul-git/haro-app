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

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo('create schedules');
        $adminRole->givePermissionTo('read schedules');
        $adminRole->givePermissionTo('update schedules');
        $adminRole->givePermissionTo('delete schedules');

        $studentRole = Role::create(['name' => 'student']);
        $lecturerRole = Role::create(['name' => 'lecturer']);

        // create a lecturer seeder
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'password' => bcrypt('password')
        ]);

        $admin->assignRole($adminRole);

        // create a lecturer seeder
        $lecturer = User::create([
            'name' => 'amir',
            'email' => 'amir@mail.com',
            'password' => bcrypt('password')
        ]);

        $lecturer->assignRole($lecturerRole);

        $lecturer = User::create([
            'name' => 'adip',
            'email' => 'adip@mail.com',
            'password' => bcrypt('password')
        ]);

        $lecturer->assignRole($lecturerRole);

        // create a student seeder
        $student = User::create([
            'name' => 'neo',
            'email' => 'neo@mail.com',
            'password' => bcrypt('password')
        ]);

        $student->assignRole($studentRole);

        $student = User::create([
            'name' => 'budi',
            'email' => 'budi@mail.com',
            'password' => bcrypt('password')
        ]);

        $student->assignRole($studentRole);
    }
}
