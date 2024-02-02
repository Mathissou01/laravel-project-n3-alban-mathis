<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
  public function run(): void
{
    $categoriesData = [
        ['name' => 'Category 1', 'color' => '#FF0000', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Category 2', 'color' => '#00FF00', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Category 3', 'color' => '#0000FF', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Category 4', 'color' => '#FFFF00', 'created_at' => now(), 'updated_at' => now()],
    ];

    foreach ($categoriesData as &$category) {
        // Génération du slug à partir du nom de la catégorie
        $category['slug'] = Str::slug($category['name']);
    }

    // Insertion des catégories dans la base de données
    DB::table('categories')->insert($categoriesData);
}
}