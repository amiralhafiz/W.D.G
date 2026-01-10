# W.D.G - PHP Dashboard Application

## Overview
A PHP-based dashboard application with a front-end and back-end architecture that uses PostgreSQL for data storage.

## Project Structure
- `/front-end/` - PHP front-end pages (dashboard, user management, pages, logs)
- `/back-end/` - PHP backend with API endpoints and repository classes
  - `/api/` - API endpoints (health, users, pages, loggers)
  - `/src/` - Core classes (Database, Logger, UserRepository, PageRepository)
- `/index.php` - Entry point that redirects to front-end

## Tech Stack
- **Language**: PHP 8.2
- **Database**: PostgreSQL (via DATABASE_URL environment variable)
- **Frontend**: Bootstrap 5 with custom CSS
- **Server**: PHP built-in development server

## Database Tables
- `wdg_activity_logs` - Activity logging
- `wdg_users` - User management
- `wdg_pages` - CMS pages

## Running Locally
The application runs on port 5000 using PHP's built-in server.

## Configuration
- Database connection is automatically configured via `DATABASE_URL` environment variable
- Timezone is set to Asia/Kuala_Lumpur
