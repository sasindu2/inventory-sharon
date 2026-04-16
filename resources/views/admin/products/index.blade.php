@extends('layouts.app')

@section('title', 'Manage Products')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h2 mb-1">Manage Products</h1>
            <p class="text-body-secondary mb-0">Create, update, archive, and monitor product inventory.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.export') }}" class="btn btn-outline-dark">Export CSV</a>
            <a href="{{ route('admin.products.create') }}" class="btn btn-dark">Add Product</a>
        </div>
    </div>

    @include('partials.product-filters', ['action' => route('admin.products.index')])

    <div class="surface-card p-4">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Stock Status</th>
                        <th>Location</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $product->name }}</div>
                                <div class="small text-body-secondary">{{ $product->sku }}</div>
                            </td>
                            <td>{{ $product->category?->name }}</td>
                            <td>{{ number_format($product->quantity) }}</td>
                            <td>
                                @if($product->isOutOfStock())
                                    <span class="badge text-bg-danger">Out of Stock</span>
                                @elseif($product->isLowStock())
                                    <span class="badge text-bg-warning">Low Stock</span>
                                @else
                                    <span class="badge text-bg-success">In Stock</span>
                                @endif
                            </td>
                            <td>{{ $product->location_label }}</td>
                            <td class="text-end">
                                <div class="d-inline-flex flex-wrap gap-2 justify-content-end">
                                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-outline-dark">View</a>
                                    <a href="{{ route('admin.stock-adjustments.create', $product) }}" class="btn btn-sm btn-outline-primary">Adjust</a>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Archive this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Archive</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-body-secondary py-4">No products found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $products->links() }}</div>
@endsection
