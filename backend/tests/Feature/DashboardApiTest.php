<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Country;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_summary_uses_deterministic_salary_calculations(): void
    {
        $india = Country::factory()->create(['name' => 'India', 'code' => 'IN']);
        $department = Department::factory()->create(['name' => 'Engineering']);

        $this->createEmployee($india, $department, 'Software Engineer', 100000, 'active', 'full_time');
        $this->createEmployee($india, $department, 'Software Engineer', 200000, 'active', 'contract');
        $this->createEmployee($india, $department, 'HR Manager', 300000, 'inactive', 'part_time');

        $response = $this->getJson('/api/dashboard/summary')
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.total_employees', 3)
            ->assertJsonPath('data.active_employees', 2)
            ->assertJsonPath('data.inactive_employees', 1)
            ->assertJsonPath('data.average_salary', 200000);

        $this->assertEquals(100000, $response->json('data.minimum_salary'));
        $this->assertEquals(300000, $response->json('data.maximum_salary'));
    }

    public function test_country_salary_insights_return_min_max_average_by_country(): void
    {
        $india = Country::factory()->create(['name' => 'India', 'code' => 'IN']);
        $usa = Country::factory()->create(['name' => 'United States', 'code' => 'US']);
        $department = Department::factory()->create(['name' => 'Engineering']);

        $this->createEmployee($india, $department, 'Software Engineer', 100000);
        $this->createEmployee($india, $department, 'Software Engineer', 300000);
        $this->createEmployee($usa, $department, 'Software Engineer', 50000);

        $response = $this->getJson('/api/dashboard/country-salary-insights')
            ->assertOk()
            ->assertJsonPath('status', true);

        $indiaInsight = collect($response->json('data'))->firstWhere('country', 'India');
        $usaInsight = collect($response->json('data'))->firstWhere('country', 'United States');

        $this->assertSame(2, $indiaInsight['total_employees']);
        $this->assertEquals(100000, $indiaInsight['minimum_salary']);
        $this->assertEquals(300000, $indiaInsight['maximum_salary']);
        $this->assertEquals(200000, $indiaInsight['average_salary']);

        $this->assertSame(1, $usaInsight['total_employees']);
        $this->assertEquals(50000, $usaInsight['average_salary']);
    }

    public function test_job_title_insights_can_be_filtered_by_country(): void
    {
        $india = Country::factory()->create(['name' => 'India', 'code' => 'IN']);
        $usa = Country::factory()->create(['name' => 'United States', 'code' => 'US']);
        $department = Department::factory()->create(['name' => 'Engineering']);

        $this->createEmployee($india, $department, 'Software Engineer', 100000);
        $this->createEmployee($india, $department, 'Software Engineer', 200000);
        $this->createEmployee($india, $department, 'HR Manager', 50000);
        $this->createEmployee($usa, $department, 'Software Engineer', 300000);

        $response = $this->getJson("/api/dashboard/job-title-insights?country_id={$india->id}")
            ->assertOk()
            ->assertJsonPath('status', true);

        $data = collect($response->json('data'));

        $this->assertCount(2, $data);
        $this->assertNull($data->firstWhere('country', 'United States'));

        $softwareInsight = $data->firstWhere('job_title', 'Software Engineer');

        $this->assertSame('India', $softwareInsight['country']);
        $this->assertSame(2, $softwareInsight['total_employees']);
        $this->assertEquals(150000, $softwareInsight['average_salary']);
        $this->assertEquals(100000, $softwareInsight['minimum_salary']);
        $this->assertEquals(200000, $softwareInsight['maximum_salary']);
    }

    private function createEmployee(
        Country $country,
        Department $department,
        string $jobTitle,
        int $salary,
        string $status = 'active',
        string $employmentType = 'full_time'
    ): Employee {
        return Employee::factory()->create([
            'user_id' => User::factory()->create()->id,
            'country_id' => $country->id,
            'department_id' => $department->id,
            'job_title' => $jobTitle,
            'salary' => $salary,
            'status' => $status,
            'employment_type' => $employmentType,
            'joining_date' => '2026-05-18',
        ]);
    }
}
