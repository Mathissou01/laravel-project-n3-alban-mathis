<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TasksSeeder extends Seeder
{
    public function run(): void
    {
        // Définir la localisation de Carbon en français
        setlocale(LC_TIME, 'fr_FR.utf8');

        $tasksData = [];

        for ($i = 1; $i <= 10; $i++) {
            $tasksData[] = [
                'name' => 'Tache ' . $i,
                'date' => Carbon::now()->format('Y-m-d'),
                'heure' => Carbon::now()->format('H:i:s'),
                'category_id' => rand(1, 4),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insertion des tâches dans la base de données
        DB::table('tasks')->insert($tasksData);
    }
}