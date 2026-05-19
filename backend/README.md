# Salary Management API

Laravel backend API for salary management, employee CRUD, dashboard analytics, and rate-limited API access.

## Requirements

- PHP 8.3 or higher
- Composer
- MySQL or SQLite
- Node.js and npm, only required for Laravel asset tooling
- WAMP/XAMPP or another local PHP environment

The project is currently located at:

```bash
C:\wamp64\www\sma\backend
```

Run all backend commands from the `backend` directory unless stated otherwise.

## Installation

```bash
cd C:\wamp64\www\sma\backend
composer install
copy .env.example .env
php artisan key:generate
```

Update `.env` database settings.

SQLite example:

```env
DB_CONNECTION=sqlite
```

MySQL example:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sma
DB_USERNAME=root
DB_PASSWORD=
```

Then run:

```bash
php artisan migrate
php artisan db:seed
```

## Development Server

```bash
php artisan serve
```

Default local URL:

```text
http://127.0.0.1:8000
```

API routes are prefixed with:

```text
/api
```

## Composer Commands

Code formatting:

```bash
composer pint
```

Check formatting without changing files:

```bash
composer pint:test
```

Run PHPStan/Larastan static analysis:

```bash
composer phpstan
```

Run PHPStan with higher memory limit:

```bash
composer phpstan:generate
```

Run tests:

```bash
composer test
```

Validate Composer configuration:

```bash
composer validate --no-check-publish
```

## Code Quality

This project uses:

- Laravel Pint for formatting
- PHPStan with Larastan for static analysis
- PHPUnit for tests

Configuration files:

- `pint.json`
- `phpstan.neon`
- `phpunit.xml`

Before committing backend changes, run:

```bash
composer pint
composer pint:test
composer phpstan
composer test
```

## Design Artifacts

Additional assessment artifacts are available in:

```text
docs/product-framing.md
docs/architecture.md
docs/ai-usage.md
docs/tradeoffs.md
```

## Project Structure

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
bootstrap/
database/
  migrations/
  seeders/
resources/
  lang/en/messages.php
routes/
  api.php
tests/
```

## Code Standard Followed

The API code follows a controller-request-service-resource structure.

Controllers:

- Live in `app/Http/Controllers/Api`
- Stay thin
- Validate using request classes
- Delegate business/query logic to services
- Return API responses using `App\Traits\ApiResponse`

Services:

- Live in `app/Services`
- Hold query and business logic
- Use transactions for create, update, delete, and status changes when needed

Requests:

- Live in `app/Http/Requests/Api/{Module}`
- Extend `App\Http\Requests\Api\BaseApiRequest`
- Contain validation rules and filter helpers

Resources:

- Live in `app/Http/Resources`
- Shape API response data
- Hide internal model details when needed

Enums:

- Live in `app/Enums`
- Used for fixed values such as employee status, employment type, and user role
- Validation should reference enum values instead of hard-coded duplicate lists

Traits:

- `ApiResponse` standardizes JSON response shape
- `HasUuid` auto-generates UUID values on model creation

## API Response Format

Success response:

```json
{
  "status": true,
  "message": "Employees retrieved successfully.",
  "data": []
}
```

Paginated response:

```json
{
  "status": true,
  "message": "Employees retrieved successfully.",
  "data": [],
  "pagination": {
    "total": 100,
    "perPage": 10,
    "currentPage": 1,
    "lastPage": 10,
    "from": 1,
    "to": 10
  }
}
```

Error response:

```json
{
  "status": false,
  "message": "Validation failed. Please check the submitted information.",
  "errors": {}
}
```

Messages are stored in:

```text
resources/lang/en/messages.php
```

## Middleware

Middleware aliases are registered in:

```text
bootstrap/app.php
```

Current API throttling middleware:

- `ip.throttle`
- `burst.throttle`
- `role.throttle`
- `token.throttle`

Employee and dashboard APIs currently do not require authentication. They are grouped with throttling middleware only.

## API Routes

View routes:

```bash
php artisan route:list
php artisan route:list --path=employees
php artisan route:list --path=dashboard
```

## Postman Collection

Import this collection into Postman:

```text
postman/Salary_Management_API.postman_collection.json
```

Collection variables:

- `base_url`
- `api_prefix`
- `bearer_token`
- `employee_uuid`
- `user_id`
- `department_id`
- `country_id`
- `employee_code`
- `per_page`

The create employee request stores `employee_uuid` from the response automatically when the API returns `data.uuid`.

### Employee APIs

Base path:

```text
/api/employees
```

List employees:

```http
GET /api/employees
```

Query parameters:

```text
page
search
status
employment_type
department_id
country_id
sortedField
sortedBy
perPage
```

Allowed `status` values:

```text
active
inactive
```

Allowed `employment_type` values:

```text
full_time
part_time
contract
intern
```

Create employee:

```http
POST /api/employees
```

Payload:

```json
{
  "user_id": 1,
  "employee_code": "EMP00001",
  "department_id": 1,
  "country_id": 1,
  "job_title": "Software Engineer",
  "salary": 75000,
  "employment_type": "full_time",
  "status": "active",
  "joining_date": "2026-05-18"
}
```

Show employee:

```http
GET /api/employees/{uuid}
```

Update employee:

```http
PUT /api/employees/{uuid}
PATCH /api/employees/{uuid}
```

Delete employee:

```http
DELETE /api/employees/{uuid}
```

Toggle employee active/inactive:

```http
PATCH /api/employees/{uuid}/active
```

Employee dropdown/list:

```http
GET /api/employees/list
```

### Dashboard APIs

Dashboard summary:

```http
GET /api/dashboard/summary
```

Country salary insights:

```http
GET /api/dashboard/country-salary-insights
```

Job title salary insights:

```http
GET /api/dashboard/job-title-insights
```

Optional query parameter:

```text
country_id
```

Department insights:

```http
GET /api/dashboard/department-insights
```

Salary distribution:

```http
GET /api/dashboard/salary-distribution
```

## Database

Main tables:

- `users`
- `countries`
- `departments`
- `employees`

Useful commands:

```bash
php artisan migrate
php artisan migrate:fresh
php artisan db:seed
php artisan migrate:fresh --seed
```

Seeders:

- `CountrySeeder`
- `DepartmentSeeder`
- `EmployeeSeeder`
- `DatabaseSeeder`

## Employee Module Files

```text
app/Http/Controllers/Api/EmployeeController.php
app/Http/Requests/Api/Employee/EmployeeIndexRequest.php
app/Http/Requests/Api/Employee/EmployeeStoreRequest.php
app/Http/Requests/Api/Employee/EmployeeUpdateRequest.php
app/Http/Resources/EmployeeResource.php
app/Services/EmployeeService.php
app/Models/Employee.php
app/Enums/EmployeeEmploymentType.php
app/Enums/EmployeeStatus.php
```

## Dashboard Module Files

```text
app/Http/Controllers/Api/DashboardController.php
app/Http/Requests/Api/Dashboard/JobTitleInsightsRequest.php
app/Services/DashboardService.php
```

## Adding a New API Module

Use this structure:

```text
app/Http/Controllers/Api/{Module}Controller.php
app/Http/Requests/Api/{Module}/{Module}IndexRequest.php
app/Http/Requests/Api/{Module}/{Module}StoreRequest.php
app/Http/Requests/Api/{Module}/{Module}UpdateRequest.php
app/Http/Resources/{Module}Resource.php
app/Services/{Module}Service.php
app/Models/{Module}.php
app/Enums/{Module}Status.php
```

Recommended flow:

1. Create migration and model.
2. Add enum classes for fixed values.
3. Add request classes for validation.
4. Add service class for business logic.
5. Add resource class for response shape.
6. Add controller methods using `ApiResponse`.
7. Register routes in `routes/api.php`.
8. Add response messages in `resources/lang/en/messages.php`.
9. Run Pint, PHPStan, and tests.

## Windows Notes

If PHPStan cache files under `.phpstan-cache`, `.phpstan-tmp`, or `storage/phpstan` are locked, close running PHP processes or restart the terminal/IDE.

The PHPStan cache folders are ignored by Git.

If Xdebug logs show this warning:

```text
Xdebug: [Log Files] File 'c:/wamp64/logs/xdebug.log' could not be opened.
```

It does not usually block Composer, Pint, or PHPStan execution. Fix the Xdebug log path if you want clean command output.

## Troubleshooting

Clear Laravel caches:

```bash
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

Regenerate autoload files:

```bash
composer dump-autoload
```

Check route registration:

```bash
php artisan route:list
```

Check syntax for a file:

```bash
php -l app/Services/EmployeeService.php
```
