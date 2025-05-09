<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => $this->faker->sentence(),
            'project_id' => Project::factory(), // Garante que o projeto existe
            'start_date' => now()->addDays(rand(0, 5)),
            'end_date' => fn(array $attributes) =>
            $attributes['start_date'] ? \Carbon\Carbon::parse($attributes['start_date'])->copy()->addDays(rand(1, 10)) : null,
            'predecessor_id' => null, 
            'status' => $this->faker->randomElement(['Pendente', 'Concluida', 'Em Andamento']),
        ];
    }
}
