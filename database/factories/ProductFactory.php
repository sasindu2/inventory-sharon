<?php

namespace Database\Factories;

use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use App\Models\WarehouseLocation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = Str::title(fake()->unique()->words(fake()->numberBetween(2, 4), true));
        $quantity = fake()->numberBetween(0, 140);
        $minimumStock = fake()->numberBetween(5, 25);

        return [
            'category_id' => Category::factory(),
            'warehouse_location_id' => WarehouseLocation::factory(),
            'name' => $name,
            'sku' => 'SKU-'.fake()->unique()->numerify('#####'),
            'description' => fake()->paragraph(),
            'quantity' => $quantity,
            'minimum_stock_level' => $minimumStock,
            'unit_price' => fake()->randomFloat(2, 5, 2500),
            'image_path' => null,
            'status' => fake()->randomElement(ProductStatus::cases()),
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => [
            'status' => ProductStatus::Active,
        ]);
    }

    public function lowStock(): static
    {
        $threshold = fake()->numberBetween(5, 15);

        return $this->state(fn () => [
            'quantity' => fake()->numberBetween(1, $threshold),
            'minimum_stock_level' => $threshold,
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn () => [
            'quantity' => 0,
            'minimum_stock_level' => fake()->numberBetween(5, 15),
        ]);
    }
}
