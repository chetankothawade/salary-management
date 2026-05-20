# Salary Management Frontend

React + Vite frontend for the Salary Management tool. The UI supports dashboard salary insights, employee listing, search, filtering, pagination, create, edit, delete, and status updates.

## Requirements

- Node.js 20 or higher recommended
- npm
- Running backend API

Run all frontend commands from:

```bash
cd C:\wamp64\www\sma\frontend
```

## Installation

Install dependencies:

```bash
npm install
```

If npm reports peer dependency conflicts for MUI packages, use:

```bash
npm install --legacy-peer-deps
```

## Environment

Create or update `.env`:

```env
VITE_API_BASE_URL=http://127.0.0.1:8000/api
```

The backend should be running at:

```text
http://127.0.0.1:8000
```

## Development Server

Start the Vite dev server:

```bash
npm run dev
```

The project uses:

```json
"dev": "vite --open"
```

This opens the browser automatically when the dev server starts.

Default frontend URL:

```text
http://localhost:5173
```

## Scripts

Run development server:

```bash
npm run dev
```

Run ESLint:

```bash
npm run lint
```

Create production build:

```bash
npm run build
```

Preview production build:

```bash
npm run preview
```

## Project Structure

```text
src/
  api/
    axios.js
    dashboardApi.js
    employeeApi.js
  app/
    App.jsx
    queryClient.js
    router.jsx
    theme.js
  components/
    common/
      MetricCard.jsx
  features/
    dashboard/
      dashboardHooks.js
    employees/
      DeleteEmployeeDialog.jsx
      EmployeeForm.jsx
      EmployeeTable.jsx
      employeeHooks.js
      employeeSchema.js
  pages/
    DashboardPage.jsx
    EmployeeCreatePage.jsx
    EmployeeEditPage.jsx
    EmployeesPage.jsx
```

## Code Structure

The frontend follows a feature-based structure.

API clients:

- Live in `src/api`
- Keep endpoint paths in one place
- Use the shared Axios client from `src/api/axios.js`

React Query hooks:

- Live inside each feature folder
- Own query keys, mutations, cache invalidation, and loading states
- Keep pages focused on UI composition

Pages:

- Live in `src/pages`
- Represent route-level screens
- Compose reusable feature components

Feature components:

- Live in `src/features/{module}`
- Hold module-specific UI such as employee table, form, and dialogs

Shared components:

- Live in `src/components/common`
- Used across multiple pages

## Main Libraries

- React
- Vite
- Material UI
- Material UI DataGrid
- React Router
- TanStack React Query
- Axios
- React Hook Form
- Zod
- React Hot Toast
- Recharts

## Routes

```text
/                  Dashboard
/employees          Employee list
/employees/create   Create employee
/employees/:uuid/edit Edit employee
```

## API Integration

Axios base URL is configured in:

```text
src/api/axios.js
```

The app expects backend responses in this shape:

```json
{
  "status": true,
  "message": "Success message",
  "data": {}
}
```

Paginated employee responses should also include:

```json
{
  "pagination": {
    "total": 100,
    "perPage": 10,
    "currentPage": 1,
    "lastPage": 10
  }
}
```

## Employee Module

Employee list features:

- Server-side pagination
- Server-side sorting
- Search by name, email, employee code, or job title
- Filter by status, employment type, department, and country
- Empty state
- Loading state
- Delete confirmation
- Status toggle

Employee form features:

- Create and edit mode
- React Hook Form
- Zod validation
- Department and country dropdowns from API options
- Submit loading state
- Toast notifications

## Dashboard Module

Dashboard features:

- Total employee summary
- Active/inactive employee summary
- Average salary
- Salary range
- Salary distribution chart
- Department headcount chart
- Country salary insights with min, average, and max salary
- Job title salary insights filtered by country and job title
- Pagination for long job title insight tables

## Quality Checks

Before committing frontend changes, run:

```bash
npm run lint
npm run build
```

## Troubleshooting

If API calls fail, confirm:

- Backend server is running with `php artisan serve`
- `VITE_API_BASE_URL` points to the backend `/api` URL
- Browser dev tools do not show CORS or network errors

If dependency installation fails, retry:

```bash
npm install --legacy-peer-deps
```

If Vite uses a different port because `5173` is busy, use the URL printed in the terminal.
