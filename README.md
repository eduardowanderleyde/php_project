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

## ShippingCalculator — Usage Documentation

### 1. Instantiating the Calculator

You can use the `ShippingCalculator` with any implementation of `HttpClientInterface`.
For real integration with ViaCEP, use `ApiFreightClient`:

```php
use App\Services\ShippingCalculator;
use App\Services\ApiFreightClient;

$client = new ApiFreightClient();
$calculator = new ShippingCalculator($client);
```

### 2. Calculating the Shipping Price

```php
$originCep = '01001-000';        // Origin CEP
$destinationCep = '20040-000';   // Destination CEP
$weight = 2.5;                   // Weight in kg

try {
    $price = $calculator->calculate($originCep, $destinationCep, $weight);
    echo "Estimated shipping price: R$ " . number_format($price, 2, ',', '.');
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

### 3. Simulation Logic

- **Same CEP:** shipping = R$ 10.00 × weight
- **Same city:** shipping = R$ 15.00 × weight
- **Same state:** shipping = R$ 20.00 × weight
- **Different states:** shipping = R$ 35.00 × weight

### 4. Example Response

```
Estimated shipping price: R$ 70,00
```

### 5. Error Handling

- Invalid or not found CEP: throws exception with clear message.
- Weight less than or equal to zero: throws exception.
- Failure in ViaCEP integration: throws exception.

### 6. Tests

The project already has automated tests covering:

- Shipping calculation for all scenarios (same CEP, city, state, different states)
- CEP and weight validation
- Integration and error handling with ViaCEP

### 7. Example usage in Controller (REST API)

If you want to expose as an endpoint, just create a controller and use the logic above to return the shipping price in JSON.
