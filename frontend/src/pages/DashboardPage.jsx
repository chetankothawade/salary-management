import {
  Alert,
  Box,
  CircularProgress,
  Grid,
  MenuItem,
  Paper,
  Stack,
  Table,
  TableBody,
  TableCell,
  TableHead,
  TablePagination,
  TableRow,
  TextField,
  Typography,
} from '@mui/material'
import { useMemo, useState } from 'react'
import {
  Bar,
  BarChart,
  CartesianGrid,
  ResponsiveContainer,
  Tooltip,
  XAxis,
  YAxis,
} from 'recharts'
import MetricCard from '../components/common/MetricCard'
import {
  useCountrySalaryInsights,
  useDashboardSummary,
  useDepartmentInsights,
  useJobTitleInsights,
  useSalaryDistribution,
} from '../features/dashboard/dashboardHooks'
import { useEmployeeOptions } from '../features/employees/employeeHooks'

const formatNumber = (value) =>
  new Intl.NumberFormat('en-US', {
    maximumFractionDigits: 0,
  }).format(Number(value ?? 0))

function DashboardPage() {
  const [countryId, setCountryId] = useState('')
  const [jobTitle, setJobTitle] = useState('')
  const [jobTitlePage, setJobTitlePage] = useState(0)
  const [jobTitleRowsPerPage, setJobTitleRowsPerPage] = useState(10)
  const summary = useDashboardSummary()
  const countries = useCountrySalaryInsights()
  const departments = useDepartmentInsights()
  const distribution = useSalaryDistribution()
  const jobTitleInsights = useJobTitleInsights(
    countryId ? { country_id: countryId } : {},
  )
  const options = useEmployeeOptions()

  const isLoading =
    summary.isLoading ||
    countries.isLoading ||
    departments.isLoading ||
    distribution.isLoading ||
    jobTitleInsights.isLoading ||
    options.isLoading

  const error =
    summary.error ||
    countries.error ||
    departments.error ||
    distribution.error ||
    jobTitleInsights.error ||
    options.error

  const summaryData = summary.data?.data ?? {}
  const countryRows = countries.data?.data ?? []
  const departmentRows = departments.data?.data ?? []
  const salaryBuckets = distribution.data?.data ?? []
  const optionCountries = options.data?.data?.countries ?? []
  const jobTitles = options.data?.data?.job_titles ?? []
  const filteredJobTitleRows = useMemo(
    () => {
      const rows = jobTitleInsights.data?.data ?? []

      return jobTitle
        ? rows.filter((row) => row.job_title === jobTitle)
        : rows
    },
    [jobTitle, jobTitleInsights.data],
  )
  const paginatedJobTitleRows = useMemo(
    () =>
      filteredJobTitleRows.slice(
        jobTitlePage * jobTitleRowsPerPage,
        jobTitlePage * jobTitleRowsPerPage + jobTitleRowsPerPage,
      ),
    [filteredJobTitleRows, jobTitlePage, jobTitleRowsPerPage],
  )

  if (isLoading) {
    return (
      <Box sx={{ display: 'grid', minHeight: 320, placeItems: 'center' }}>
        <CircularProgress />
      </Box>
    )
  }

  if (error) {
    return <Alert severity="error">{error.message}</Alert>
  }

  return (
    <Stack spacing={3}>
      <Box>
        <Typography variant="h4">Dashboard</Typography>
        <Typography color="text.secondary">
          Salary and headcount overview for HR review.
        </Typography>
      </Box>

      <Grid container spacing={2}>
        <Grid size={{ xs: 12, sm: 6, md: 3 }}>
          <MetricCard
            label="Total Employees"
            value={formatNumber(summaryData.total_employees)}
            helper="All employee records"
          />
        </Grid>
        <Grid size={{ xs: 12, sm: 6, md: 3 }}>
          <MetricCard
            label="Active Employees"
            value={formatNumber(summaryData.active_employees)}
            helper={`${formatNumber(summaryData.inactive_employees)} inactive`}
          />
        </Grid>
        <Grid size={{ xs: 12, sm: 6, md: 3 }}>
          <MetricCard
            label="Average Salary"
            value={formatNumber(summaryData.average_salary)}
            helper="Across all employees"
          />
        </Grid>
        <Grid size={{ xs: 12, sm: 6, md: 3 }}>
          <MetricCard
            label="Salary Range"
            value={`${formatNumber(summaryData.minimum_salary)} - ${formatNumber(
              summaryData.maximum_salary,
            )}`}
            helper="Minimum to maximum"
          />
        </Grid>
      </Grid>

      <Grid container spacing={2}>
        <Grid size={{ xs: 12, lg: 6 }}>
          <Paper variant="outlined" sx={{ p: 2, height: 360 }}>
            <Typography variant="h6" sx={{ mb: 2 }}>
              Salary Distribution
            </Typography>
            <ResponsiveContainer width="100%" height="85%">
              <BarChart data={salaryBuckets}>
                <CartesianGrid strokeDasharray="3 3" />
                <XAxis dataKey="range" />
                <YAxis allowDecimals={false} />
                <Tooltip />
                <Bar dataKey="total" fill="#2563eb" radius={[4, 4, 0, 0]} />
              </BarChart>
            </ResponsiveContainer>
          </Paper>
        </Grid>

        <Grid size={{ xs: 12, lg: 6 }}>
          <Paper variant="outlined" sx={{ p: 2, height: 360 }}>
            <Typography variant="h6" sx={{ mb: 2 }}>
              Department Headcount
            </Typography>
            <ResponsiveContainer width="100%" height="85%">
              <BarChart data={departmentRows.slice(0, 8)}>
                <CartesianGrid strokeDasharray="3 3" />
                <XAxis dataKey="department" tick={{ fontSize: 11 }} />
                <YAxis allowDecimals={false} />
                <Tooltip />
                <Bar
                  dataKey="total_employees"
                  fill="#0f766e"
                  radius={[4, 4, 0, 0]}
                />
              </BarChart>
            </ResponsiveContainer>
          </Paper>
        </Grid>
      </Grid>

      <Paper variant="outlined">
        <Box sx={{ p: 2 }}>
          <Typography variant="h6">Country Salary Insights</Typography>
          <Typography variant="body2" color="text.secondary">
            Minimum, average, and maximum salary for each country.
          </Typography>
        </Box>
        <Table size="small">
          <TableHead>
            <TableRow>
              <TableCell>Country</TableCell>
              <TableCell align="right">Employees</TableCell>
              <TableCell align="right">Min Salary</TableCell>
              <TableCell align="right">Avg Salary</TableCell>
              <TableCell align="right">Max Salary</TableCell>
            </TableRow>
          </TableHead>
          <TableBody>
            {countryRows.map((row) => (
              <TableRow key={row.country}>
                <TableCell>{row.country}</TableCell>
                <TableCell align="right">{row.total_employees}</TableCell>
                <TableCell align="right">
                  {formatNumber(row.minimum_salary)}
                </TableCell>
                <TableCell align="right">
                  {formatNumber(row.average_salary)}
                </TableCell>
                <TableCell align="right">
                  {formatNumber(row.maximum_salary)}
                </TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </Paper>

      <Paper variant="outlined">
        <Stack
          direction={{ xs: 'column', md: 'row' }}
          spacing={2}
          alignItems={{ xs: 'stretch', md: 'center' }}
          justifyContent="space-between"
          sx={{ p: 2 }}
        >
          <Box>
            <Typography variant="h6">Job Title Salary Insights</Typography>
            <Typography variant="body2" color="text.secondary">
              Average salary for a selected job title in a country.
            </Typography>
          </Box>
          <Stack direction={{ xs: 'column', sm: 'row' }} spacing={2}>
            <TextField
              select
              label="Country"
              size="small"
              value={countryId}
              onChange={(event) => {
                setCountryId(event.target.value)
                setJobTitlePage(0)
              }}
              sx={{ minWidth: 220 }}
            >
              <MenuItem value="">All countries</MenuItem>
              {optionCountries.map((country) => (
                <MenuItem key={country.id} value={country.id}>
                  {country.name}
                </MenuItem>
              ))}
            </TextField>
            <TextField
              select
              label="Job title"
              size="small"
              value={jobTitle}
              onChange={(event) => {
                setJobTitle(event.target.value)
                setJobTitlePage(0)
              }}
              sx={{ minWidth: 240 }}
            >
              <MenuItem value="">All job titles</MenuItem>
              {jobTitles.map((title) => (
                <MenuItem key={title} value={title}>
                  {title}
                </MenuItem>
              ))}
            </TextField>
          </Stack>
        </Stack>
        <Table size="small">
          <TableHead>
            <TableRow>
              <TableCell>Country</TableCell>
              <TableCell>Job Title</TableCell>
              <TableCell align="right">Employees</TableCell>
              <TableCell align="right">Min Salary</TableCell>
              <TableCell align="right">Avg Salary</TableCell>
              <TableCell align="right">Max Salary</TableCell>
            </TableRow>
          </TableHead>
          <TableBody>
            {paginatedJobTitleRows.length > 0 ? (
              paginatedJobTitleRows.map((row) => (
                <TableRow key={`${row.country}-${row.job_title}`}>
                  <TableCell>{row.country}</TableCell>
                  <TableCell>{row.job_title}</TableCell>
                  <TableCell align="right">{row.total_employees}</TableCell>
                  <TableCell align="right">
                    {formatNumber(row.minimum_salary)}
                  </TableCell>
                  <TableCell align="right">
                    {formatNumber(row.average_salary)}
                  </TableCell>
                  <TableCell align="right">
                    {formatNumber(row.maximum_salary)}
                  </TableCell>
                </TableRow>
              ))
            ) : (
              <TableRow>
                <TableCell colSpan={6} align="center">
                  No salary insight found for the selected filters.
                </TableCell>
              </TableRow>
            )}
          </TableBody>
        </Table>
        <TablePagination
          component="div"
          count={filteredJobTitleRows.length}
          page={jobTitlePage}
          rowsPerPage={jobTitleRowsPerPage}
          rowsPerPageOptions={[10, 25, 50]}
          onPageChange={(_, nextPage) => setJobTitlePage(nextPage)}
          onRowsPerPageChange={(event) => {
            setJobTitleRowsPerPage(Number(event.target.value))
            setJobTitlePage(0)
          }}
        />
      </Paper>
    </Stack>
  )
}

export default DashboardPage
