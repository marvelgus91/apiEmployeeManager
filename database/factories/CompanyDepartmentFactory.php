<?php

namespace Database\Factories;

use App\Models\CompanyArea;
use App\Models\CompanyDepartment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyDepartment>
 */
class CompanyDepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = CompanyDepartment::class;

    public function definition(): array
    {
        return [
            'company_area_id' => CompanyArea::factory(),
            'name' => $this->faker->word . 'Department',
        ];
    }
}
