import { api } from './axios'

export const dashboardApi = {
  summary: () => api.get('/dashboard/summary'),
  countrySalaryInsights: () => api.get('/dashboard/country-salary-insights'),
  jobTitleInsights: (params) =>
    api.get('/dashboard/job-title-insights', { params }),
  departmentInsights: () => api.get('/dashboard/department-insights'),
  salaryDistribution: () => api.get('/dashboard/salary-distribution'),
}
