<?php

namespace Database\Factories;

use App\Models\CompanyDepartment;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'company_department_id' => CompanyDepartment::factory(),
            'fullName' => $this->faker->name,
            'position' => $this->faker->word,
            'startDate' => $this->faker->dateTime(),
            'photo' => null,
            'status' => $this->faker->boolean,
        ];
    }
}
