<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('links')->insert([
            'name' => 'Zoom Utama',
            'link' => 'ini adalah link zoom utama'
        ]);

        DB::table('links')->insert([
            'name' => 'Zoom Cadangan',
            'link' => 'ini adalah link zoom cadangan'
        ]);
    }
}
