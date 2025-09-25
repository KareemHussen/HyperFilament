<?php

namespace Database\Seeders;

use App\Models\City;
use File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            "Cairo" => ["Nasr City", "Heliopolis", "Maadi", "Zamalek"],
            "Alfayoum" => ["Ibshway", "Sinnuris", "Etsa"],
            "Giza" => ["Dokki", "Mohandessin", "October City"],
            "Alexandria" => ["Sidi Gaber", "Smouha", "Gleem", "Stanley"],
            "Asyut" => ["Dairut", "Manfalut", "Abnub"],
            "Qalyubia" => ["Banha", "Shubra El Kheima", "Khosous"],
            "Sohag" => ["Tahta", "Girga", "Akhmim"],
            "Minya" => ["Mallawi", "Beni Mazar", "Maghagha"],
            "Aswan" => ["Kom Ombo", "Edfu", "Daraw"],
            "Luxor" => ["Karnak", "Armant", "Esna"],
            "Port Said" => ["El Manakh", "El Arab", "Port Fouad"],
            "Damietta" => ["Faraskur", "Kafr Saad", "Zarqa"],
            "Beni Suef" => ["Nasser", "Beba", "Al Fashn"],
        ];

        foreach ($cities as $cityName => $areas) {
            $city = City::create(['name' => $cityName]);

            foreach ($areas as $areaName) {
                $city->areas()->create(['name' => $areaName]);
            }
        }
    }

}
