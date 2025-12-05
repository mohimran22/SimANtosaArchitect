<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         foreach (Project::all() as $project) {

        $levels = [
            ['level_order' => 1, 'level_name' => 'Konsultasi'],
            ['level_order' => 2, 'level_name' => 'Survei'],
            ['level_order' => 3, 'level_name' => 'Desain'],
            ['level_order' => 4, 'level_name' => 'RAB'],
            ['level_order' => 5, 'level_name' => 'SPK'],
        ];

        foreach ($levels as $level) {
            $project->levels()->firstOrCreate($level);
        }
    }
    }
}
