<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StockMovementType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStockAdjustmentRequest;
use App\Models\Product;
use App\Services\ActivityLogService;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use RuntimeException;

class StockAdjustmentController extends Controller
{
    public function __construct(
        private readonly InventoryService $inventoryService,
        private readonly ActivityLogService $activityLogService,
    ) {
    }

    public function create(Product $product): View
    {
        return view('admin.stock-adjustments.create', [
            'product' => $product,
            'movementTypes' => StockMovementType::cases(),
        ]);
    }

    public function store(StoreStockAdjustmentRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $movement = $this->inventoryService->adjustStock(
                $product,
                $request->user(),
                (int) $validated['quantity'],
                StockMovementType::from($validated['type']),
                $validated['reason'] ?? null,
                $validated['notes'] ?? null,
            );
        } catch (RuntimeException $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }

        $this->activityLogService->log(
            $request->user(),
            'stock_adjusted',
            "Adjusted stock for {$product->name}.",
            $product,
            [
                'movement_id' => $movement->id,
                'quantity_change' => $movement->quantity_change,
                'new_quantity' => $movement->new_quantity,
            ]
        );

        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Stock adjusted successfully.');
    }
}
