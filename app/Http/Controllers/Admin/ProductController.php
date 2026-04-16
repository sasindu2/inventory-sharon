<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StockMovementType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\WarehouseLocation;
use App\Services\ActivityLogService;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private readonly InventoryService $inventoryService,
        private readonly ActivityLogService $activityLogService,
    ) {
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'category_id', 'status', 'stock', 'sort']);

        return view('admin.products.index', [
            'products' => Product::query()
                ->with(['category', 'warehouseLocation'])
                ->filter($filters)
                ->paginate(12)
                ->withQueryString(),
            'categories' => Category::query()->orderBy('name')->get(),
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'product' => new Product(),
            'categories' => Category::query()->orderBy('name')->get(),
            'locations' => WarehouseLocation::query()->orderBy('warehouse_area')->orderBy('rack_number')->get(),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $openingQuantity = (int) $validated['quantity'];
        $validated['quantity'] = 0;

        $product = $this->inventoryService->createProduct($validated, $request->file('image'));

        if ($openingQuantity > 0) {
            $this->inventoryService->adjustStock(
                $product,
                $request->user(),
                $openingQuantity,
                StockMovementType::Increase,
                'Opening Balance',
                'Initial stock entered during product creation.'
            );
        }

        $this->activityLogService->log(
            $request->user(),
            'product_created',
            "Created product {$product->name}.",
            $product
        );

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product): View
    {
        $product->load([
            'category',
            'warehouseLocation',
            'stockMovements.user',
        ]);

        return view('admin.products.show', [
            'product' => $product,
        ]);
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::query()->orderBy('name')->get(),
            'locations' => WarehouseLocation::query()->orderBy('warehouse_area')->orderBy('rack_number')->get(),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product = $this->inventoryService->updateProduct($product, $request->validated(), $request->file('image'));

        $this->activityLogService->log(
            $request->user(),
            'product_updated',
            "Updated product {$product->name}.",
            $product
        );

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $productName = $product->name;
        $product->delete();

        $this->activityLogService->log(
            request()->user(),
            'product_deleted',
            "Archived product {$productName}.",
            Product::class,
            ['name' => $productName]
        );

        return redirect()->route('admin.products.index')
            ->with('success', 'Product archived successfully.');
    }
}
