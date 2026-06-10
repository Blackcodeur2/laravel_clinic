<?php

namespace Database\Factories;

use App\Models\ServiceMedical;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ServiceMedical>
 */
class ServiceMedicalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $services = [
            'Consultation générale', 'Consultation spécialisée', 'Échographie abdominale',
            'Électrocardiogramme', 'Radiographie thoracique', 'Prise de sang',
            'Analyse urinaire', 'Injection', 'Pansement simple', 'Pansement complexe',
        ];

        return [
            'code' => strtoupper(fake()->unique()->bothify('SRV-####')),
            'nom' => fake()->unique()->randomElement($services),
            'prix' => fake()->randomFloat(2, 2000, 50000),
            'description' => fake()->optional()->sentence(),
        ];
    }
}
