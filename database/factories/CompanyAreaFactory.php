<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;
use App\Models\CompanyArea;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyArea>
 */
class CompanyAreaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = CompanyArea::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'name' => $this->faker->word . 'Area',
        ];
    }
}
