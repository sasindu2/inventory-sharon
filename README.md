# Sharon Inventory

Laravel 11 inventory management system built for PHP 8.4 and MySQL with Blade UI, role-based access control, stock movement tracking, CSV export, warehouse location management, and responsive admin/user dashboards.

## Stack

- Laravel 11
- PHP 8.4
- MySQL 8+
- Blade templates
- Bootstrap 5

## Core Features

- Session authentication with seeded admin and regular-user accounts
- Role-based authorization with admin-only management routes
- Category, product, and warehouse location management
- Product search, filter, sort, pagination, and stock status badges
- Stock adjustments with immutable movement history
- Low-stock and out-of-stock visibility
- Product image upload with public storage
- Soft deletes for products
- Dashboard analytics and recent activity
- Admin activity logging
- Inventory CSV export

## Roles

- Admin
  Creates, updates, archives, and exports inventory. Manages categories, warehouse locations, stock, and activity history.
- Regular User
  Can view products, quantities, stock status, and warehouse locations.

## MySQL Schema

Schema details are documented in [docs/inventory-schema.md](docs/inventory-schema.md).

Main tables:

- `roles`
- `users`
- `categories`
- `warehouse_locations`
- `products`
- `stock_movements`
- `activity_logs`

## Installation

1. Install PHP 8.4, Composer, and MySQL.
2. Clone the project and enter the directory.
3. Install dependencies:

```bash
composer install
```

4. Copy the environment file if needed:

```bash
copy .env.example .env
```

5. Create the MySQL database:

```sql
CREATE DATABASE sharon_inventory CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

6. Confirm `.env` uses MySQL:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sharon_inventory
DB_USERNAME=root
DB_PASSWORD=
FILESYSTEM_DISK=public
```

7. Generate the app key:

```bash
php artisan key:generate
```

8. Run migrations and seed demo data:

```bash
php artisan migrate --seed
```

9. Link the public storage directory for product images:

```bash
php artisan storage:link
```

10. Start the application:

```bash
php artisan serve
```

## Demo Accounts

- Admin: `admin@inventory.test` / `password`
- Regular User: `user@inventory.test` / `password`

## Business Rules Implemented

- Every product belongs to one category
- Every product belongs to one warehouse location
- SKU is unique
- Product stock cannot go below zero
- Every stock increase or decrease creates a stock movement record
- Low stock is flagged when `quantity <= minimum_stock_level`
- Out of stock is flagged when `quantity = 0`
- Product deletion is soft delete only

## Verification

Executed locally with the PHP 8.4 binary available in this environment:

- `php artisan route:list`
- `php artisan migrate --pretend`
- `php -l` across changed PHP files
- `php artisan test`

The feature tests that require SQLite are skipped in this environment because the local PHP 8.4 build does not include `pdo_sqlite`.
