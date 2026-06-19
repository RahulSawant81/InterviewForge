<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tag::insert([
            ['name' => 'OOP', 'slug' => 'oop'],
            ['name' => 'API', 'slug' => 'api'],
            ['name' => 'Authentication', 'slug' => 'authentication'],
            ['name' => 'Eloquent', 'slug' => 'eloquent'],
            ['name' => 'Database', 'slug' => 'database'],
            ['name' => 'Performance', 'slug' => 'performance'],
        ]);
    }
}
