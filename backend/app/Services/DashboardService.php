<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\EmployeeStatus;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function summary(): array
    {
        return [
            'total_employees' => Employee::count(),
            'active_employees' => Employee::where('status', EmployeeStatus::ACTIVE->value)->count(),
            'inactive_employees' => Employee::where('status', EmployeeStatus::INACTIVE->value)->count(),
            'average_salary' => round((float) Employee::avg('salary'), 2),
            'minimum_salary' => Employee::min('salary'),
            'maximum_salary' => Employee::max('salary'),
            'employment_types' => Employee::select('employment_type', DB::raw('COUNT(*) as total'))
                ->groupBy('employment_type')
                ->get(),
        ];
    }

    public function countrySalaryInsights(): Collection
    {
        return Employee::query()
            ->join('countries', 'employees.country_id', '=', 'countries.id')
            ->select(
                'countries.name as country',
                DB::raw('COUNT(employees.id) as total_employees'),
                DB::raw('MIN(salary) as minimum_salary'),
                DB::raw('MAX(salary) as maximum_salary'),
                DB::raw('ROUND(AVG(salary), 2) as average_salary')
            )
            ->groupBy('countries.name')
            ->orderBy('average_salary', 'desc')
            ->get();
    }

    public function jobTitleInsights(array $filters): Collection
    {
        return Employee::query()
            ->join('countries', 'employees.country_id', '=', 'countries.id')
            ->select(
                'countries.name as country',
                'job_title',
                DB::raw('COUNT(employees.id) as total_employees'),
                DB::raw('ROUND(AVG(salary), 2) as average_salary'),
                DB::raw('MIN(salary) as minimum_salary'),
                DB::raw('MAX(salary) as maximum_salary')
            )
            ->when($filters['country_id'] ?? null, function ($query, $countryId) {
                $query->where('employees.country_id', $countryId);
            })
            ->groupBy('countries.name', 'job_title')
            ->orderBy('average_salary', 'desc')
            ->get();
    }

    public function departmentInsights(): Collection
    {
        return Employee::query()
            ->join('departments', 'employees.department_id', '=', 'departments.id')
            ->select(
                'departments.name as department',
                DB::raw('COUNT(employees.id) as total_employees'),
                DB::raw('ROUND(AVG(salary), 2) as average_salary'),
                DB::raw('MIN(salary) as minimum_salary'),
                DB::raw('MAX(salary) as maximum_salary')
            )
            ->groupBy('departments.name')
            ->orderBy('total_employees', 'desc')
            ->get();
    }

    public function salaryDistribution(): array
    {
        return [
            [
                'range' => '0 - 50K',
                'total' => Employee::whereBetween('salary', [0, 50000])->count(),
            ],
            [
                'range' => '50K - 100K',
                'total' => Employee::whereBetween('salary', [50001, 100000])->count(),
            ],
            [
                'range' => '100K - 150K',
                'total' => Employee::whereBetween('salary', [100001, 150000])->count(),
            ],
            [
                'range' => '150K - 200K',
                'total' => Employee::whereBetween('salary', [150001, 200000])->count(),
            ],
            [
                'range' => '200K+',
                'total' => Employee::where('salary', '>', 200000)->count(),
            ],
        ];
    }
}
