@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="surface-card p-4">
                @if($product->image_path)
                    <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="img-fluid rounded-4 mb-4">
                @else
                    <div class="rounded-4 border bg-light d-flex align-items-center justify-content-center text-body-secondary" style="min-height: 320px;">No product image</div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <div class="text-body-secondary small">{{ $product->category?->name }}</div>
                        <h1 class="h3 mb-0">{{ $product->name }}</h1>
                    </div>
                    <span class="badge text-bg-{{ $product->status->badgeClass() }}">{{ $product->status->label() }}</span>
                </div>

                <p class="text-body-secondary mb-4">{{ $product->description }}</p>

                <div class="vstack gap-2 small">
                    <div class="d-flex justify-content-between"><span>SKU</span><strong>{{ $product->sku }}</strong></div>
                    <div class="d-flex justify-content-between"><span>Quantity</span><strong>{{ number_format($product->quantity) }}</strong></div>
                    <div class="d-flex justify-content-between"><span>Minimum Stock</span><strong>{{ number_format($product->minimum_stock_level) }}</strong></div>
                    <div class="d-flex justify-content-between"><span>Stock Status</span><strong>{{ $product->stock_status }}</strong></div>
                    <div class="d-flex justify-content-between"><span>Unit Price</span><strong>${{ number_format((float) $product->unit_price, 2) }}</strong></div>
                    <div class="d-flex justify-content-between"><span>Warehouse Location</span><strong>{{ $product->location_label }}</strong></div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="surface-card p-4 mb-4">
                <h2 class="h5 mb-3">Recent Stock History</h2>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Change</th>
                                <th>Reason</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($product->stockMovements->take(10) as $movement)
                                <tr>
                                    <td>{{ $movement->created_at->format('M d, Y H:i') }}</td>
                                    <td><span class="badge text-bg-{{ $movement->type->badgeClass() }}">{{ $movement->type->label() }}</span></td>
                                    <td>{{ $movement->quantity_change > 0 ? '+' : '' }}{{ $movement->quantity_change }}</td>
                                    <td>{{ $movement->reason ?? 'N/A' }}</td>
                                    <td>{{ $movement->user?->name ?? 'System' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-body-secondary py-4">No stock history available.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <a href="{{ route('catalog.index') }}" class="btn btn-outline-dark">Back to Catalog</a>
        </div>
    </div>
@endsection
