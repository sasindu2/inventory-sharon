<?php

namespace Database\Factories;

use App\Enums\StockMovementType;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StockMovement>
 */
class StockMovementFactory extends Factory
{
    protected $model = StockMovement::class;

    public function definition(): array
    {
        $previous = fake()->numberBetween(0, 100);
        $type = fake()->randomElement(StockMovementType::cases());
        $quantity = fake()->numberBetween(1, 20);
        $delta = $type->signedQuantity($quantity);
        $new = max(0, $previous + $delta);

        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'type' => $type,
            'reason' => fake()->randomElement(['Purchase Order', 'Cycle Count', 'Damaged Stock', 'Sales Issue']),
            'notes' => fake()->sentence(),
            'quantity_change' => $delta,
            'previous_quantity' => $previous,
            'new_quantity' => $new,
        ];
    }
}
