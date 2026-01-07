# W.D.G - Web Directory Guide (Modern Edition)

## Overview
A modernized PHP and Bootstrap 4 web application for managing member information. This version features a fully Object-Oriented architecture, secure PDO database interactions with PostgreSQL, and strict typing.

## Project Structure
```
├── index.php          # Main dashboard - lists all members
├── add.php            # Add member interface
├── edit.php           # Edit member interface
├── delete.php         # Secure deletion logic
├── config.php         # Database & Repository (OOP)
├── dbcheck.php        # System health & diagnostics
├── footer.php         # Global footer
└── assets/            # Frontend assets
```

## Modern Features
- **Object-Oriented**: Dedicated `UserRepository` and `Database` singleton.
- **Security**: PDO prepared statements (SQL injection prevention), XSS protection (htmlspecialchars), and strict typing.
- **Database**: Native PostgreSQL integration.
- **Clean UI**: Updated Bootstrap 4 layouts.

## Tech Stack
- **Backend**: PHP 8.4 (Strict Types)
- **Database**: PostgreSQL (via PDO)
- **Frontend**: Bootstrap 4

## Environment
- `DATABASE_URL`: Automatically managed by Replit.
