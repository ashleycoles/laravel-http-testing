<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 20; $i++) {
            DB::table('products')->insert([
                'name' => fake()->words(2, true),
                'description' => fake()->paragraphs(4, true),
                'price' => fake()->randomFloat(2),
                'stock' => rand(0, 100),
            ]);
        }
    }
}
