<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::create([
            'title' => 'task 1',
            'description' => 'description of task 1',
            'assignee_id' => User::where('role','user')->inRandomOrder()->first()->id,
            'created_by_id' => User::where('role','manager')->inRandomOrder()->first()->id,
            'due_date' => now(),
        ]);
        Task::create([
            'title' => 'task 2',
            'description' => 'description of task 2',
            'assignee_id' => User::where('role','user')->inRandomOrder()->first()->id,
            'created_by_id' => User::where('role','manager')->inRandomOrder()->first()->id,
            'due_date' => now(),
        ]);
        Task::create([
            'title' => 'task 3',
            'description' => 'description of task 3',
            'assignee_id' => User::where('role','user')->inRandomOrder()->first()->id,
            'created_by_id' => User::where('role','manager')->inRandomOrder()->first()->id,
            'due_date' => now(),
        ]);
    }
}
