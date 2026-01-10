# W.D.G Dashboard

## Overview
A PHP-based dashboard application with member management, activity logging, and page management features. The project includes a front-end dashboard and back-end API.

## Project Structure
```
├── index.php              # Entry point (redirects to front-end)
├── front-end/             # User interface files
│   ├── index.php          # Main dashboard
│   ├── members.php        # Member management
│   ├── logs.php           # Activity logs viewer
│   ├── dbcheck.php        # Database health check
│   ├── add-member.php     # Add new member form
│   ├── edit-member.php    # Edit member form
│   ├── add-page.php       # Add page form
│   ├── view-page.php      # View page content
│   ├── config.php         # Configuration loader
│   ├── footer.php         # Shared footer
│   ├── assets/            # CSS, JS, images
│   └── api-calling/       # JavaScript API functions
├── back-end/              # Server-side logic
│   ├── bootstrap.php      # Application initialization
│   ├── src/               # PHP classes
│   │   ├── Database.php   # Database connection (PostgreSQL/MySQL)
│   │   ├── UserRepository.php    # User CRUD operations
│   │   ├── PageRepository.php    # Page CRUD operations
│   │   └── Logger.php     # Activity logging
│   └── api/               # API endpoints
│       ├── users.php      # User API
│       └── loggers.php    # Logs API
```

## Database
- Uses PostgreSQL on Replit (connected via DATABASE_URL)
- Tables: wdg_users, wdg_activity_logs, wdg_pages

## Running the Application
The PHP built-in development server runs on port 5000:
```
php -S 0.0.0.0:5000
```

## Recent Changes
- 2026-01-10: Adapted from MySQL to PostgreSQL for Replit environment
- 2026-01-10: Updated Database.php to use DATABASE_URL environment variable
- 2026-01-10: Created PostgreSQL-compatible table schema
