<?php

namespace Database\Factories;

use App\Models\WarehouseLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WarehouseLocation>
 */
class WarehouseLocationFactory extends Factory
{
    protected $model = WarehouseLocation::class;

    public function definition(): array
    {
        $area = fake()->randomElement(['A', 'B', 'C', 'D']).'-'.fake()->numberBetween(1, 4);
        $rack = 'R'.fake()->numberBetween(1, 25);
        $shelf = 'S'.fake()->numberBetween(1, 10);

        return [
            'code' => "{$area}-{$rack}-{$shelf}-".fake()->unique()->numberBetween(100, 999),
            'warehouse_area' => $area,
            'rack_number' => $rack,
            'shelf_number' => $shelf,
            'description' => fake()->sentence(),
        ];
    }
}
