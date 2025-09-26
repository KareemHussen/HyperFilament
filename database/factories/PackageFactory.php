<?php

namespace Database\Factories;

use App\Enums\PackageTypeEnum;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'weight' => $this->faker->randomFloat(2, 0, 100),
            'length' => $this->faker->randomFloat(2, 0, 100),
            'width' => $this->faker->randomFloat(2, 0, 100),
            'height' => $this->faker->randomFloat(2, 0, 100),
            'quantity' => $this->faker->numberBetween(1, 10),
            'type' => $this->faker->randomElement(PackageTypeEnum::cases()),
        ];
    }

    public function forTrip(?Trip $trip = null): self
    {
        return $this->state(function () use ($trip) {
            return [
                'trip_id' => $trip?->id ?? Trip::factory(),
            ];
        });
    }
}
