<?php

namespace Database\Factories;

use App\Models\Medicament;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Medicament>
 */
class MedicamentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $medicaments = [
            'Paracétamol 500mg', 'Ibuprofène 400mg', 'Amoxicilline 500mg',
            'Metformine 850mg', 'Amlodipine 5mg', 'Oméprazole 20mg',
            'Doliprane 1000mg', 'Aspégic 100mg', 'Levothyrox 50µg',
            'Bisoprolol 5mg', 'Ramipril 5mg', 'Simvastatine 20mg',
        ];

        return [
            'nom' => fake()->unique()->randomElement($medicaments),
            'prix' => fake()->randomFloat(2, 500, 25000),
            'stock' => fake()->numberBetween(10, 500),
            'description' => fake()->optional()->sentence(),
        ];
    }
}
