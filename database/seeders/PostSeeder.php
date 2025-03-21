<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 200; $i++) {
            DB::table('posts')->insert([
                'title' => fake()->words(3, true),
                'content' => fake()->paragraphs(3, true),
                'image' => fake()->imageUrl(),
                'excerpt' => fake()->sentence(5),
                'author' => fake()->name(),
            ]);
        }
    }
}
