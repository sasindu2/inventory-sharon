@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h2 mb-1">Product Catalog</h1>
            <p class="text-body-secondary mb-0">Browse live stock levels, location assignments, and product status.</p>
        </div>
        <span class="badge badge-soft px-3 py-2">{{ $products->total() }} products</span>
    </div>

    @include('partials.product-filters', ['action' => route('catalog.index')])

    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-md-6 col-xl-4">
                <div class="surface-card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="text-body-secondary small">{{ $product->category?->name }}</div>
                            <h2 class="h5 mb-1">{{ $product->name }}</h2>
                            <div class="small text-body-secondary">{{ $product->sku }}</div>
                        </div>
                        <span class="badge text-bg-{{ $product->status->badgeClass() }}">{{ $product->status->label() }}</span>
                    </div>

                    <div class="d-flex gap-3 align-items-center mb-3">
                        @if($product->image_path)
                            <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="img-thumb">
                        @else
                            <div class="img-thumb d-flex align-items-center justify-content-center text-body-secondary small">No image</div>
                        @endif
                        <div class="small">
                            <div><strong>Location:</strong> {{ $product->location_label }}</div>
                            <div><strong>Price:</strong> ${{ number_format((float) $product->unit_price, 2) }}</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="text-body-secondary small">Available Quantity</div>
                            <div class="fs-4 fw-bold">{{ number_format($product->quantity) }}</div>
                        </div>
                        <div class="text-end">
                            @if($product->isOutOfStock())
                                <span class="badge text-bg-danger">Out of Stock</span>
                            @elseif($product->isLowStock())
                                <span class="badge text-bg-warning">Low Stock</span>
                            @else
                                <span class="badge text-bg-success">In Stock</span>
                            @endif
                        </div>
                    </div>

                    <p class="text-body-secondary">{{ \Illuminate\Support\Str::limit($product->description, 110) }}</p>

                    <a href="{{ route('catalog.show', $product) }}" class="btn btn-outline-dark w-100">View Details</a>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="surface-card p-5 text-center text-body-secondary">No products matched the current filters.</div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
@endsection
