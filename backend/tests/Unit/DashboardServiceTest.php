<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Country;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardServiceTest extends TestCase
{
    use RefreshDatabase;

    private DashboardService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(DashboardService::class);
    }

    public function test_summary_department_and_salary_distribution_are_calculated_from_fixed_data(): void
    {
        $india = Country::factory()->create(['name' => 'India', 'code' => 'IN']);
        $engineering = Department::factory()->create(['name' => 'Engineering']);
        $finance = Department::factory()->create(['name' => 'Finance']);

        $this->employee($india, $engineering, 'Software Engineer', 40000, 'active', 'full_time');
        $this->employee($india, $engineering, 'Software Engineer', 100000, 'active', 'contract');
        $this->employee($india, $finance, 'Finance Analyst', 160000, 'inactive', 'part_time');
        $this->employee($india, $finance, 'Finance Manager', 250000, 'active', 'full_time');

        $summary = $this->service->summary();

        $this->assertSame(4, $summary['total_employees']);
        $this->assertSame(3, $summary['active_employees']);
        $this->assertSame(1, $summary['inactive_employees']);
        $this->assertSame(137500.0, $summary['average_salary']);
        $this->assertEquals(40000, $summary['minimum_salary']);
        $this->assertEquals(250000, $summary['maximum_salary']);

        $departments = $this->service->departmentInsights();
        $engineeringInsight = $departments->firstWhere('department', 'Engineering');

        $this->assertNotNull($engineeringInsight);
        $this->assertSame(2, $engineeringInsight->getAttribute('total_employees'));
        $this->assertEquals(70000, $engineeringInsight->getAttribute('average_salary'));

        $distribution = collect($this->service->salaryDistribution());

        $this->assertSame(1, $distribution->firstWhere('range', '0 - 50K')['total']);
        $this->assertSame(1, $distribution->firstWhere('range', '50K - 100K')['total']);
        $this->assertSame(0, $distribution->firstWhere('range', '100K - 150K')['total']);
        $this->assertSame(1, $distribution->firstWhere('range', '150K - 200K')['total']);
        $this->assertSame(1, $distribution->firstWhere('range', '200K+')['total']);
    }

    public function test_country_and_job_title_insights_are_calculated_with_country_filter(): void
    {
        $india = Country::factory()->create(['name' => 'India', 'code' => 'IN']);
        $usa = Country::factory()->create(['name' => 'United States', 'code' => 'US']);
        $engineering = Department::factory()->create(['name' => 'Engineering']);

        $this->employee($india, $engineering, 'Software Engineer', 100000);
        $this->employee($india, $engineering, 'Software Engineer', 200000);
        $this->employee($india, $engineering, 'HR Manager', 50000);
        $this->employee($usa, $engineering, 'Software Engineer', 300000);

        $countryInsights = $this->service->countrySalaryInsights();
        $indiaInsight = $countryInsights->firstWhere('country', 'India');

        $this->assertNotNull($indiaInsight);
        $this->assertSame(3, $indiaInsight->getAttribute('total_employees'));
        $this->assertEquals(50000, $indiaInsight->getAttribute('minimum_salary'));
        $this->assertEquals(200000, $indiaInsight->getAttribute('maximum_salary'));
        $this->assertEquals(116666.67, $indiaInsight->getAttribute('average_salary'));

        $jobInsights = $this->service->jobTitleInsights([
            'country_id' => $india->id,
        ]);

        $softwareInsight = $jobInsights->firstWhere('job_title', 'Software Engineer');

        $this->assertNotNull($softwareInsight);
        $this->assertCount(2, $jobInsights);
        $this->assertSame('India', $softwareInsight->getAttribute('country'));
        $this->assertSame(2, $softwareInsight->getAttribute('total_employees'));
        $this->assertEquals(150000, $softwareInsight->getAttribute('average_salary'));
        $this->assertNull($jobInsights->firstWhere('country', 'United States'));
    }

    private function employee(
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
