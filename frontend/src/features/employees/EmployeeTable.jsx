import DeleteIcon from '@mui/icons-material/Delete'
import EditIcon from '@mui/icons-material/Edit'
import ToggleOffIcon from '@mui/icons-material/ToggleOff'
import ToggleOnIcon from '@mui/icons-material/ToggleOn'
import { Box, Chip, IconButton, Stack, Tooltip, Typography } from '@mui/material'
import { DataGrid } from '@mui/x-data-grid'

const statusColor = {
  active: 'success',
  inactive: 'default',
}

const formatSalary = (value) =>
  new Intl.NumberFormat('en-US', {
    maximumFractionDigits: 0,
  }).format(Number(value ?? 0))

function EmptyState() {
  return (
    <Stack alignItems="center" justifyContent="center" sx={{ height: '100%' }}>
      <Typography variant="subtitle1">No employees found</Typography>
      <Typography variant="body2" color="text.secondary">
        Try adjusting the search or filters.
      </Typography>
    </Stack>
  )
}

function EmployeeTable({
  rows,
  rowCount,
  loading,
  paginationModel,
  onPaginationModelChange,
  sortModel,
  onSortModelChange,
  onEdit,
  onDelete,
  onToggleStatus,
}) {
  const columns = [
    {
      field: 'employee_code',
      headerName: 'Code',
      width: 150,
    },
    {
      field: 'full_name',
      headerName: 'Name',
      flex: 1,
      minWidth: 180,
    },
    {
      field: 'job_title',
      headerName: 'Job Title',
      flex: 1,
      minWidth: 200,
    },
    {
      field: 'country',
      headerName: 'Country',
      minWidth: 160,
      valueGetter: (_, row) => row.country?.name ?? '-',
    },
    {
      field: 'employment_type',
      headerName: 'Type',
      minWidth: 140,
      valueFormatter: (value) => String(value ?? '').replace('_', ' '),
    },
    {
      field: 'salary',
      headerName: 'Salary',
      type: 'number',
      minWidth: 140,
      align: 'right',
      headerAlign: 'right',
      valueFormatter: (value) => formatSalary(value),
    },
    {
      field: 'status',
      headerName: 'Status',
      minWidth: 130,
      renderCell: (params) => (
        <Chip
          label={params.value}
          size="small"
          color={statusColor[params.value] ?? 'default'}
        />
      ),
    },
    {
      field: 'actions',
      headerName: 'Actions',
      sortable: false,
      filterable: false,
      disableColumnMenu: true,
      width: 150,
      align: 'right',
      headerAlign: 'right',
      renderCell: (params) => (
        <Stack
          direction="row"
          spacing={0.5}
          justifyContent="flex-end"
          sx={{ width: '100%' }}
        >
          <Tooltip title="Edit employee">
            <IconButton
              size="small"
              color="primary"
              onClick={() => onEdit(params.row)}
              sx={{ bgcolor: 'action.hover' }}
            >
              <EditIcon fontSize="small" />
            </IconButton>
          </Tooltip>
          <Tooltip title="Toggle status">
            <IconButton
              size="small"
              color={params.row.status === 'active' ? 'success' : 'default'}
              onClick={() => onToggleStatus(params.row)}
            >
              {params.row.status === 'active' ? (
                <ToggleOnIcon fontSize="small" />
              ) : (
                <ToggleOffIcon fontSize="small" />
              )}
            </IconButton>
          </Tooltip>
          <Tooltip title="Delete employee">
            <IconButton
              size="small"
              color="error"
              onClick={() => onDelete(params.row)}
            >
              <DeleteIcon fontSize="small" />
            </IconButton>
          </Tooltip>
        </Stack>
      ),
    },
  ]

  return (
    <Box sx={{ height: 650, width: '100%' }}>
      <DataGrid
        rows={rows}
        columns={columns}
        getRowId={(row) => row.uuid}
        loading={loading}
        rowCount={rowCount}
        paginationMode="server"
        sortingMode="server"
        paginationModel={paginationModel}
        onPaginationModelChange={onPaginationModelChange}
        sortModel={sortModel}
        onSortModelChange={onSortModelChange}
        pageSizeOptions={[10, 25, 50, 100]}
        disableRowSelectionOnClick
        rowHeight={56}
        columnHeaderHeight={52}
        slots={{
          noRowsOverlay: EmptyState,
        }}
        sx={{
          border: 0,
          color: 'text.primary',
          '& .MuiDataGrid-columnHeaderTitle': {
            fontWeight: 700,
          },
          '& .MuiDataGrid-columnHeaders': {
            bgcolor: 'grey.50',
          },
          '& .MuiDataGrid-cell': {
            alignItems: 'center',
          },
          '& .MuiDataGrid-row:hover': {
            bgcolor: 'action.hover',
          },
        }}
      />
    </Box>
  )
}

export default EmployeeTable
