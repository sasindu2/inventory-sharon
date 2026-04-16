# Inventory Schema

## Relational Design

### `roles`

- `id` bigint unsigned primary key
- `name` varchar(255)
- `slug` varchar(255) unique
- timestamps

### `users`

- Laravel default authentication columns
- `role_id` bigint unsigned nullable foreign key to `roles.id`
- index/constraint from foreign key

### `categories`

- `id` bigint unsigned primary key
- `name` varchar(255) unique
- `slug` varchar(255) unique
- `description` text nullable
- timestamps

### `warehouse_locations`

- `id` bigint unsigned primary key
- `code` varchar(255) unique
- `warehouse_area` varchar(255) indexed
- `rack_number` varchar(255)
- `shelf_number` varchar(255)
- `description` text nullable
- timestamps
- unique composite index on `warehouse_area`, `rack_number`, `shelf_number`

### `products`

- `id` bigint unsigned primary key
- `category_id` bigint unsigned foreign key to `categories.id`
- `warehouse_location_id` bigint unsigned foreign key to `warehouse_locations.id`
- `name` varchar(255)
- `sku` varchar(255) unique
- `description` text nullable
- `quantity` unsigned integer default `0`
- `minimum_stock_level` unsigned integer default `0`
- `unit_price` decimal(12,2) default `0`
- `image_path` varchar(255) nullable
- `status` enum(`active`, `inactive`, `discontinued`)
- timestamps
- soft deletes
- indexes on `category_id + status`, `warehouse_location_id + status`, `quantity + minimum_stock_level`

### `stock_movements`

- `id` bigint unsigned primary key
- `product_id` bigint unsigned foreign key to `products.id`
- `user_id` bigint unsigned nullable foreign key to `users.id`
- `type` enum(`increase`, `decrease`)
- `reason` varchar(255) nullable
- `notes` text nullable
- `quantity_change` integer
- `previous_quantity` unsigned integer
- `new_quantity` unsigned integer
- timestamps
- indexes on `product_id + created_at`, `user_id + created_at`

### `activity_logs`

- `id` bigint unsigned primary key
- `user_id` bigint unsigned nullable foreign key to `users.id`
- `action` varchar(255) indexed
- `subject_type` varchar(255) nullable
- `subject_id` bigint unsigned nullable
- `description` varchar(255)
- `properties` json nullable
- timestamps
- indexes on `subject_type + subject_id`, `action + created_at`

## Relationships

- One `role` has many `users`
- One `category` has many `products`
- One `warehouse_location` has many `products`
- One `product` belongs to one `category`
- One `product` belongs to one `warehouse_location`
- One `product` has many `stock_movements`
- One `stock_movement` belongs to one `product`
- One `stock_movement` belongs to one `user`
- One `activity_log` belongs to one `user`

## Referential Integrity

- Categories cannot be deleted while products still reference them
- Warehouse locations cannot be deleted while products still reference them
- Products are soft deleted to preserve stock history
- Stock movement rows keep user context when available and set `user_id` to null if a user is removed

## Performance Notes

- SKU is uniquely indexed for fast lookup
- Foreign keys are indexed by Laravel
- Product list filters are supported by category/status and warehouse/status indexes
- Low-stock queries benefit from the `quantity + minimum_stock_level` index
- Stock history queries benefit from the `product_id + created_at` composite index
