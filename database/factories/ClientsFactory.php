<?php
// database/factories/ClientsFactory.php

namespace Database\Factories;

use App\Models\Clients;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Clients>
 */
class ClientsFactory extends Factory
{
    protected $model = Clients::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'phone' => $this->faker->numerify('9#########'), // Generate an Indian phone number
            'priority' => 'Normal', // Default priority is 'Normal'
        ];
    }
}
