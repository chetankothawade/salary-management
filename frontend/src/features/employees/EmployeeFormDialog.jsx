import {
  Button,
  Dialog,
  DialogActions,
  DialogContent,
  DialogTitle,
  Grid,
  MenuItem,
  TextField,
} from '@mui/material'
import { useState } from 'react'

const emptyForm = {
  user_id: '',
  employee_code: '',
  department_id: '',
  country_id: '',
  job_title: '',
  salary: '',
  employment_type: 'full_time',
  status: 'active',
  joining_date: '',
}

function mapEmployeeToForm(employee) {
  if (!employee) {
    return emptyForm
  }

  return {
    user_id: employee.user_id ?? '',
    employee_code: employee.employee_code ?? '',
    department_id: employee.department?.id ?? '',
    country_id: employee.country?.id ?? '',
    job_title: employee.job_title ?? '',
    salary: employee.salary ?? '',
    employment_type: employee.employment_type ?? 'full_time',
    status: employee.status ?? 'active',
    joining_date: employee.joining_date ?? '',
  }
}

function EmployeeFormDialog({ open, employee, loading, error, onClose, onSubmit }) {
  const [form, setForm] = useState(() => mapEmployeeToForm(employee))

  const handleChange = (event) => {
    const { name, value } = event.target

    setForm((current) => ({
      ...current,
      [name]: value,
    }))
  }

  const handleSubmit = (event) => {
    event.preventDefault()

    onSubmit({
      ...form,
      user_id: Number(form.user_id),
      department_id: Number(form.department_id),
      country_id: Number(form.country_id),
      salary: Number(form.salary),
    })
  }

  return (
    <Dialog open={open} onClose={onClose} maxWidth="md" fullWidth>
      <DialogTitle>{employee ? 'Edit Employee' : 'Add Employee'}</DialogTitle>
      <DialogContent>
        <Grid
          component="form"
          id="employee-form"
          container
          spacing={2}
          sx={{ pt: 1 }}
          onSubmit={handleSubmit}
        >
          <Grid size={{ xs: 12, md: 6 }}>
            <TextField
              name="user_id"
              label="User ID"
              value={form.user_id}
              onChange={handleChange}
              type="number"
              required
              fullWidth
            />
          </Grid>
          <Grid size={{ xs: 12, md: 6 }}>
            <TextField
              name="employee_code"
              label="Employee Code"
              value={form.employee_code}
              onChange={handleChange}
              required
              fullWidth
            />
          </Grid>
          <Grid size={{ xs: 12, md: 6 }}>
            <TextField
              name="department_id"
              label="Department ID"
              value={form.department_id}
              onChange={handleChange}
              type="number"
              required
              fullWidth
            />
          </Grid>
          <Grid size={{ xs: 12, md: 6 }}>
            <TextField
              name="country_id"
              label="Country ID"
              value={form.country_id}
              onChange={handleChange}
              type="number"
              required
              fullWidth
            />
          </Grid>
          <Grid size={{ xs: 12, md: 6 }}>
            <TextField
              name="job_title"
              label="Job Title"
              value={form.job_title}
              onChange={handleChange}
              required
              fullWidth
            />
          </Grid>
          <Grid size={{ xs: 12, md: 6 }}>
            <TextField
              name="salary"
              label="Salary"
              value={form.salary}
              onChange={handleChange}
              type="number"
              required
              fullWidth
            />
          </Grid>
          <Grid size={{ xs: 12, md: 6 }}>
            <TextField
              select
              name="employment_type"
              label="Employment Type"
              value={form.employment_type}
              onChange={handleChange}
              required
              fullWidth
            >
              <MenuItem value="full_time">Full time</MenuItem>
              <MenuItem value="part_time">Part time</MenuItem>
              <MenuItem value="contract">Contract</MenuItem>
              <MenuItem value="intern">Intern</MenuItem>
            </TextField>
          </Grid>
          <Grid size={{ xs: 12, md: 6 }}>
            <TextField
              select
              name="status"
              label="Status"
              value={form.status}
              onChange={handleChange}
              required
              fullWidth
            >
              <MenuItem value="active">Active</MenuItem>
              <MenuItem value="inactive">Inactive</MenuItem>
            </TextField>
          </Grid>
          <Grid size={{ xs: 12, md: 6 }}>
            <TextField
              name="joining_date"
              label="Joining Date"
              value={form.joining_date}
              onChange={handleChange}
              type="date"
              required
              fullWidth
              slotProps={{ inputLabel: { shrink: true } }}
            />
          </Grid>
          {error ? (
            <Grid size={{ xs: 12 }}>
              <TextField value={error.message} error fullWidth disabled />
            </Grid>
          ) : null}
        </Grid>
      </DialogContent>
      <DialogActions>
        <Button onClick={onClose}>Cancel</Button>
        <Button
          type="submit"
          form="employee-form"
          variant="contained"
          disabled={loading}
        >
          Save
        </Button>
      </DialogActions>
    </Dialog>
  )
}

export default EmployeeFormDialog
