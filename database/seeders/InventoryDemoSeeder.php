<?php

namespace Database\Seeders;

use App\Enums\ProductStatus;
use App\Enums\UserRole;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\WarehouseLocation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InventoryDemoSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::query()->where('slug', UserRole::Admin->value)->firstOrFail();
        $userRole = Role::query()->where('slug', UserRole::User->value)->firstOrFail();

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@inventory.test'],
            [
                'name' => 'Inventory Admin',
                'role_id' => $adminRole->id,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $regularUser = User::query()->updateOrCreate(
            ['email' => 'user@inventory.test'],
            [
                'name' => 'Warehouse Viewer',
                'role_id' => $userRole->id,
                'password' => Hash::make('biomotori'),
                'email_verified_at' => now(),
            ]
        );

        $hasExistingInventoryData = Category::query()->exists()
            || WarehouseLocation::query()->exists()
            || Product::query()->withTrashed()->exists();

        if ($hasExistingInventoryData) {
            ActivityLog::query()->updateOrCreate(
                ['action' => 'seeded_demo_data'],
                [
                    'user_id' => $admin->id,
                    'description' => 'Demo account credentials refreshed without reseeding inventory data.',
                    'properties' => [
                        'products' => Product::query()->withTrashed()->count(),
                        'categories' => Category::query()->count(),
                        'locations' => WarehouseLocation::query()->count(),
                        'viewer_account' => $regularUser->email,
                        'inventory_seed_skipped' => true,
                    ],
                ]
            );

            return;
        }

        User::factory(4)->regular($userRole)->create();

        $categories = Category::factory(6)->create();
        $locations = WarehouseLocation::factory(12)->create();

        Product::factory(28)
            ->state(fn () => [
                'category_id' => $categories->random()->id,
                'warehouse_location_id' => $locations->random()->id,
                'status' => fake()->randomElement([
                    ProductStatus::Active,
                    ProductStatus::Active,
                    ProductStatus::Inactive,
                ]),
            ])
            ->create();

        Product::factory(4)
            ->lowStock()
            ->state(fn () => [
                'category_id' => $categories->random()->id,
                'warehouse_location_id' => $locations->random()->id,
                'status' => ProductStatus::Active,
            ])
            ->create();

        Product::factory(3)
            ->outOfStock()
            ->state(fn () => [
                'category_id' => $categories->random()->id,
                'warehouse_location_id' => $locations->random()->id,
                'status' => ProductStatus::Active,
            ])
            ->create();

        Product::query()->get()->each(function (Product $product) use ($admin) {
            $initialQuantity = $product->quantity;

            StockMovement::query()->create([
                'product_id' => $product->id,
                'user_id' => $admin->id,
                'type' => 'increase',
                'reason' => 'Initial Stock Load',
                'notes' => 'Seeded opening balance.',
                'quantity_change' => $initialQuantity,
                'previous_quantity' => 0,
                'new_quantity' => $initialQuantity,
            ]);

            if ($initialQuantity > 10) {
                $deduction = fake()->numberBetween(1, min(8, $initialQuantity));
                $product->update([
                    'quantity' => $initialQuantity - $deduction,
                ]);

                StockMovement::query()->create([
                    'product_id' => $product->id,
                    'user_id' => $admin->id,
                    'type' => 'decrease',
                    'reason' => 'Sales Allocation',
                    'notes' => 'Demo stock issue transaction.',
                    'quantity_change' => -$deduction,
                    'previous_quantity' => $initialQuantity,
                    'new_quantity' => $initialQuantity - $deduction,
                ]);
            }
        });

        ActivityLog::factory(8)->create([
            'user_id' => $admin->id,
        ]);

        ActivityLog::query()->updateOrCreate(
            ['action' => 'seeded_demo_data'],
            [
                'user_id' => $admin->id,
                'description' => 'Initial inventory demo data seeded.',
                'properties' => [
                    'products' => Product::query()->count(),
                    'categories' => Category::query()->count(),
                    'locations' => WarehouseLocation::query()->count(),
                    'viewer_account' => $regularUser->email,
                    'inventory_seed_skipped' => false,
                ],
            ]
        );
    }
}
