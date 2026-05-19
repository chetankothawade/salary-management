<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Employee;

use App\Enums\EmployeeEmploymentType;
use App\Enums\EmployeeStatus;
use App\Http\Requests\Api\BaseApiRequest;
use App\Models\Employee;
use Illuminate\Validation\Rule;

class EmployeeUpdateRequest extends BaseApiRequest
{
    public function rules(): array
    {
        $uuid = (string) ($this->route('employee') ?? $this->route('uuid'));
        $required = $this->isMethod('patch') ? 'sometimes' : 'required';
        $employee = Employee::where('uuid', $uuid)->first();
        $userId = $employee?->user_id;

        return [
            'name' => "{$required}|string|max:255",
            'email' => [
                $required,
                'email:rfc,dns',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'employee_code' => [
                $required,
                'string',
                'max:50',
                Rule::unique('employees', 'employee_code')->ignore($uuid, 'uuid'),
            ],
            'department_id' => "{$required}|integer|exists:departments,id",
            'country_id' => "{$required}|integer|exists:countries,id",
            'job_title' => "{$required}|string|max:150",
            'salary' => "{$required}|numeric|min:0|max:999999999999.99",
            'employment_type' => "{$required}|in:".implode(',', EmployeeEmploymentType::values()),
            'status' => "{$required}|in:".implode(',', EmployeeStatus::values()),
            'joining_date' => "{$required}|date",
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('email')) {
            $this->merge([
                'email' => strtolower(trim((string) $this->email)),
            ]);
        }

        if ($this->has('employee_code')) {
            $this->merge([
                'employee_code' => strtoupper(trim((string) $this->employee_code)),
            ]);
        }
    }
}
