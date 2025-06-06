<?php

namespace Database\Seeders;

use App\Models\company;
use App\Models\CompanyArea;
use App\Models\CompanyDepartment;
use App\Models\Employee;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Company::factory()
        ->count(5)
        ->has(
            CompanyArea::factory()
                ->count(3)
                ->has(
                    CompanyDepartment::factory()
                        ->count(2)
                        ->has(
                            Employee::factory()->count(5)
                        )
                )
        )
        ->create();
    }
}
