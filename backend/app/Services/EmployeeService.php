<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\EmployeeStatus;
use App\Models\Employee;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class EmployeeService
{
    public function getPaginatedEmployees(array $filters): LengthAwarePaginator
    {
        $search = $filters['search'] ?? null;
        $status = $filters['status'] ?? null;
        $employmentType = $filters['employment_type'] ?? null;
        $departmentId = $filters['department_id'] ?? null;
        $countryId = $filters['country_id'] ?? null;
        $sortedField = $filters['sortedField'] ?? 'id';
        $sortedBy = $filters['sortedBy'] ?? 'asc';
        $perPage = $filters['perPage'] ?? 10;

        $query = Employee::query()
            ->with(['user:id,uuid,name,email,role,is_active', 'department:id,name', 'country:id,name,code,currency']);

        // Apply filters only when present so the same endpoint supports list, search, and drill-down views.
        if (! empty($status)) {
            $query->where('status', $status);
        }

        if (! empty($employmentType)) {
            $query->where('employment_type', $employmentType);
        }

        if (! empty($departmentId)) {
            $query->where('department_id', $departmentId);
        }

        if (! empty($countryId)) {
            $query->where('country_id', $countryId);
        }

        if (! empty($search)) {
            // Search includes employee-owned fields and the linked user's name/email for HR lookup workflows.
            $query->where(function ($q) use ($search) {
                $q->where('employee_code', 'like', "%{$search}%")
                    ->orWhere('job_title', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        return $query->orderBy($sortedField, $sortedBy)->paginate($perPage);
    }

    public function createEmployee(array $data): Employee
    {
        return DB::transaction(function () use ($data) {
            // Reload relationships so controllers can return a complete resource immediately after creation.
            return Employee::create($data)->load(['user', 'department', 'country']);
        });
    }

    public function updateEmployee(Employee $employee, array $data): Employee
    {
        return DB::transaction(function () use ($employee, $data) {
            $employee->update($data);

            return $employee->fresh(['user', 'department', 'country']);
        });
    }

    public function deleteEmployee(Employee $employee): void
    {
        DB::transaction(function () use ($employee) {
            // Mark inactive before soft delete so historical reporting does not treat deleted employees as active.
            $employee->update(['status' => EmployeeStatus::INACTIVE->value]);
            $employee->delete();
        });
    }

    public function toggleStatus(Employee $employee): Employee
    {
        return DB::transaction(function () use ($employee) {
            // Enum casts return EmployeeStatus instances, so compare against the enum case.
            $newStatus = $employee->status === EmployeeStatus::ACTIVE
                ? EmployeeStatus::INACTIVE->value
                : EmployeeStatus::ACTIVE->value;

            $employee->update(['status' => $newStatus]);

            return $employee->fresh(['user', 'department', 'country']);
        });
    }

    public function getByUuid(string $uuid): ?Employee
    {
        return Employee::with(['user', 'department', 'country'])
            ->where('uuid', $uuid)
            ->first();
    }

    public function getEmployeeList(): Collection
    {
        // Lightweight dropdown payload for UI controls; full details are served by index/show.
        return Employee::query()
            ->select(['id', 'uuid', 'user_id', 'employee_code', 'job_title'])
            ->with(['user:id,name'])
            ->where('status', EmployeeStatus::ACTIVE->value)
            ->orderBy('employee_code')
            ->get();
    }
}
