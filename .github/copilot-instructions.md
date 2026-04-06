# Project Guidelines

## Code Style

Follow Laravel conventions:

- Models: Singular nouns (e.g., `FixedAsset`, `Department`)
- Controllers: PascalCase with "Controller" suffix (e.g., `MasterListController`)
- Routes: Kebab-case (e.g., `/asset-list`, `/department-list`)
- Database columns: Snake_case (e.g., `asset_no`, `capitalization_date`)
- Foreign keys: `table_singular_id` (e.g., `department_id`)

Reference: [Laravel Coding Standards](https://laravel.com/docs/10.x/contributions#coding-style)

## Architecture

Laravel 12 application with Livewire 3.6.4 for reactive components and Volt 1.7.0 for single-file functional components. Fixed Asset Management System (FAMES) for tracking company assets, departments, and financial evaluations.

Key components:

- **Models** (`app/Models/`): 11 models including `FixedAsset`, `Department`, `AssetCategory`
- **Controllers** (`app/Http/Controllers/`): Handle CRUD operations for master data and evaluations
- **Views** (`resources/views/`): Organized by feature (master_list/, fixed_assets/, auth/)
- **Migrations** (`database/migrations/`): 15 migrations defining schema with relationships

## Build and Test

```bash
# Setup
composer install
npm install
php artisan key:generate
php artisan migrate

# Development
composer dev          # Concurrent: serve, queue:listen, npm run dev
php artisan serve     # Laravel dev server (port 8000)
npm run dev           # Vite asset bundler

# Testing
composer test         # Run Pest tests with config cache clear
```

Tests use Pest framework with RefreshDatabase trait and SQLite in-memory database.

## Conventions

- **Mass Assignment**: Models use `protected $guarded = ['id']` for flexible assignment
- **Controller Patterns**: `list` methods fetch and render data; `save` methods handle POST with manual property assignment from request input
- **Relationships**: Standard Eloquent `belongsTo()` and `hasMany()`; FixedAsset belongs to Department, Category, Location, Classification
- **Authentication**: All routes except auth pages require `auth` middleware
- **Database**: Virtual columns (e.g., `serial_number`) computed from other fields; timestamps auto-managed
- **Forms**: Minimal Livewire forms; complex forms use direct input handling in controllers
