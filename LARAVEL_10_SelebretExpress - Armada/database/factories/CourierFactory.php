<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Courier>
 */
class CourierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'user_id' => $this->faker->numberBetween(1, 50), // Simulasi ID dari Layanan Pengguna
        'vehicle_type' => $this->faker->randomElement(['motor', 'mobil', 'sepeda']),
        'vehicle_plate' => strtoupper($this->faker->bothify('? #### ??')),
        'status' => $this->faker->randomElement(['available', 'busy', 'offline']),
        'current_location' => $this->faker->address(),
        ];
    }
}
