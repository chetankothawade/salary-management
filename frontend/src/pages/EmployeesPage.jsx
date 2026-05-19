import {
  Alert,
  Box,
  Button,
  Divider,
  InputAdornment,
  MenuItem,
  Paper,
  Stack,
  TextField,
  Typography,
} from '@mui/material'
import AddIcon from '@mui/icons-material/Add'
import ClearIcon from '@mui/icons-material/Clear'
import SearchIcon from '@mui/icons-material/Search'
import { useEffect, useMemo, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import DeleteEmployeeDialog from '../features/employees/DeleteEmployeeDialog'
import EmployeeTable from '../features/employees/EmployeeTable'
import {
  useDeleteEmployee,
  useEmployeeOptions,
  useEmployees,
  useToggleEmployeeStatus,
} from '../features/employees/employeeHooks'

function EmployeesPage() {
  const navigate = useNavigate()
  const [searchInput, setSearchInput] = useState('')
  const [search, setSearch] = useState('')
  const [filters, setFilters] = useState({
    status: '',
    employment_type: '',
    department_id: '',
    country_id: '',
  })
  const [paginationModel, setPaginationModel] = useState({
    page: 0,
    pageSize: 10,
  })
  const [sortModel, setSortModel] = useState([
    {
      field: 'id',
      sort: 'asc',
    },
  ])
  const [deleteTarget, setDeleteTarget] = useState(null)

  useEffect(() => {
    const timeout = window.setTimeout(() => {
      setSearch(searchInput)
      setPaginationModel((current) => ({ ...current, page: 0 }))
    }, 350)

    return () => window.clearTimeout(timeout)
  }, [searchInput])

  const queryParams = useMemo(() => {
    const activeSort = sortModel[0] ?? { field: 'id', sort: 'asc' }

    return Object.fromEntries(
      Object.entries({
        ...filters,
        search,
        page: paginationModel.page + 1,
        perPage: paginationModel.pageSize,
        sortedField: activeSort.field,
        sortedBy: activeSort.sort ?? 'asc',
      }).filter(([, value]) => value !== ''),
    )
  }, [filters, paginationModel, search, sortModel])

  const employees = useEmployees(queryParams)
  const options = useEmployeeOptions()
  const deleteEmployee = useDeleteEmployee()
  const toggleStatus = useToggleEmployeeStatus()

  const departments = options.data?.data?.departments ?? []
  const countries = options.data?.data?.countries ?? []
  const rows = employees.data?.data ?? []
  const rowCount = employees.data?.pagination?.total ?? 0
  const mutationError = deleteEmployee.error

  const handleFilterChange = (event) => {
    const { name, value } = event.target

    setFilters((current) => ({
      ...current,
      [name]: value,
    }))
    setPaginationModel((current) => ({ ...current, page: 0 }))
  }

  const handleClearFilters = () => {
    setSearchInput('')
    setSearch('')
    setFilters({
      status: '',
      employment_type: '',
      department_id: '',
      country_id: '',
    })
    setPaginationModel((current) => ({ ...current, page: 0 }))
  }

  const handleConfirmDelete = () => {
    if (!deleteTarget) {
      return
    }

    deleteEmployee.mutate(deleteTarget.uuid, {
      onSuccess: () => setDeleteTarget(null),
    })
  }

  return (
    <Stack spacing={3}>
      <Stack
        direction={{ xs: 'column', sm: 'row' }}
        spacing={2}
        alignItems={{ xs: 'stretch', sm: 'center' }}
        justifyContent="space-between"
      >
        <Box sx={{ flex: 1, minWidth: 0 }}>
          <Typography variant="h4">Employees</Typography>
          <Typography color="text.secondary">
            Search, filter, and maintain employee salary records.
          </Typography>
        </Box>
        <Box sx={{ display: 'flex', justifyContent: { xs: 'stretch', sm: 'flex-end' } }}>
          <Button
            variant="contained"
            startIcon={<AddIcon />}
            size="small"
            onClick={() => navigate('/employees/create')}
            sx={{
              height: 38,
              px: 2,
              width: { xs: '100%', sm: 'auto' },
            }}
          >
            Add Employee
          </Button>
        </Box>
      </Stack>

      <Paper variant="outlined" sx={{ p: 2 }}>
        <Stack
          direction={{ xs: 'column', lg: 'row' }}
          spacing={2}
          alignItems={{ xs: 'stretch', lg: 'center' }}
          useFlexGap
          sx={{ flexWrap: 'wrap' }}
        >
          <TextField
            label="Search"
            placeholder="Name, email, code, or job title"
            value={searchInput}
            onChange={(event) => setSearchInput(event.target.value)}
            size="small"
            fullWidth
            slotProps={{
              input: {
                startAdornment: (
                  <InputAdornment position="start">
                    <SearchIcon fontSize="small" />
                  </InputAdornment>
                ),
              },
            }}
          />
          <TextField
            select
            name="status"
            label="Status"
            value={filters.status}
            onChange={handleFilterChange}
            size="small"
            sx={{ minWidth: { xs: '100%', lg: 180 } }}
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
            sx={{ minWidth: { xs: '100%', lg: 200 } }}
          >
            <MenuItem value="">All</MenuItem>
            <MenuItem value="full_time">Full time</MenuItem>
            <MenuItem value="part_time">Part time</MenuItem>
            <MenuItem value="contract">Contract</MenuItem>
            <MenuItem value="intern">Intern</MenuItem>
          </TextField>
          <TextField
            select
            name="department_id"
            label="Department"
            value={filters.department_id}
            onChange={handleFilterChange}
            size="small"
            disabled={options.isLoading}
            sx={{ minWidth: { xs: '100%', lg: 220 } }}
          >
            <MenuItem value="">All</MenuItem>
            {departments.map((department) => (
              <MenuItem key={department.id} value={department.id}>
                {department.name}
              </MenuItem>
            ))}
          </TextField>
          <TextField
            select
            name="country_id"
            label="Country"
            value={filters.country_id}
            onChange={handleFilterChange}
            size="small"
            disabled={options.isLoading}
            sx={{ minWidth: { xs: '100%', lg: 200 } }}
          >
            <MenuItem value="">All</MenuItem>
            {countries.map((country) => (
              <MenuItem key={country.id} value={country.id}>
                {country.name}
              </MenuItem>
            ))}
          </TextField>
          <Divider flexItem orientation="vertical" sx={{ display: { xs: 'none', lg: 'block' } }} />
          <Button
            variant="outlined"
            startIcon={<ClearIcon />}
            onClick={handleClearFilters}
            sx={{ minWidth: 130, minHeight: 40 }}
          >
            Clear
          </Button>
        </Stack>
      </Paper>

      {employees.error ? (
        <Alert severity="error">{employees.error.message}</Alert>
      ) : null}

      {mutationError ? (
        <Alert severity="error">{mutationError.message}</Alert>
      ) : null}

      <Paper variant="outlined">
        <EmployeeTable
          rows={rows}
          rowCount={rowCount}
          loading={employees.isLoading || employees.isFetching}
          paginationModel={paginationModel}
          onPaginationModelChange={setPaginationModel}
          sortModel={sortModel}
          onSortModelChange={setSortModel}
          onEdit={(employee) => navigate(`/employees/${employee.uuid}/edit`)}
          onDelete={setDeleteTarget}
          onToggleStatus={(employee) => toggleStatus.mutate(employee.uuid)}
        />
      </Paper>

      <DeleteEmployeeDialog
        open={Boolean(deleteTarget)}
        employee={deleteTarget}
        loading={deleteEmployee.isPending}
        onClose={() => setDeleteTarget(null)}
        onConfirm={handleConfirmDelete}
      />
    </Stack>
  )
}

export default EmployeesPage
