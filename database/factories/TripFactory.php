<?php

namespace Database\Factories;

use App\Enums\TripStatus;
use App\Models\Area;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $area_ids = Area::inRandomOrder()->limit(2)->pluck('id')->toArray();
        $company = Company::inRandomOrder()->first();
        $vehicle = $company->vehicles()->inRandomOrder()->first();
        $driver = $company->drivers()->inRandomOrder()->first();

        $start_date = fake()->dateTimeBetween('-2 year', 'now');

        return [
            "vehicle_id" => $vehicle->id,
            "driver_id" => $driver->id,
            "company_id" => $company->id,
            "from_area" => $area_ids[0],
            "to_area" => $area_ids[1],
            "start_date" => $start_date,
            "end_date" => fake()->dateTimeBetween($start_date, 'now'),
            "status" => fake()->randomElement(TripStatus::cases()),
            "created_at" => fake()->dateTimeBetween($start_date, 'now'),
        ];
    }
}
