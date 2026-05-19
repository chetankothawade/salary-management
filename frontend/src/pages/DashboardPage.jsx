import {
  Alert,
  Box,
  CircularProgress,
  Grid,
  Paper,
  Stack,
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableRow,
  Typography,
} from '@mui/material'
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
  useSalaryDistribution,
} from '../features/dashboard/dashboardHooks'

const formatNumber = (value) =>
  new Intl.NumberFormat('en-US', {
    maximumFractionDigits: 0,
  }).format(Number(value ?? 0))

function DashboardPage() {
  const summary = useDashboardSummary()
  const countries = useCountrySalaryInsights()
  const departments = useDepartmentInsights()
  const distribution = useSalaryDistribution()

  const isLoading =
    summary.isLoading ||
    countries.isLoading ||
    departments.isLoading ||
    distribution.isLoading

  const error =
    summary.error || countries.error || departments.error || distribution.error

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

  const summaryData = summary.data?.data ?? {}
  const countryRows = countries.data?.data ?? []
  const departmentRows = departments.data?.data ?? []
  const salaryBuckets = distribution.data?.data ?? []

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
    </Stack>
  )
}

export default DashboardPage
