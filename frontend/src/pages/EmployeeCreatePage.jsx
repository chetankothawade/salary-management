import { Box, Typography } from '@mui/material'
import { Stack } from '@mui/system'
import { useNavigate } from 'react-router-dom'
import toast from 'react-hot-toast'
import EmployeeForm from '../features/employees/EmployeeForm'
import { useCreateEmployee } from '../features/employees/employeeHooks'

function EmployeeCreatePage() {
  const navigate = useNavigate()
  const createEmployee = useCreateEmployee()

  const handleSubmit = (payload) => {
    createEmployee.mutate(payload, {
      onSuccess: () => {
        toast.success('Employee created successfully.')
        navigate('/employees')
      },
    })
  }

  return (
    <Stack spacing={3}>
      <Box>
        <Typography variant="h4">Add Employee</Typography>
        <Typography color="text.secondary">
          Create a new employee profile and salary record.
        </Typography>
      </Box>

      <EmployeeForm
        mode="create"
        loading={createEmployee.isPending}
        error={createEmployee.error}
        onCancel={() => navigate('/employees')}
        onSubmit={handleSubmit}
      />
    </Stack>
  )
}

export default EmployeeCreatePage
