import { Alert, Box, CircularProgress, Typography } from '@mui/material'
import { Stack } from '@mui/system'
import { useNavigate, useParams } from 'react-router-dom'
import toast from 'react-hot-toast'
import EmployeeForm from '../features/employees/EmployeeForm'
import {
  useEmployee,
  useUpdateEmployee,
} from '../features/employees/employeeHooks'

function EmployeeEditPage() {
  const { uuid } = useParams()
  const navigate = useNavigate()
  const employee = useEmployee(uuid)
  const updateEmployee = useUpdateEmployee()

  const handleSubmit = (payload) => {
    updateEmployee.mutate(
      { uuid, payload },
      {
        onSuccess: () => {
          toast.success('Employee updated successfully.')
          navigate('/employees')
        },
      },
    )
  }

  if (employee.isLoading) {
    return (
      <Box sx={{ display: 'grid', minHeight: 320, placeItems: 'center' }}>
        <CircularProgress />
      </Box>
    )
  }

  if (employee.error) {
    return <Alert severity="error">{employee.error.message}</Alert>
  }

  return (
    <Stack spacing={3}>
      <Box>
        <Typography variant="h4">Edit Employee</Typography>
        <Typography color="text.secondary">
          Update employee profile and compensation details.
        </Typography>
      </Box>

      <EmployeeForm
        mode="edit"
        employee={employee.data?.data}
        loading={updateEmployee.isPending}
        error={updateEmployee.error}
        onCancel={() => navigate('/employees')}
        onSubmit={handleSubmit}
      />
    </Stack>
  )
}

export default EmployeeEditPage
