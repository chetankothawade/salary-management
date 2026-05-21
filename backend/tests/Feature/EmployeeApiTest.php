<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Country;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_can_be_created_listed_updated_patched_and_deleted(): void
    {
        $country = Country::factory()->create(['name' => 'India', 'code' => 'IN', 'currency' => 'INR']);
        $department = Department::factory()->create(['name' => 'Engineering']);

        $this->getJson('/api/employees/options')
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.departments.0.name', 'Engineering')
            ->assertJsonPath('data.countries.0.name', 'India');

        $createPayload = [
            'name' => 'Asha Sharma',
            'email' => 'asha.sharma@example.com',
            'employee_code' => 'emp-test-001',
            'department_id' => $department->id,
            'country_id' => $country->id,
            'job_title' => 'Software Engineer',
            'salary' => 100000,
            'employment_type' => 'full_time',
            'status' => 'active',
            'joining_date' => '2026-05-18',
        ];

        $createResponse = $this->postJson('/api/employees', $createPayload)
            ->assertCreated()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.employee_code', 'EMP-TEST-001')
            ->assertJsonPath('data.name', 'Asha Sharma')
            ->assertJsonPath('data.email', 'asha.sharma@example.com')
            ->assertJsonPath('data.full_name', 'Asha Sharma');

        $employeeUuid = $createResponse->json('data.uuid');

        $this->getJson('/api/employees?search=Asha&sortedField=id&sortedBy=asc')
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('pagination.total', 1)
            ->assertJsonPath('data.0.uuid', $employeeUuid);

        $this->getJson("/api/employees/{$employeeUuid}")
            ->assertOk()
            ->assertJsonPath('data.job_title', 'Software Engineer');

        $this->putJson("/api/employees/{$employeeUuid}", [
            ...$createPayload,
            'name' => 'Asha Patil',
            'email' => 'asha.patil@example.com',
            'employee_code' => 'EMP-TEST-001',
            'job_title' => 'Senior Software Engineer',
            'salary' => 125000,
        ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Asha Patil')
            ->assertJsonPath('data.email', 'asha.patil@example.com')
            ->assertJsonPath('data.job_title', 'Senior Software Engineer')
            ->assertJsonPath('data.salary', '125000.00');

        $this->patchJson("/api/employees/{$employeeUuid}", [
            'salary' => 130000,
        ])
            ->assertOk()
            ->assertJsonPath('data.salary', '130000.00');

        $this->patchJson("/api/employees/{$employeeUuid}/active")
            ->assertOk()
            ->assertJsonPath('data.status', 'inactive');

        $this->deleteJson("/api/employees/{$employeeUuid}")
            ->assertOk()
            ->assertJsonPath('status', true);

        $this->assertSoftDeleted('employees', [
            'uuid' => $employeeUuid,
        ]);
    }

    public function test_employee_create_validation_errors_are_returned(): void
    {
        $this->postJson('/api/employees', [
            'salary' => -1,
            'employment_type' => 'temporary',
            'status' => 'archived',
        ])
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Validation failed. Please check the submitted information.')
            ->assertJsonValidationErrors([
                'name',
                'email',
                'employee_code',
                'department_id',
                'country_id',
                'job_title',
                'salary',
                'employment_type',
                'status',
                'joining_date',
            ]);
    }

    public function test_employee_patch_allows_partial_update_but_validates_present_fields(): void
    {
        $employee = Employee::factory()->create([
            'salary' => 90000,
        ]);

        $this->patchJson("/api/employees/{$employee->uuid}", [
            'salary' => 95000,
        ])
            ->assertOk()
            ->assertJsonPath('data.salary', '95000.00');

        $this->patchJson("/api/employees/{$employee->uuid}", [
            'employment_type' => 'invalid',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['employment_type']);
    }
}
