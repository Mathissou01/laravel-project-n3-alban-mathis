<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         User::factory()->create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
        ]);
        // Appel du seeder pour la table 'categories'
        $this->call(CategorySeeder::class);

        // Appel du seeder pour la table 'tasks'
        $this->call(TasksSeeder::class);
      
    }
}
