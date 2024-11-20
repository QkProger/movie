<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('movies')->insert([
            ['title' => 'Фильм 1', 'is_published' => 0, 'poster_url' => 'posters/default.jpg'],
            ['title' => 'Фильм 2', 'is_published' => 0, 'poster_url' => 'posters/default.jpg'],
        ]);
    }
}
