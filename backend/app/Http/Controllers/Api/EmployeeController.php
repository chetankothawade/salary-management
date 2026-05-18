<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Employee\EmployeeIndexRequest;
use App\Http\Requests\Api\Employee\EmployeeStoreRequest;
use App\Http\Requests\Api\Employee\EmployeeUpdateRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Services\EmployeeService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected EmployeeService $employeeService
    ) {}

    public function index(EmployeeIndexRequest $request): JsonResponse
    {
        $employees = $this->employeeService->getPaginatedEmployees($request->filters());

        return $this->paginate(
            'messages.employee_list_success',
            EmployeeResource::collection($employees),
            $employees
        );
    }

    public function store(EmployeeStoreRequest $request): JsonResponse
    {
        return $this->safeExecute(
            'messages.employee_created',
            fn () => new EmployeeResource(
                $this->employeeService->createEmployee($request->validated())
            ),
            201
        );
    }

    public function show(string $uuid): JsonResponse
    {
        return $this->success(
            'messages.employee_details_success',
            new EmployeeResource($this->findEmployeeOrFail($uuid))
        );
    }

    public function update(EmployeeUpdateRequest $request, string $uuid): JsonResponse
    {
        return $this->safeExecute(
            'messages.employee_updated',
            fn () => new EmployeeResource(
                $this->employeeService->updateEmployee(
                    $this->findEmployeeOrFail($uuid),
                    $request->validated()
                )
            )
        );
    }

    public function destroy(string $uuid): JsonResponse
    {
        return $this->safeExecute('messages.employee_deleted', function () use ($uuid) {
            $this->employeeService->deleteEmployee($this->findEmployeeOrFail($uuid));

            return [];
        });
    }

    public function active(string $uuid): JsonResponse
    {
        return $this->safeExecute(
            'messages.employee_status_updated',
            fn () => new EmployeeResource(
                $this->employeeService->toggleStatus($this->findEmployeeOrFail($uuid))
            )
        );
    }

    public function getEmployeeList(): JsonResponse
    {
        return $this->success(
            'messages.employee_dropdown_success',
            $this->employeeService->getEmployeeList()
        );
    }

    private function findEmployeeOrFail(string $uuid): Employee
    {
        $employee = $this->employeeService->getByUuid($uuid);

        if (! $employee) {
            abort(404, __('messages.employee_not_found'));
        }

        return $employee;
    }
}
