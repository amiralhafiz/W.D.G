# W.D.G - Web Dashboard Generator

## Overview
A PHP-based web dashboard application with a front-end interface and back-end API. The application uses PostgreSQL for data storage and provides user management, page management, and activity logging features.

## Project Structure
- `front-end/` - PHP frontend pages, assets (CSS, JS, images), and API calling scripts
- `back-end/` - PHP backend with API endpoints and data repositories
  - `api/` - REST API endpoints (health, users, pages, loggers)
  - `src/` - Core classes (Database, Logger, UserRepository, PageRepository)
- `index.php` - Root redirector to front-end

## Tech Stack
- **Language**: PHP 8.4
- **Database**: PostgreSQL (via DATABASE_URL environment variable)
- **Frontend**: Bootstrap 5, vanilla JavaScript
- **Server**: PHP built-in development server

## Database Schema
- `wdg_users` - User management (user, fullname, phonenumber, email, status, date)
- `wdg_pages` - Page content (id, status, title, slug, description, content, created_at)
- `wdg_activity_logs` - Activity logging (id, activity, details, created_at)

## Running the Application
The application runs on PHP's built-in server at port 5000:
```bash
php -S 0.0.0.0:5000
```

## Configuration
- Database connection is handled automatically via `DATABASE_URL` environment variable
- Timezone is set to Asia/Kuala_Lumpur in bootstrap.php and config.php
