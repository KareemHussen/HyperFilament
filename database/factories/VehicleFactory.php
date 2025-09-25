<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $companies = Company::all('id')->pluck('id')->toArray();
        
        return [
            "name" => fake()->company(),
            "plate_number" => strtoupper(fake()->bothify('???-###')),
            "weight" => fake()->numberBetween(1000, 5000),
            "company_id" => fake()->randomElement($companies),
        ];
    }
}
