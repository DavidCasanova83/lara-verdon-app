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
    -   `City`: Model for managing tourist destinations (La Palud-sur-Verdon, Saint-André-les-Alpes, Colmars-les-Alpes, Entrevaux, Annot)
-   **Livewire Components**:
    -   Auth components in `app/Livewire/Auth/`
    -   Settings components in `app/Livewire/Settings/`
    -   Actions in `app/Livewire/Actions/`
    -   **Form Components**: Multi-step tourist information forms
        -   `FormStep1`: Geographic information and optional user details (collapsible section)
        -   `FormStep2`: Age group selection with multiple options
        -   `FormStep3`: Specific and general tourism requests
    -   **Statistics Components**: 
        -   `CityStatistics`: Individual city statistics
        -   `AdvancedStatistics`: Global statistics dashboard
        -   `PostItNotes`: Floating notes system
-   **Views**: Blade templates in `resources/views/`
    -   Flux UI components in `resources/views/flux/`
    -   Livewire views in `resources/views/livewire/`
    -   **Reusable Components**:
        -   `progress-bar`: Multi-step form progress indicator
        -   `selection-button`: Styled selection buttons with accessibility

### Database

-   **SQLite**: Default database for development (`database/database.sqlite`)
-   **Migrations**: Standard Laravel migrations in `database/migrations/`
-   **Factories**: Model factories in `database/factories/`

### Frontend

-   **Tailwind CSS 4.0**: Configured with `@tailwindcss/vite` plugin
-   **Vite**: Asset bundling with Laravel Vite plugin
-   **Entry Points**: `resources/css/app.css` and `resources/js/app.js`
-   **JavaScript Components**:
    -   `confetti.js`: Custom confetti animation system for form success
-   **Alpine.js**: Used for interactive components (collapsible sections, transitions)
-   **Color Scheme**: Consistent teal color theme (#3B9C92) throughout the application

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
-   `app/Models/City.php`: City model for tourism destinations
-   `app/Http/Controllers/CityController.php`: Handles city selection and statistics
-   `resources/views/pages/home.blade.php`: City selection homepage with specific images
-   `public/images/villages/`: Directory containing specific images for each village
-   `resources/js/confetti.js`: Custom confetti animation system

## Tourism Form System

### Multi-Step Forms
The application features a 3-step tourism information form:

1. **Step 1**: Geographic information and optional user details
   - Country selection with "Other" option
   - Department selection (France only) with "Unknown" toggle
   - Collapsible optional section for email and GDPR consent
   - Auto-hide success messages after 5 seconds

2. **Step 2**: Age group selection
   - Multiple age group options with multi-select capability
   - Visual feedback for selected options

3. **Step 3**: Tourism requests
   - City-specific requests (if available)
   - General tourism requests
   - Free text field for additional requests
   - Summary of selected options

### Form Features
- **Progress Bar**: Visual progress indicator across all steps
- **Success Animation**: Confetti animation on successful form submission
- **Auto-Save**: Automatic form data persistence
- **Accessibility**: ARIA labels, keyboard navigation, focus management
- **Responsive Design**: Mobile-first approach with responsive layouts
- **Error Handling**: Comprehensive validation and error display

### Village Images
Each village has a specific image instead of random placeholders:
- La Palud-sur-Verdon: Gorges du Verdon landscape
- Saint-André-les-Alpes: Mountain lake scenery
- Colmars-les-Alpes: Fortified village
- Entrevaux: Medieval architecture
- Annot: Sandstone formations

## Statistics System

### City Statistics
- Individual statistics per city/village
- Login statistics tracking
- Advanced global statistics dashboard
- Real-time data visualization

### Post-It Notes
- Floating notes system for reminders
- Persistent across page navigation
- Admin-configurable content
