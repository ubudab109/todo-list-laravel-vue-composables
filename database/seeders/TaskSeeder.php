<?php

namespace Database\Seeders;

use App\Constants\TaskLevel;
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
        $user = User::first();
        if ($user) {
            for ($x = 0; $x < 200; $x++) {
                if ($x % 2 == 0) {
                    $priority = TaskLevel::LOW()->getValue();
                } else if ($x % 3 == 0) {
                    $priority = TaskLevel::NORMAL()->getValue();
                } else if ($x % 4 == 0) {
                    $priority = TaskLevel::HIGH()->getValue();
                } else {
                    $priority = TaskLevel::URGENT()->getValue();
                }
                Task::create([
                    'user_id'       => $user->id,
                    'title'         => fake()->title(),
                    'description'   => fake()->title(),
                    'priority'      => (string)$priority,
                    'due_date'      => fake()->date(),
                ]);
            }
        }
    }
}
