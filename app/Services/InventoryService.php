<?php

namespace App\Services;

use App\Enums\StockMovementType;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class InventoryService
{
    public function createProduct(array $data, ?UploadedFile $image = null): Product
    {
        return DB::transaction(function () use ($data, $image) {
            $payload = Arr::except($data, ['image']);

            if ($image) {
                $payload['image_path'] = $image->store('product-images', 'public');
            }

            return Product::create($payload);
        });
    }

    public function updateProduct(Product $product, array $data, ?UploadedFile $image = null): Product
    {
        return DB::transaction(function () use ($product, $data, $image) {
            $payload = Arr::except($data, ['image', 'remove_image']);

            if (($data['remove_image'] ?? false) && $product->image_path) {
                Storage::disk('public')->delete($product->image_path);
                $payload['image_path'] = null;
            }

            if ($image) {
                if ($product->image_path) {
                    Storage::disk('public')->delete($product->image_path);
                }

                $payload['image_path'] = $image->store('product-images', 'public');
            }

            $product->update($payload);

            return $product->fresh(['category', 'warehouseLocation']);
        });
    }

    public function adjustStock(
        Product $product,
        User $user,
        int $quantity,
        StockMovementType $type,
        ?string $reason = null,
        ?string $notes = null
    ): StockMovement {
        return DB::transaction(function () use ($product, $user, $quantity, $type, $reason, $notes) {
            $lockedProduct = Product::query()->lockForUpdate()->findOrFail($product->id);
            $previousQuantity = $lockedProduct->quantity;
            $delta = $type->signedQuantity($quantity);
            $newQuantity = $previousQuantity + $delta;

            if ($newQuantity < 0) {
                throw new RuntimeException('Stock quantity cannot go below zero.');
            }

            $lockedProduct->update([
                'quantity' => $newQuantity,
            ]);

            return StockMovement::create([
                'product_id' => $lockedProduct->id,
                'user_id' => $user->id,
                'type' => $type,
                'reason' => $reason,
                'notes' => $notes,
                'quantity_change' => $delta,
                'previous_quantity' => $previousQuantity,
                'new_quantity' => $newQuantity,
            ]);
        });
    }
}
