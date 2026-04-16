<?php

namespace Tests\Feature;

use App\Enums\StockMovementType;
use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use App\Models\WarehouseLocation;
use Tests\TestCase;
use Tests\Concerns\RequiresSqlite;

class StockAdjustmentTest extends TestCase
{
    use RequiresSqlite;

    public function test_admin_can_increase_stock_and_log_movement(): void
    {
        $this->prepareSqliteDatabase();

        $adminRole = Role::factory()->admin()->create();
        $admin = User::factory()->admin($adminRole)->create();
        $category = Category::factory()->create();
        $location = WarehouseLocation::factory()->create();
        $product = Product::factory()->for($category)->for($location)->create([
            'quantity' => 10,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.stock-adjustments.store', $product), [
            'type' => StockMovementType::Increase->value,
            'quantity' => 5,
            'reason' => 'Restock',
        ]);

        $response->assertRedirect(route('admin.products.show', $product));
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'user_id' => $admin->id,
            'quantity_change' => 5,
            'new_quantity' => 15,
        ]);
        $this->assertSame(15, $product->fresh()->quantity);
    }

    public function test_admin_cannot_reduce_stock_below_zero(): void
    {
        $this->prepareSqliteDatabase();

        $adminRole = Role::factory()->admin()->create();
        $admin = User::factory()->admin($adminRole)->create();
        $category = Category::factory()->create();
        $location = WarehouseLocation::factory()->create();
        $product = Product::factory()->for($category)->for($location)->create([
            'quantity' => 3,
        ]);

        $response = $this->from(route('admin.stock-adjustments.create', $product))
            ->actingAs($admin)
            ->post(route('admin.stock-adjustments.store', $product), [
                'type' => StockMovementType::Decrease->value,
                'quantity' => 5,
                'reason' => 'Damaged',
            ]);

        $response->assertRedirect(route('admin.stock-adjustments.create', $product));
        $response->assertSessionHasErrors('quantity');
        $this->assertSame(3, $product->fresh()->quantity);
        $this->assertDatabaseCount('stock_movements', 0);
    }
}
