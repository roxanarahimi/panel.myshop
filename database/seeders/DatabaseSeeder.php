<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'id' => '1',
            'name' => 'roxana',
            'mobile' => '09128222725',
            'role' => 'admin',
            'email' => 'ms.roxanarahimi@gmail.com',
            'password' => '$2y$12$VZEsnZGUQmDXPNTZf8DYc.gWCIwIucjNn0.u3h/.LhZIzFsxk3VLi'
        ]);
    }
}
