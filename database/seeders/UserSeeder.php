<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Role::firstOrCreate(['name' => 'Super Admin']);
        Role::firstOrCreate(['name' => 'Company']);

        User::firstOrCreate(['email' => 'kareemhussen500@gmail.com'],[
            'name' => 'Kareem Hussen',
            'email' => 'kareemhussen500@gmail.com',
            'password' => '12345678',
        ])->assignRole('Super Admin');

        User::firstOrCreate(['email' => 'FirstCompany@gmail.com'],[
            'name' => 'First Company',
            'email' => 'firstcompany@gmail.com',
            'password' => '12345678',
        ])->assignRole('Company');
    
    }
}
