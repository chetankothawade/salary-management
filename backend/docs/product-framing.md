# Product Framing

## Goal

Build a minimal but usable salary management tool for an HR Manager responsible for a 10,000 employee organization.

The product should help HR quickly:

- Maintain employee salary records.
- Find and update employee details.
- Compare salaries across countries, job titles, and departments.
- Spot outliers and compensation distribution patterns.

## Primary User

HR Manager.

The HR Manager needs operational workflows and salary insights more than a marketing-style interface. The product should prioritize fast scanning, clear filters, predictable CRUD behavior, and trustworthy numbers.

## Core Jobs To Be Done

1. Manage employees.
   - Add a new employee.
   - View employee records.
   - Update salary, job title, department, country, employment type, and status.
   - Delete employees when they should no longer appear in active workflows.

2. Understand compensation.
   - See minimum, maximum, and average salary by country.
   - See average salary for a job title in a country.
   - Understand salary distribution across broad compensation bands.
   - Compare departments by headcount and salary range.

3. Work with large datasets.
   - Seed and query 10,000 employees.
   - Keep list endpoints paginated.
   - Avoid loading all employee records into memory for dashboard aggregates.

## Current Product Scope

The application currently provides:

- Employee CRUD APIs.
- Employee list and dropdown APIs.
- Dashboard summary APIs.
- Country salary insights.
- Job title salary insights with country filter.
- Department insights.
- Salary distribution.
- 10,000 employee seeding using first and last name files.
- Feature and unit tests for core API/service behavior.
- React frontend for dashboard and employee management workflows.
- Employee DataGrid with search, filters, sorting, pagination, empty states, and row actions.
- Create/edit employee forms with validation, department/country dropdowns, and toast notifications.
- Dashboard UI for summary metrics, salary distribution, department headcount, country salary insights, and job-title salary insights.

## Product Decisions

### Employee Data

Employee identity is split across `users` and `employees`.

- `users.name` stores full name.
- `employees` stores HR-specific fields: employee code, country, department, job title, salary, employment type, status, and joining date.

This keeps authentication-ready user identity separate from HR employment data.

### UUID API Identifiers

Public API routes use UUIDs for employee detail/update/delete operations. This avoids exposing sequential database IDs to clients.

### Soft Delete

Deleting an employee soft-deletes the employee record and marks status as inactive first. This keeps historical records available while removing deleted employees from normal active workflows.

### Dashboard Aggregates

Dashboard endpoints use SQL aggregate queries instead of fetching all rows. This is important because the target dataset has 10,000 employees and should remain responsive.

## Implemented UI Direction

The frontend is an operational HR dashboard, not a landing page.

Current first screen:

- Top summary metrics: total employees, active employees, average salary.
- Salary distribution chart.
- Country salary insights table/chart.
- Department headcount chart.
- Job title salary insights with country and job-title filters.

Employee management UI:

- Paginated data table.
- Search by name, email, employee code, job title.
- Filters for status, country, department, employment type.
- Create/edit pages with reusable form component.
- Delete confirmation.
- Status toggle action.
- Compact no-data state.

Dashboard UI:

- Country selector.
- Job title selector/search.
- Cards for total employees, active employees, average salary, and salary range.
- Compact charts for salary distribution and department comparison.
- Paginated job-title insight table to avoid very long dashboard pages.

## Success Criteria

- HR can create, update, view, and delete employees.
- HR can answer salary questions by country and job title.
- API remains stable with 10,000 seeded employees.
- Tests cover core CRUD and dashboard calculations.
- Code structure remains easy to extend with future modules.
