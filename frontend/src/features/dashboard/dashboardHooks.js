import { useQuery } from '@tanstack/react-query'
import { dashboardApi } from '../../api/dashboardApi'

export function useDashboardSummary() {
  return useQuery({
    queryKey: ['dashboard', 'summary'],
    queryFn: dashboardApi.summary,
  })
}

export function useCountrySalaryInsights() {
  return useQuery({
    queryKey: ['dashboard', 'country-salary-insights'],
    queryFn: dashboardApi.countrySalaryInsights,
  })
}

export function useDepartmentInsights() {
  return useQuery({
    queryKey: ['dashboard', 'department-insights'],
    queryFn: dashboardApi.departmentInsights,
  })
}

export function useSalaryDistribution() {
  return useQuery({
    queryKey: ['dashboard', 'salary-distribution'],
    queryFn: dashboardApi.salaryDistribution,
  })
}
