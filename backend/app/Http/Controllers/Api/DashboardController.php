<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Dashboard\JobTitleInsightsRequest;
use App\Services\DashboardService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    public function summary(): JsonResponse
    {
        // Dashboard endpoints return read-only aggregate data; calculations stay in the service.
        return $this->success(
            'messages.dashboard_summary_success',
            $this->dashboardService->summary()
        );
    }

    public function countrySalaryInsights(): JsonResponse
    {
        return $this->success(
            'messages.dashboard_country_salary_insights_success',
            $this->dashboardService->countrySalaryInsights()
        );
    }

    public function jobTitleInsights(JobTitleInsightsRequest $request): JsonResponse
    {
        // The optional country filter is validated before reaching the aggregate query.
        return $this->success(
            'messages.dashboard_job_title_insights_success',
            $this->dashboardService->jobTitleInsights($request->filters())
        );
    }

    public function departmentInsights(): JsonResponse
    {
        return $this->success(
            'messages.dashboard_department_insights_success',
            $this->dashboardService->departmentInsights()
        );
    }

    public function salaryDistribution(): JsonResponse
    {
        return $this->success(
            'messages.dashboard_salary_distribution_success',
            $this->dashboardService->salaryDistribution()
        );
    }
}
