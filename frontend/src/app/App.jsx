import {
  AppBar,
  Box,
  Button,
  Container,
  Toolbar,
  Typography,
} from '@mui/material'
import { Toaster } from 'react-hot-toast'
import { NavLink, Outlet } from 'react-router-dom'

const navItems = [
  { label: 'Dashboard', to: '/' },
  { label: 'Employees', to: '/employees' },
]

function App() {
  return (
    <Box sx={{ minHeight: '100vh', bgcolor: 'background.default' }}>
      <AppBar
        position="sticky"
        color="inherit"
        elevation={0}
        sx={{ borderBottom: '1px solid', borderColor: 'divider' }}
      >
        <Toolbar sx={{ gap: 2 }}>
          <Typography variant="h6" sx={{ flexGrow: 1 }}>
            Salary Management
          </Typography>

          {navItems.map((item) => (
            <Button
              key={item.to}
              component={NavLink}
              to={item.to}
              color="inherit"
              sx={{
                '&.active': {
                  bgcolor: 'primary.main',
                  color: 'primary.contrastText',
                },
              }}
            >
              {item.label}
            </Button>
          ))}
        </Toolbar>
      </AppBar>

      <Container maxWidth="xl" sx={{ py: 3 }}>
        <Outlet />
      </Container>
      <Toaster position="top-right" />
    </Box>
  )
}

export default App
