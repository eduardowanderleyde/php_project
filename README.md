# Mini CMS

A simple PHP CMS with admin panel built using Test-Driven Development (TDD) and Docker.

## Features

- User authentication
- Page management
- Post management
- Menu management
- Image upload
- Rich text editor
- Template system
- 100% Test coverage
- Docker containerization

## Requirements

- Docker
- Docker Compose
- Git

## Installation

1. Clone the repository
2. Copy `.env.example` to `.env` and configure your environment variables
3. Build and start the containers:

   ```bash
   docker-compose up -d
   ```

4. Install dependencies:

   ```bash
   docker-compose exec app composer install
   ```

5. Create database tables:

   ```bash
   docker-compose exec app php database/migrations/migrate.php
   ```

## Accessing the Application

- Web Application: <http://localhost:8000>
- PHPMyAdmin: <http://localhost:8080>
  - Username: ${DB_USERNAME}
  - Password: ${DB_PASSWORD}

## Testing

Run the tests using Docker:

```bash
# Run all tests
docker-compose exec app composer test

# Run tests with coverage report
docker-compose exec app composer test:coverage

# Run static analysis
docker-compose exec app composer phpstan

# Check code style
docker-compose exec app composer cs-check

# Fix code style issues
docker-compose exec app composer cs-fix
```

## Default Admin Credentials

- Email: <admin@example.com>
- Password: admin123

## Project Structure

- `public/` - Public directory containing index.php and assets
- `src/` - Source code directory
  - `Controllers/` - Application controllers
  - `Models/` - Database models
  - `Views/` - View templates
  - `Config/` - Configuration files
  - `Database/` - Database migrations and seeds
  - `Helpers/` - Helper functions
- `tests/` - Test directory
  - `Unit/` - Unit tests
  - `Feature/` - Feature tests
- `templates/` - Front-end and admin templates
- `docker/` - Docker configuration files
  - `nginx/` - Nginx configuration
- `vendor/` - Composer dependencies (not in version control)

## Development Workflow

1. Write a failing test
2. Write the minimum code to make the test pass
3. Refactor the code while keeping tests green
4. Repeat

## Docker Commands

```bash
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# View logs
docker-compose logs -f

# Access container shell
docker-compose exec app bash

# Rebuild containers
docker-compose up -d --build
```
