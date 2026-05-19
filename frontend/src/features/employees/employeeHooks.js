import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { employeeApi } from '../../api/employeeApi'

export function useEmployees(filters) {
  return useQuery({
    queryKey: ['employees', filters],
    queryFn: () => employeeApi.list(filters),
  })
}

export function useDeleteEmployee() {
  const queryClient = useQueryClient()

  return useMutation({
    mutationFn: employeeApi.remove,
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['employees'] })
      queryClient.invalidateQueries({ queryKey: ['dashboard'] })
    },
  })
}

export function useToggleEmployeeStatus() {
  const queryClient = useQueryClient()

  return useMutation({
    mutationFn: employeeApi.toggleStatus,
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['employees'] })
      queryClient.invalidateQueries({ queryKey: ['dashboard'] })
    },
  })
}
