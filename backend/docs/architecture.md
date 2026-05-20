# Architecture

## Overview

The application has two main parts:

- Laravel backend API.
- React + Vite frontend UI.

The backend is organized around a controller-request-service-resource pattern.

```text
Request -> Controller -> Service -> Model/Database -> Resource -> API Response
```

This keeps responsibilities clear:

- Requests validate input.
- Controllers coordinate HTTP flow.
- Services hold business logic and query composition.
- Models represent database entities and relationships.
- Resources shape API output.
- Traits provide reusable response and UUID behavior.

The frontend is organized by feature and consumes the backend through Axios and TanStack React Query.

```text
Page -> Feature Component -> React Query Hook -> API Client -> Backend API
```

## Directory Structure

Backend:

```text
app/
  Enums/
  Http/
    Controllers/Api/
    Middleware/
    Requests/Api/
    Resources/
  Models/
  Services/
  Traits/
database/
  migrations/
  seeders/
  seeders/data/
routes/
  api.php
tests/
  Feature/
  Unit/
```

Frontend:

```text
frontend/src/
  api/
  app/
  components/common/
  features/
    dashboard/
    employees/
  pages/
```

## Main Modules

### Employee

Files:

```text
app/Http/Controllers/Api/EmployeeController.php
app/Http/Requests/Api/Employee/EmployeeIndexRequest.php
app/Http/Requests/Api/Employee/EmployeeStoreRequest.php
app/Http/Requests/Api/Employee/EmployeeUpdateRequest.php
app/Http/Resources/EmployeeResource.php
app/Services/EmployeeService.php
app/Models/Employee.php
```

Responsibilities:

- CRUD operations.
- Search, filtering, sorting, pagination.
- UUID lookup.
- Soft delete and active/inactive status toggle.
- Lightweight dropdown list for UI controls.

### Dashboard

Files:

```text
app/Http/Controllers/Api/DashboardController.php
app/Http/Requests/Api/Dashboard/JobTitleInsightsRequest.php
app/Services/DashboardService.php
```

Responsibilities:

- Summary metrics.
- Salary insights by country.
- Salary insights by job title and country.
- Department insights.
- Salary distribution.

### Frontend Employee Management

Files:

```text
frontend/src/pages/EmployeesPage.jsx
frontend/src/pages/EmployeeCreatePage.jsx
frontend/src/pages/EmployeeEditPage.jsx
frontend/src/features/employees/EmployeeTable.jsx
frontend/src/features/employees/EmployeeForm.jsx
frontend/src/features/employees/DeleteEmployeeDialog.jsx
frontend/src/features/employees/employeeHooks.js
frontend/src/features/employees/employeeSchema.js
frontend/src/api/employeeApi.js
```

Responsibilities:

- Paginated employee table.
- Search and filters for HR lookup workflows.
- Create and edit forms.
- Zod validation.
- Delete confirmation.
- Status toggle.
- Empty and loading states.

### Frontend Dashboard

Files:

```text
frontend/src/pages/DashboardPage.jsx
frontend/src/features/dashboard/dashboardHooks.js
frontend/src/api/dashboardApi.js
frontend/src/components/common/MetricCard.jsx
```

Responsibilities:

- Summary metric cards.
- Salary distribution chart.
- Department headcount chart.
- Country salary insight table.
- Job title salary insight table with filters and pagination.

## Database Model

Main tables:

- `users`
- `countries`
- `departments`
- `employees`

Relationships:

```text
User hasOne Employee
Employee belongsTo User
Employee belongsTo Country
Employee belongsTo Department
Country hasMany Employees
Department hasMany Employees
```

## API Response Standard

All API responses use `App\Traits\ApiResponse`.

Success:

```json
{
  "status": true,
  "message": "Employees retrieved successfully.",
  "data": []
}
```

Paginated:

```json
{
  "status": true,
  "message": "Employees retrieved successfully.",
  "data": [],
  "pagination": {
    "total": 10000,
    "perPage": 10,
    "currentPage": 1,
    "lastPage": 1000,
    "from": 1,
    "to": 10
  }
}
```

Validation errors are handled by Laravel exception rendering in `bootstrap/app.php`.

The frontend Axios client unwraps successful API responses and normalizes failed responses into:

```json
{
  "message": "Unable to complete the request.",
  "errors": {},
  "status": 422
}
```

## Validation

Request classes live under:

```text
app/Http/Requests/Api
```

Examples:

- `EmployeeStoreRequest`
- `EmployeeUpdateRequest`
- `EmployeeIndexRequest`
- `JobTitleInsightsRequest`

`EmployeeUpdateRequest` treats:

- `PUT` as full replacement.
- `PATCH` as partial update.

## Middleware

Registered in:

```text
bootstrap/app.php
```

Current API throttling middleware:

- `ip.throttle`
- `burst.throttle`
- `role.throttle`
- `token.throttle`

Employee and dashboard routes currently do not require authentication.

## Seeding Architecture

The `EmployeeSeeder` is designed for regular local reruns.

Key points:

- Creates 10,000 employees.
- Generates full names from `first_names.txt` and `last_names.txt`.
- Inserts records in chunks.
- Precomputes the shared password hash once.
- Uses deterministic seeded emails and employee codes.
- Cleans up only records generated by the seeder before reseeding.
- Fetches inserted users by generated UUIDs, not by latest IDs.

## Testing Strategy

Feature tests cover HTTP behavior:

- Employee CRUD.
- Validation errors.
- Dashboard endpoints.
- Seeder count/rerun sanity.

Unit tests cover service behavior:

- Employee service create/update/delete/search/toggle/list.
- Dashboard service aggregate calculations.

Test database:

```text
SQLite in memory
```

Configured in `phpunit.xml`.

## Performance Considerations

- Employee list is paginated.
- Dashboard calculations use SQL aggregate queries.
- Seeder uses chunked inserts.
- Search includes employee fields plus linked user name/email.
- Dropdown endpoint returns a smaller payload than full employee listing.
- Frontend employee table uses server-side pagination and sorting.
- Job title dashboard insights use client-side pagination to keep the dashboard readable.

## Extension Points

To add a new module:

1. Create migration/model.
2. Add enums for fixed values.
3. Add request validation.
4. Add service methods.
5. Add resource output.
6. Add API controller.
7. Register routes.
8. Add language messages.
9. Add feature and unit tests.
