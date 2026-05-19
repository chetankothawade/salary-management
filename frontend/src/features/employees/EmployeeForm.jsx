import { zodResolver } from '@hookform/resolvers/zod'
import {
  Alert,
  Button,
  Grid,
  MenuItem,
  Paper,
  Stack,
  TextField,
} from '@mui/material'
import { Controller, useForm } from 'react-hook-form'
import { useEmployeeOptions } from './employeeHooks'
import {
  employeeSchema,
  employeeToFormValues,
  emptyEmployeeValues,
} from './employeeSchema'

function EmployeeForm({ employee, mode, loading, error, onCancel, onSubmit }) {
  const options = useEmployeeOptions()
  const departments = options.data?.data?.departments ?? []
  const countries = options.data?.data?.countries ?? []

  const {
    control,
    handleSubmit,
    formState: { errors },
  } = useForm({
    resolver: zodResolver(employeeSchema),
    defaultValues: employee ? employeeToFormValues(employee) : emptyEmployeeValues,
  })

  const submitLabel = mode === 'edit' ? 'Update Employee' : 'Create Employee'

  return (
    <Paper variant="outlined" sx={{ p: 2 }}>
      <Stack
        component="form"
        spacing={2}
        onSubmit={handleSubmit(onSubmit)}
        noValidate
      >
        {error ? <Alert severity="error">{error.message}</Alert> : null}

        <Grid container spacing={2}>
          <Grid size={{ xs: 12, md: 6 }}>
            <Controller
              name="name"
              control={control}
              render={({ field }) => (
                <TextField
                  {...field}
                  label="Name"
                  error={Boolean(errors.name)}
                  helperText={errors.name?.message}
                  fullWidth
                  required
                />
              )}
            />
          </Grid>
          <Grid size={{ xs: 12, md: 6 }}>
            <Controller
              name="email"
              control={control}
              render={({ field }) => (
                <TextField
                  {...field}
                  label="Email"
                  error={Boolean(errors.email)}
                  helperText={errors.email?.message}
                  fullWidth
                  required
                />
              )}
            />
          </Grid>
          <Grid size={{ xs: 12, md: 6 }}>
            <Controller
              name="employee_code"
              control={control}
              render={({ field }) => (
                <TextField
                  {...field}
                  label="Employee Code"
                  error={Boolean(errors.employee_code)}
                  helperText={errors.employee_code?.message}
                  fullWidth
                  required
                />
              )}
            />
          </Grid>
          <Grid size={{ xs: 12, md: 6 }}>
            <Controller
              name="job_title"
              control={control}
              render={({ field }) => (
                <TextField
                  {...field}
                  label="Job Title"
                  error={Boolean(errors.job_title)}
                  helperText={errors.job_title?.message}
                  fullWidth
                  required
                />
              )}
            />
          </Grid>
          <Grid size={{ xs: 12, md: 6 }}>
            <Controller
              name="department_id"
              control={control}
              render={({ field }) => (
                <TextField
                  {...field}
                  select
                  label="Department"
                  error={Boolean(errors.department_id)}
                  helperText={
                    errors.department_id?.message ??
                    (options.error ? 'Unable to load departments' : '')
                  }
                  fullWidth
                  required
                  disabled={options.isLoading}
                >
                  <MenuItem value="">Select department</MenuItem>
                  {departments.map((department) => (
                    <MenuItem key={department.id} value={department.id}>
                      {department.name}
                    </MenuItem>
                  ))}
                </TextField>
              )}
            />
          </Grid>
          <Grid size={{ xs: 12, md: 6 }}>
            <Controller
              name="country_id"
              control={control}
              render={({ field }) => (
                <TextField
                  {...field}
                  select
                  label="Country"
                  error={Boolean(errors.country_id)}
                  helperText={
                    errors.country_id?.message ??
                    (options.error ? 'Unable to load countries' : '')
                  }
                  fullWidth
                  required
                  disabled={options.isLoading}
                >
                  <MenuItem value="">Select country</MenuItem>
                  {countries.map((country) => (
                    <MenuItem key={country.id} value={country.id}>
                      {country.name}
                    </MenuItem>
                  ))}
                </TextField>
              )}
            />
          </Grid>
          <Grid size={{ xs: 12, md: 6 }}>
            <Controller
              name="salary"
              control={control}
              render={({ field }) => (
                <TextField
                  {...field}
                  label="Salary"
                  type="number"
                  error={Boolean(errors.salary)}
                  helperText={errors.salary?.message}
                  fullWidth
                  required
                />
              )}
            />
          </Grid>
          <Grid size={{ xs: 12, md: 6 }}>
            <Controller
              name="joining_date"
              control={control}
              render={({ field }) => (
                <TextField
                  {...field}
                  label="Joining Date"
                  type="date"
                  error={Boolean(errors.joining_date)}
                  helperText={errors.joining_date?.message}
                  fullWidth
                  required
                  slotProps={{ inputLabel: { shrink: true } }}
                />
              )}
            />
          </Grid>
          <Grid size={{ xs: 12, md: 6 }}>
            <Controller
              name="employment_type"
              control={control}
              render={({ field }) => (
                <TextField
                  {...field}
                  select
                  label="Employment Type"
                  error={Boolean(errors.employment_type)}
                  helperText={errors.employment_type?.message}
                  fullWidth
                  required
                >
                  <MenuItem value="full_time">Full time</MenuItem>
                  <MenuItem value="part_time">Part time</MenuItem>
                  <MenuItem value="contract">Contract</MenuItem>
                  <MenuItem value="intern">Intern</MenuItem>
                </TextField>
              )}
            />
          </Grid>
          <Grid size={{ xs: 12, md: 6 }}>
            <Controller
              name="status"
              control={control}
              render={({ field }) => (
                <TextField
                  {...field}
                  select
                  label="Status"
                  error={Boolean(errors.status)}
                  helperText={errors.status?.message}
                  fullWidth
                  required
                >
                  <MenuItem value="active">Active</MenuItem>
                  <MenuItem value="inactive">Inactive</MenuItem>
                </TextField>
              )}
            />
          </Grid>
        </Grid>

        <Stack direction="row" spacing={1} justifyContent="flex-end">
          <Button onClick={onCancel}>Cancel</Button>
          <Button type="submit" variant="contained" disabled={loading}>
            {submitLabel}
          </Button>
        </Stack>
      </Stack>
    </Paper>
  )
}

export default EmployeeForm
