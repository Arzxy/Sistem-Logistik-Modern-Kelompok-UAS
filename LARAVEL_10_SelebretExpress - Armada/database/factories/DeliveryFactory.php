<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Delivery>
 */
class DeliveryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   public function definition(): array
    {
        $status = $this->faker->randomElement(['pending', 'assigned', 'picked_up', 'delivered']);
        
        return [
            'package_id' => $this->faker->numberBetween(100, 999),
            'courier_id' => \App\Models\Courier::factory(),
            'status' => $status,
            'pickup_address' => $this->faker->address(),
            'delivery_address' => $this->faker->address(),
            
            // Logika Pintar:
            'assigned_at' => ($status !== 'pending') ? now()->subHours(2) : null,
            'picked_at'   => (in_array($status, ['picked_up', 'delivered'])) ? now()->subHour() : null,
            'delivered_at'=> ($status === 'delivered') ? now() : null,
        ];
    }
}
