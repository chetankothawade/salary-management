import {
  Alert,
  Box,
  Button,
  Chip,
  CircularProgress,
  MenuItem,
  Paper,
  Stack,
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableRow,
  TextField,
  Typography,
} from '@mui/material'
import { useMemo, useState } from 'react'
import {
  useDeleteEmployee,
  useEmployees,
  useToggleEmployeeStatus,
} from '../features/employees/employeeHooks'

const statusColor = {
  active: 'success',
  inactive: 'default',
}

function EmployeesPage() {
  const [filters, setFilters] = useState({
    search: '',
    status: '',
    employment_type: '',
    sortedField: 'id',
    sortedBy: 'asc',
    perPage: 10,
  })

  const queryParams = useMemo(
    () =>
      Object.fromEntries(
        Object.entries(filters).filter(([, value]) => value !== ''),
      ),
    [filters],
  )

  const employees = useEmployees(queryParams)
  const deleteEmployee = useDeleteEmployee()
  const toggleStatus = useToggleEmployeeStatus()

  const rows = employees.data?.data ?? []
  const pagination = employees.data?.pagination

  const handleFilterChange = (event) => {
    const { name, value } = event.target

    setFilters((current) => ({
      ...current,
      [name]: value,
    }))
  }

  return (
    <Stack spacing={3}>
      <Box>
        <Typography variant="h4">Employees</Typography>
        <Typography color="text.secondary">
          Search, filter, and maintain employee salary records.
        </Typography>
      </Box>

      <Paper variant="outlined" sx={{ p: 2 }}>
        <Stack direction={{ xs: 'column', md: 'row' }} spacing={2}>
          <TextField
            name="search"
            label="Search"
            value={filters.search}
            onChange={handleFilterChange}
            size="small"
            fullWidth
          />
          <TextField
            select
            name="status"
            label="Status"
            value={filters.status}
            onChange={handleFilterChange}
            size="small"
            sx={{ minWidth: 160 }}
          >
            <MenuItem value="">All</MenuItem>
            <MenuItem value="active">Active</MenuItem>
            <MenuItem value="inactive">Inactive</MenuItem>
          </TextField>
          <TextField
            select
            name="employment_type"
            label="Type"
            value={filters.employment_type}
            onChange={handleFilterChange}
            size="small"
            sx={{ minWidth: 180 }}
          >
            <MenuItem value="">All</MenuItem>
            <MenuItem value="full_time">Full time</MenuItem>
            <MenuItem value="part_time">Part time</MenuItem>
            <MenuItem value="contract">Contract</MenuItem>
            <MenuItem value="intern">Intern</MenuItem>
          </TextField>
        </Stack>
      </Paper>

      {employees.isLoading ? (
        <Box sx={{ display: 'grid', minHeight: 320, placeItems: 'center' }}>
          <CircularProgress />
        </Box>
      ) : null}

      {employees.error ? (
        <Alert severity="error">{employees.error.message}</Alert>
      ) : null}

      {!employees.isLoading && !employees.error ? (
        <Paper variant="outlined">
          <Table size="small">
            <TableHead>
              <TableRow>
                <TableCell>Code</TableCell>
                <TableCell>Name</TableCell>
                <TableCell>Job Title</TableCell>
                <TableCell>Country</TableCell>
                <TableCell align="right">Salary</TableCell>
                <TableCell>Status</TableCell>
                <TableCell align="right">Actions</TableCell>
              </TableRow>
            </TableHead>
            <TableBody>
              {rows.map((employee) => (
                <TableRow key={employee.uuid}>
                  <TableCell>{employee.employee_code}</TableCell>
                  <TableCell>{employee.full_name}</TableCell>
                  <TableCell>{employee.job_title}</TableCell>
                  <TableCell>{employee.country?.name ?? '-'}</TableCell>
                  <TableCell align="right">{employee.salary}</TableCell>
                  <TableCell>
                    <Chip
                      label={employee.status}
                      size="small"
                      color={statusColor[employee.status] ?? 'default'}
                    />
                  </TableCell>
                  <TableCell align="right">
                    <Stack direction="row" spacing={1} justifyContent="flex-end">
                      <Button
                        size="small"
                        variant="outlined"
                        onClick={() => toggleStatus.mutate(employee.uuid)}
                      >
                        Toggle
                      </Button>
                      <Button
                        size="small"
                        color="error"
                        variant="outlined"
                        onClick={() => deleteEmployee.mutate(employee.uuid)}
                      >
                        Delete
                      </Button>
                    </Stack>
                  </TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>

          <Box sx={{ p: 2 }}>
            <Typography variant="body2" color="text.secondary">
              Showing {pagination?.from ?? 0}-{pagination?.to ?? 0} of{' '}
              {pagination?.total ?? 0}
            </Typography>
          </Box>
        </Paper>
      ) : null}
    </Stack>
  )
}

export default EmployeesPage
