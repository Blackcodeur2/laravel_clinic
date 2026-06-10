<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'date_naissance' => fake()->dateTimeBetween('-80 years', '-5 years')->format('Y-m-d'),
            'sexe' => fake()->randomElement(['M', 'F']),
            'telephone' => fake()->numerify('06########'),
            'adresse' => fake()->address(),
        ];
    }
}
