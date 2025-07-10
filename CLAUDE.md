# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel application built with the Livewire Starter Kit, using:

-   **Laravel 12** with **Livewire** for reactive components
-   **Flux UI** components (Livewire's UI library)
-   **Volt** for single-file Livewire components
-   **Tailwind CSS 4.0** for styling
-   **Vite** for asset building
-   **Pest** for testing
-   **SQLite** database for development

## Development Commands

### Core Development

```bash
# Start development server with all services (preferred)
composer dev

# This runs concurrently:
# - php artisan serve (Laravel server)
# - php artisan queue:listen (Queue worker)
# - php artisan pail (Real-time logs)
# - npm run dev (Vite dev server)

# Individual services
php artisan serve           # Laravel server only
npm run dev                 # Vite dev server only
php artisan queue:listen    # Queue worker only
php artisan pail           # Real-time logs only
```

### Testing

```bash
# Run all tests
composer test
# OR
php artisan test

# Run specific test file
php artisan test tests/Feature/Auth/LoginTest.php

# Run tests with coverage
php artisan test --coverage
```

### Code Quality

```bash
# Format code with Laravel Pint
./vendor/bin/pint

# Run specific migrations
php artisan migrate

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Asset Building

```bash
# Development
npm run dev

# Production build
npm run build
```

## Architecture

### Authentication System

-   **Livewire Components**: Authentication is handled by Livewire components in `app/Livewire/Auth/`
-   **Routes**: Auth routes are defined in `routes/auth.php`
-   **Views**: Auth views use the `components.layouts.auth` layout

### Core Components

-   **Models**: Standard Eloquent models in `app/Models/`
-   **Livewire Components**:
    -   Auth components in `app/Livewire/Auth/`
    -   Settings components in `app/Livewire/Settings/`
    -   Actions in `app/Livewire/Actions/`
-   **Views**: Blade templates in `resources/views/`
    -   Flux UI components in `resources/views/flux/`
    -   Livewire views in `resources/views/livewire/`

### Database

-   **SQLite**: Default database for development (`database/database.sqlite`)
-   **Migrations**: Standard Laravel migrations in `database/migrations/`
-   **Factories**: Model factories in `database/factories/`

### Frontend

-   **Tailwind CSS 4.0**: Configured with `@tailwindcss/vite` plugin
-   **Vite**: Asset bundling with Laravel Vite plugin
-   **Entry Points**: `resources/css/app.css` and `resources/js/app.js`

### Testing

-   **Pest**: PHP testing framework
-   **Feature Tests**: Authentication and settings functionality
-   **Unit Tests**: Basic unit tests structure
-   **Configuration**: PHPUnit configured for SQLite in-memory database

## Key Files

-   `composer.json`: Contains `devv` script for concurrent development servers
-   `vite.config.js`: Asset building configuration
-   `routes/web.php`: Main application routes
-   `routes/auth.php`: Authentication routes
-   `app/Models/User.php`: User model with initials() helper method
