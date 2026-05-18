<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Employee;

use App\Enums\EmployeeEmploymentType;
use App\Enums\EmployeeStatus;
use App\Http\Requests\Api\BaseApiRequest;

class EmployeeIndexRequest extends BaseApiRequest
{
    public function rules(): array
    {
        return [
            'page' => 'nullable|integer|min:1',
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|in:'.implode(',', EmployeeStatus::values()),
            'employment_type' => 'nullable|in:'.implode(',', EmployeeEmploymentType::values()),
            'department_id' => 'nullable|integer|exists:departments,id',
            'country_id' => 'nullable|integer|exists:countries,id',
            'sortedField' => 'nullable|in:id,employee_code,job_title,salary,employment_type,status,joining_date,created_at',
            'sortedBy' => 'nullable|in:asc,desc',
            'perPage' => 'nullable|integer|min:1|max:100',
        ];
    }

    public function filters(): array
    {
        return [
            'search' => $this->input('search'),
            'status' => $this->input('status'),
            'employment_type' => $this->input('employment_type'),
            'department_id' => $this->input('department_id'),
            'country_id' => $this->input('country_id'),
            'sortedField' => $this->input('sortedField', 'id'),
            'sortedBy' => $this->input('sortedBy', 'asc'),
            'perPage' => $this->input('perPage', 10),
        ];
    }
}
