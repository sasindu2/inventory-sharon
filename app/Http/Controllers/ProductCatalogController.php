<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProductCatalogController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'category_id', 'status', 'stock', 'sort']);

        $products = Product::query()
            ->with(['category', 'warehouseLocation'])
            ->filter($filters)
            ->paginate(12)
            ->withQueryString();

        return view('catalog.index', [
            'products' => $products,
            'categories' => Category::query()->orderBy('name')->get(),
            'filters' => $filters,
        ]);
    }

    public function show(Product $product): View
    {
        $product->load(['category', 'warehouseLocation', 'stockMovements.user']);

        return view('catalog.show', [
            'product' => $product,
        ]);
    }
}
