# W.D.G - Web Directory Guide

## Overview
A simple PHP and Bootstrap 4 web application for managing member information with full CRUD (Create, Read, Update, Delete) functionality. Originally designed for MySQL, now adapted to use PostgreSQL on Replit.

## Project Structure
```
├── index.php          # Main page - lists all members
├── add.php            # Add new member form
├── edit.php           # Edit existing member
├── delete.php         # Delete member handler
├── config.php         # Database connection configuration
├── dbcheck.php        # Database health check page
├── footer.php         # Footer include file
├── assets/
│   ├── css/           # Bootstrap and custom CSS
│   ├── js/            # Bootstrap and custom JavaScript
│   ├── fonts/         # Glyphicons font files
│   └── images/        # Application images
└── w.d.g_base.sql     # Original MySQL schema (for reference)
```

## Tech Stack
- **Backend**: PHP 8.4
- **Database**: PostgreSQL (adapted from MySQL)
- **Frontend**: Bootstrap 4, jQuery
- **Server**: PHP built-in development server

## Database Schema
Table: `wdg_users`
- `id` - SERIAL PRIMARY KEY
- `name` - VARCHAR(100)
- `phonenumber` - VARCHAR(20)
- `email` - VARCHAR(100)
- `date` - TIMESTAMP (defaults to current timestamp)

## Running the Application
The application runs on port 5000 using PHP's built-in server:
```
php -S 0.0.0.0:5000
```

## Environment Variables
- `DATABASE_URL` - PostgreSQL connection string (automatically provided by Replit)

## Recent Changes
- January 2026: Converted from MySQL (mysqli) to PostgreSQL (pg_*) functions
- Added proper input sanitization with pg_escape_string and htmlspecialchars
- Updated all database queries to PostgreSQL syntax
