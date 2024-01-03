<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('days')->insert([
            'name' => 'sunday',
        ]);

        DB::table('days')->insert([
            'name' => 'monday',
        ]);

        DB::table('days')->insert([
            'name' => 'tuesday',
        ]);

        DB::table('days')->insert([
            'name' => 'wednesday',
        ]);

        DB::table('days')->insert([
            'name' => 'thursday',
        ]);

        DB::table('days')->insert([
            'name' => 'friday',
        ]);

        DB::table('days')->insert([
            'name' => 'saturday',
        ]);
    }
}
