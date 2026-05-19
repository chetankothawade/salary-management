import {
  Button,
  Dialog,
  DialogActions,
  DialogContent,
  DialogContentText,
  DialogTitle,
} from '@mui/material'

function DeleteEmployeeDialog({ open, employee, loading, onClose, onConfirm }) {
  return (
    <Dialog open={open} onClose={onClose} maxWidth="xs" fullWidth>
      <DialogTitle>Delete employee</DialogTitle>
      <DialogContent>
        <DialogContentText>
          Delete {employee?.full_name ?? 'this employee'}? This removes the
          record from normal employee lists.
        </DialogContentText>
      </DialogContent>
      <DialogActions>
        <Button onClick={onClose}>Cancel</Button>
        <Button
          color="error"
          variant="contained"
          disabled={loading}
          onClick={onConfirm}
        >
          Delete
        </Button>
      </DialogActions>
    </Dialog>
  )
}

export default DeleteEmployeeDialog