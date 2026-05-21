<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Employee;

use App\Enums\EmployeeEmploymentType;
use App\Enums\EmployeeStatus;
use App\Http\Requests\Api\BaseApiRequest;

class EmployeeStoreRequest extends BaseApiRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc|max:255|unique:users,email',
            'employee_code' => 'required|string|max:50|unique:employees,employee_code',
            'department_id' => 'required|integer|exists:departments,id',
            'country_id' => 'required|integer|exists:countries,id',
            'job_title' => 'required|string|max:150',
            'salary' => 'required|numeric|min:0|max:999999999999.99',
            'employment_type' => 'nullable|in:'.implode(',', EmployeeEmploymentType::values()),
            'status' => 'nullable|in:'.implode(',', EmployeeStatus::values()),
            'joining_date' => 'required|date',
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
