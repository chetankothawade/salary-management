# AI Usage

## Purpose

AI was used as an acceleration tool for implementation, review, test coverage, and documentation. Engineering decisions were still validated through code inspection, tests, static analysis, and manual reasoning.

## How AI Was Used

### Code Structure

AI helped shape the backend into a consistent Laravel API structure:

- Thin controllers.
- Request validation classes.
- Service-layer business logic.
- Resource-based response shaping.
- Enum-backed fixed values.
- Reusable API response trait.

### Implementation

AI assisted with:

- Employee CRUD API.
- Dashboard aggregate APIs.
- Seeder improvements for 10,000 employees.
- Middleware grouping.
- Composer scripts for Pint and PHPStan.
- Postman collection generation.

### Testing

AI helped identify weak placeholder tests and replace them with:

- Feature tests for API behavior.
- Unit tests for service logic.
- Deterministic dashboard calculation tests.
- Seeder count and rerun sanity tests.

### Documentation

AI helped prepare:

- README instructions.
- Product framing.
- Architecture notes.
- Tradeoff documentation.
- API/Postman usage notes.

## Quality Controls

AI-generated changes were checked with:

```bash
composer pint:test
composer phpstan
php artisan test
```

The project currently has:

- Feature tests for API endpoints.
- Unit tests for service logic.
- Static analysis through PHPStan/Larastan.
- Formatting through Laravel Pint.

## Human Review Focus

The main review points were:

- Whether the backend satisfies the HR Manager use case.
- Whether dashboard numbers are deterministic and testable.
- Whether seeding is practical for 10,000 employees.
- Whether comments explain important logic without adding noise.
- Whether code structure is maintainable for future modules.

## Limitations

AI was not treated as a source of truth. Outputs required verification because:

- Laravel version behavior can differ.
- SQLite and MySQL can serialize decimal values differently.
- Windows filesystem behavior can affect PHPStan and Blade cache writes.
- Generated tests can be too shallow unless explicitly reviewed.

## Suggested Prompting Pattern Used

Useful prompts followed this shape:

```text
Inspect the existing code style first.
Implement the change using the current structure.
Keep edits scoped.
Add tests for the core behavior.
Run Pint, PHPStan, and tests.
Summarize what changed and what remains.
```

This kept AI output aligned with the existing project rather than producing disconnected code.
