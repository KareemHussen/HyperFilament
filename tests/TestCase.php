<?php

namespace Tests;

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        Role::create(['name' => 'Super Admin']);
        
        $user = User::create([
            'name' => 'Testing As a Super Admin',
            'email' => 'testingSuperAdmin@hyperfilament.com',
            'password' => '12345678',
        ]);

        $user->assignRole('Super Admin');

        $this->actingAs($user);
    }

}

