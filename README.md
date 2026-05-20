# Salary Management

End-to-end salary management application for HR teams. The project contains a Laravel backend API and a React + Vite frontend UI.

## Applications

```text
backend/       Laravel API, database, seeders, tests, Postman collection
backend/docs/  Product, architecture, AI usage, and trade-off notes
frontend/      React UI, dashboard, employee management screens
```

Detailed documentation:

```text
backend/README.md
frontend/README.md
```

## Requirements

- PHP 8.3 or higher
- Composer
- MySQL or SQLite
- Node.js 20 or higher recommended
- npm

## Backend Quick Start

```bash
cd backend
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Default backend URL:

```text
http://127.0.0.1:8000
```

API prefix:

```text
/api
```

## Frontend Quick Start

```bash
cd frontend
npm install
```

Create or update `frontend/.env`:

```env
VITE_API_BASE_URL=http://127.0.0.1:8000/api
```

Start the frontend:

```bash
npm run dev
```

The dev script uses `vite --open`, so the browser opens automatically.

Default frontend URL:

```text
http://localhost:5173
```

## Running the Full App Locally

Terminal 1:

```bash
cd backend
php artisan serve
```

Terminal 2:

```bash
cd frontend
npm run dev
```

Open:

```text
http://localhost:5173
```

## Backend Quality Checks

```bash
cd backend
composer pint
composer pint:test
composer phpstan
composer test
```

## Frontend Quality Checks

```bash
cd frontend
npm run lint
npm run build
```

## Main Features

Backend:

- Employee CRUD API
- Employee search, sorting, filters, and pagination
- Dashboard salary insights API
- Country salary min, average, and max calculations
- Job title salary insights by country
- Department and salary distribution insights
- Seeders for 10,000 employees
- PHPUnit tests and PHPStan configuration

Frontend:

- Dashboard summary cards
- Salary distribution and department charts
- Country salary insights table
- Job title salary insights with filters and pagination
- Employee DataGrid with search, filters, sorting, and pagination
- Create/edit employee forms with React Hook Form and Zod validation
- Delete confirmation and toast notifications

## Key Frontend Commands

Install dependencies:

```bash
cd frontend
npm install
```

Run UI:

```bash
npm run dev
```

Lint:

```bash
npm run lint
```

Build:

```bash
npm run build
```

## Key Backend Commands

Run migrations and seeders:

```bash
cd backend
php artisan migrate:fresh --seed
```

Run tests:

```bash
composer test
```

List API routes:

```bash
php artisan route:list
```

## API Documentation

Postman collection:

```text
backend/postman/Salary_Management_API.postman_collection.json
```

Important API groups:

```text
GET    /api/employees
POST   /api/employees
GET    /api/employees/{uuid}
PUT    /api/employees/{uuid}
PATCH  /api/employees/{uuid}
DELETE /api/employees/{uuid}

GET    /api/dashboard/summary
GET    /api/dashboard/country-salary-insights
GET    /api/dashboard/job-title-insights
GET    /api/dashboard/department-insights
GET    /api/dashboard/salary-distribution
```

## Development Notes

- Keep backend controllers thin and delegate business logic to services.
- Keep validation in request classes.
- Keep frontend API calls in `src/api`.
- Keep React Query hooks inside feature folders.
- Run backend and frontend quality checks before commits.
- Commit incrementally so implementation progress is easy to review.
