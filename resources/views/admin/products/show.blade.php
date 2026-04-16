@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h2 mb-1">{{ $product->name }}</h1>
            <p class="text-body-secondary mb-0">{{ $product->sku }} · {{ $product->category?->name }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.stock-adjustments.create', $product) }}" class="btn btn-primary">Adjust Stock</a>
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-dark">Edit Product</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="surface-card p-4 h-100">
                @if($product->image_path)
                    <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="img-fluid rounded-4 mb-4">
                @else
                    <div class="rounded-4 border bg-light d-flex align-items-center justify-content-center text-body-secondary mb-4" style="min-height: 240px;">No image uploaded</div>
                @endif
                <div class="vstack gap-2 small">
                    <div class="d-flex justify-content-between"><span>Status</span><strong>{{ $product->status->label() }}</strong></div>
                    <div class="d-flex justify-content-between"><span>Quantity</span><strong>{{ number_format($product->quantity) }}</strong></div>
                    <div class="d-flex justify-content-between"><span>Minimum Stock</span><strong>{{ number_format($product->minimum_stock_level) }}</strong></div>
                    <div class="d-flex justify-content-between"><span>Stock Status</span><strong>{{ $product->stock_status }}</strong></div>
                    <div class="d-flex justify-content-between"><span>Unit Price</span><strong>${{ number_format((float) $product->unit_price, 2) }}</strong></div>
                    <div class="d-flex justify-content-between"><span>Location</span><strong>{{ $product->location_label }}</strong></div>
                </div>
                <hr>
                <p class="mb-0 text-body-secondary">{{ $product->description ?: 'No description provided.' }}</p>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="surface-card p-4">
                <h2 class="h5 mb-3">Stock Movement History</h2>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Change</th>
                                <th>Previous</th>
                                <th>New</th>
                                <th>Reason</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($product->stockMovements as $movement)
                                <tr>
                                    <td>{{ $movement->created_at->format('M d, Y H:i') }}</td>
                                    <td><span class="badge text-bg-{{ $movement->type->badgeClass() }}">{{ $movement->type->label() }}</span></td>
                                    <td>{{ $movement->quantity_change > 0 ? '+' : '' }}{{ $movement->quantity_change }}</td>
                                    <td>{{ $movement->previous_quantity }}</td>
                                    <td>{{ $movement->new_quantity }}</td>
                                    <td>{{ $movement->reason ?? 'N/A' }}</td>
                                    <td>{{ $movement->user?->name ?? 'System' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-body-secondary py-4">No stock history recorded.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
