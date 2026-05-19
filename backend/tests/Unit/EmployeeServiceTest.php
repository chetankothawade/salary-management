<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Country;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use App\Services\EmployeeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeServiceTest extends TestCase
{
    use RefreshDatabase;

    private EmployeeService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(EmployeeService::class);
    }

    public function test_it_creates_updates_finds_toggles_and_deletes_employee(): void
    {
        $country = Country::factory()->create(['name' => 'India', 'code' => 'IN']);
        $department = Department::factory()->create(['name' => 'Engineering']);
        $user = User::factory()->create(['name' => 'Ravi Kumar']);

        $employee = $this->service->createEmployee([
            'user_id' => $user->id,
            'employee_code' => 'UNIT-EMP-001',
            'department_id' => $department->id,
            'country_id' => $country->id,
            'job_title' => 'Software Engineer',
            'salary' => 100000,
            'employment_type' => 'full_time',
            'status' => 'active',
            'joining_date' => '2026-05-18',
        ]);

        $this->assertSame('UNIT-EMP-001', $employee->employee_code);
        $this->assertSame('Ravi Kumar', $employee->user->name);

        $updated = $this->service->updateEmployee($employee, [
            'job_title' => 'Senior Software Engineer',
            'salary' => 125000,
        ]);

        $this->assertSame('Senior Software Engineer', $updated->job_title);
        $this->assertSame('125000.00', $updated->salary);

        $found = $this->service->getByUuid((string) $employee->uuid);

        $this->assertNotNull($found);
        $this->assertSame($employee->id, $found->id);

        $inactive = $this->service->toggleStatus($employee->fresh());

        $this->assertSame('inactive', $inactive->status->value);

        $active = $this->service->toggleStatus($inactive);

        $this->assertSame('active', $active->status->value);

        $this->service->deleteEmployee($active);

        $this->assertSoftDeleted('employees', [
            'id' => $employee->id,
        ]);
    }

    public function test_it_paginates_filters_searches_and_returns_active_dropdown_list(): void
    {
        $india = Country::factory()->create(['name' => 'India', 'code' => 'IN']);
        $usa = Country::factory()->create(['name' => 'United States', 'code' => 'US']);
        $engineering = Department::factory()->create(['name' => 'Engineering']);
        $hr = Department::factory()->create(['name' => 'Human Resources']);

        $match = Employee::factory()->create([
            'user_id' => User::factory()->create(['name' => 'Asha Engineer'])->id,
            'country_id' => $india->id,
            'department_id' => $engineering->id,
            'employee_code' => 'UNIT-EMP-SEARCH',
            'job_title' => 'Software Engineer',
            'employment_type' => 'full_time',
            'status' => 'active',
        ]);

        Employee::factory()->create([
            'user_id' => User::factory()->create(['name' => 'Inactive Engineer'])->id,
            'country_id' => $india->id,
            'department_id' => $engineering->id,
            'job_title' => 'Software Engineer',
            'employment_type' => 'full_time',
            'status' => 'inactive',
        ]);

        Employee::factory()->create([
            'user_id' => User::factory()->create(['name' => 'US HR'])->id,
            'country_id' => $usa->id,
            'department_id' => $hr->id,
            'job_title' => 'HR Manager',
            'employment_type' => 'part_time',
            'status' => 'active',
        ]);

        $page = $this->service->getPaginatedEmployees([
            'search' => 'Asha',
            'status' => 'active',
            'employment_type' => 'full_time',
            'department_id' => $engineering->id,
            'country_id' => $india->id,
            'sortedField' => 'id',
            'sortedBy' => 'asc',
            'perPage' => 10,
        ]);

        $this->assertSame(1, $page->total());
        $this->assertSame($match->id, $page->items()[0]->id);

        $list = $this->service->getEmployeeList();

        $this->assertCount(2, $list);
        $this->assertTrue($list->contains('id', $match->id));
        $this->assertFalse($list->contains('status', 'inactive'));
    }
}
