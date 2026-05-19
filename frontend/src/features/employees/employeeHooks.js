import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { employeeApi } from '../../api/employeeApi'

export function useEmployees(filters) {
  return useQuery({
    queryKey: ['employees', filters],
    queryFn: () => employeeApi.list(filters),
    placeholderData: (previousData) => previousData,
  })
}

export function useEmployee(uuid) {
  return useQuery({
    queryKey: ['employees', uuid],
    queryFn: () => employeeApi.show(uuid),
    enabled: Boolean(uuid),
  })
}

export function useEmployeeOptions() {
  return useQuery({
    queryKey: ['employees', 'options'],
    queryFn: employeeApi.options,
    staleTime: 5 * 60 * 1000,
  })
}

export function useCreateEmployee() {
  const queryClient = useQueryClient()

  return useMutation({
    mutationFn: employeeApi.create,
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['employees'] })
      queryClient.invalidateQueries({ queryKey: ['dashboard'] })
    },
  })
}

export function useUpdateEmployee() {
  const queryClient = useQueryClient()

  return useMutation({
    mutationFn: ({ uuid, payload }) => employeeApi.update(uuid, payload),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['employees'] })
      queryClient.invalidateQueries({ queryKey: ['dashboard'] })
    },
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
