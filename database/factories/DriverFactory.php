<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
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
            "name" => fake()->name(),
            "email" => fake()->email(),
            "phone" => fake()->randomElement(["010", "011", "012" , "015"]) . fake()->randomNumber(8 , true),
            "license_number" => fake()->randomNumber(8 , true),
            "company_id" => fake()->randomElement($companies),
        ];
    }
}
