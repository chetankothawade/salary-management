<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Employee;
use BackedEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Employee $employee */
        $employee = $this->resource;

        return [
            'id' => $employee->id,
            'uuid' => $employee->uuid,
            'user_id' => $employee->user_id,
            'employee_code' => $employee->employee_code,
            'full_name' => $employee->full_name,
            'job_title' => $employee->job_title,
            'salary' => $employee->salary,
            'employment_type' => $this->enumValue($employee->employment_type),
            'status' => $this->enumValue($employee->status),
            'joining_date' => $employee->joining_date->toDateString(),
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $employee->user?->id,
                'uuid' => $employee->user?->uuid,
                'name' => $employee->user?->name,
                'email' => $employee->user?->email,
                'role' => $employee->user?->role,
                'is_active' => $employee->user?->is_active,
            ]),
            'department' => $this->whenLoaded('department', fn () => [
                'id' => $employee->department?->id,
                'name' => $employee->department?->name,
            ]),
            'country' => $this->whenLoaded('country', fn () => [
                'id' => $employee->country?->id,
                'name' => $employee->country?->name,
                'code' => $employee->country?->code,
                'currency' => $employee->country?->currency,
            ]),
            'created_at' => $employee->created_at?->toISOString(),
            'updated_at' => $employee->updated_at?->toISOString(),
        ];
    }

    private function enumValue(mixed $value): mixed
    {
        return $value instanceof BackedEnum ? $value->value : $value;
    }
}
