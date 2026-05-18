<?php

namespace Database\Factories;

use App\Enums\EmployeeEmploymentType;
use App\Enums\EmployeeStatus;
use App\Models\Country;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'employee_code' => 'EMP'.fake()->unique()->numberBetween(100000, 999999),
            'department_id' => Department::factory(),
            'country_id' => Country::factory(),
            'job_title' => fake()->randomElement([
                'Software Engineer',
                'HR Manager',
                'Finance Analyst',
                'Product Manager',
            ]),
            'salary' => fake()->numberBetween(50000, 200000),
            'employment_type' => fake()->randomElement(EmployeeEmploymentType::values()),
            'status' => EmployeeStatus::ACTIVE->value,
            'joining_date' => fake()->date(),
        ];
    }
}
