# Salary Management

This repository contains the Laravel backend for the Salary Management API.

Backend documentation is maintained here:

```text
backend/README.md
```

Quick start:

```bash
cd backend
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Quality checks:

```bash
composer pint
composer pint:test
composer phpstan
composer test
```
