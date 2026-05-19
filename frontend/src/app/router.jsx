import { createBrowserRouter } from 'react-router-dom'
import App from './App'
import DashboardPage from '../pages/DashboardPage'
import EmployeeCreatePage from '../pages/EmployeeCreatePage'
import EmployeeEditPage from '../pages/EmployeeEditPage'
import EmployeesPage from '../pages/EmployeesPage'

export const router = createBrowserRouter([
  {
    path: '/',
    element: <App />,
    children: [
      {
        index: true,
        element: <DashboardPage />,
      },
      {
        path: 'employees',
        element: <EmployeesPage />,
      },
      {
        path: 'employees/create',
        element: <EmployeeCreatePage />,
      },
      {
        path: 'employees/:uuid/edit',
        element: <EmployeeEditPage />,
      },
    ],
  },
])
