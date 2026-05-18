<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Employee;

use App\Enums\EmployeeEmploymentType;
use App\Enums\EmployeeStatus;
use App\Http\Requests\Api\BaseApiRequest;
use Illuminate\Validation\Rule;

class EmployeeUpdateRequest extends BaseApiRequest
{
    public function rules(): array
    {
        $uuid = (string) ($this->route('employee') ?? $this->route('uuid'));

        return [
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::unique('employees', 'user_id')->ignore($uuid, 'uuid'),
            ],
            'employee_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('employees', 'employee_code')->ignore($uuid, 'uuid'),
            ],
            'department_id' => 'required|integer|exists:departments,id',
            'country_id' => 'required|integer|exists:countries,id',
            'job_title' => 'required|string|max:150',
            'salary' => 'required|numeric|min:0|max:999999999999.99',
            'employment_type' => 'required|in:'.implode(',', EmployeeEmploymentType::values()),
            'status' => 'required|in:'.implode(',', EmployeeStatus::values()),
            'joining_date' => 'required|date',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('employee_code')) {
            $this->merge([
                'employee_code' => strtoupper(trim((string) $this->employee_code)),
            ]);
        }
    }
}
