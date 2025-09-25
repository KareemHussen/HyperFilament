<?php

namespace Database\Factories;

use App\Enums\IndustryEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => fake()->company(),
            "email" => fake()->companyEmail(),
            "phone" => fake()->randomElement(["010", "011", "012" , "015"]) . fake()->randomNumber(8 , true),
            "address" => fake()->address(),
            "website" => fake()->url(),
            "industry" => fake()->randomElement(IndustryEnum::cases()),
        ];
    }
}
