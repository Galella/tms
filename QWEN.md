# TMS (Task Management System) - Laravel Project

## Project Overview

This is a Laravel-based Task Management System (TMS) built with Laravel 12. It's a fresh Laravel installation that is set up to develop a task management application. The project follows Laravel's standard architecture and includes both backend APIs and frontend assets.

**Key Technologies:**
- Laravel 12 Framework
- PHP 8.2+
- AdminLTE 3.2 (admin panel UI theme)
- Vite as the build tool
- Tailwind CSS for styling
- Axios for HTTP requests
- PHPUnit for testing

**Note:** This is a newly initialized project with basic Laravel scaffolding. The custom task management functionality has not yet been implemented.

## Project Architecture

The project follows Laravel's MVC pattern:
- **app/**: Contains the main application logic (Controllers, Models, Services)
- **routes/**: Defines application routes
- **resources/**: Frontend assets (CSS, JS, Views)
- **config/**: Configuration files
- **database/**: Migrations, seeds, and factories
- **public/**: Web root with index.php
- **storage/**: Storage for logs, cache, and file uploads
- **tests/**: Unit and feature tests

## Current State

- Basic Laravel authentication is configured (User model present)
- Default Laravel migrations (users, cache, jobs) are present
- No custom task management models, controllers, or migrations yet
- AdminLTE theme has been added for admin interface

## Building and Running

### Initial Setup
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run database migrations (only default Laravel tables)
php artisan migrate

# Build frontend assets
npm run build
```

### Development Commands
```bash
# Start development server (includes Laravel, queue, logs, and Vite)
npm run dev
# or
composer run dev

# Alternative: Start Laravel server only
php artisan serve

# Build for production
npm run build

# Run tests
composer run test
# or
php artisan test

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed
```

### Environment Configuration
- Copy `.env.example` to `.env` and configure database settings
- Set `APP_NAME`, `APP_URL`, and other environment-specific variables

## Development Conventions

- **Coding Standards**: PSR-4 autoloading, Laravel conventions
- **Testing**: PHPUnit for backend tests, feature and unit test structure
- **Frontend Build**: Vite with Tailwind CSS integration
- **Database**: Eloquent ORM with migrations and factories
- **API**: RESTful design principles

## Future Development Notes

This is a base Laravel installation for a Task Management System. For implementing the task management features, consider:

- Creating models for Task, Project, TaskCategory, etc.
- Developing controllers for managing tasks
- Creating views for the task management interface
- Implementing authentication flows for the AdminLTE theme
- Setting up relationships between models
- Adding task-specific migrations

## Project-Specific Notes

- The application uses the AdminLTE theme for the admin interface
- Frontend assets are managed through Vite with Tailwind CSS
- The project includes Laravel's built-in queue system
- Environment configuration follows Laravel's standard approach

## Common Artisan Commands

```bash
# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Generate code
php artisan make:controller
php artisan make:model
php artisan make:migration

# Queue management
php artisan queue:work
php artisan queue:listen
```