@extends('layouts.app')

@section('title', 'Stock Movements')

@section('content')
    <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h2 mb-1">Stock Movement History</h1>
            <p class="text-body-secondary mb-0">Every inventory increase and decrease is tracked here.</p>
        </div>
    </div>

    <form method="GET" class="surface-card p-3 mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Product, SKU, reason, notes, or user">
            </div>
            <div class="col-md-6 d-flex gap-2">
                <button class="btn btn-dark">Search</button>
                <a href="{{ route('admin.stock-movements.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
    </form>

    <div class="surface-card p-4">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Type</th>
                        <th>Change</th>
                        <th>Previous</th>
                        <th>New</th>
                        <th>Reason</th>
                        <th>User</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $movement)
                        <tr>
                            <td>{{ $movement->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <div class="fw-semibold">{{ $movement->product?->name }}</div>
                                <div class="small text-body-secondary">{{ $movement->product?->sku }}</div>
                            </td>
                            <td><span class="badge text-bg-{{ $movement->type->badgeClass() }}">{{ $movement->type->label() }}</span></td>
                            <td>{{ $movement->quantity_change > 0 ? '+' : '' }}{{ $movement->quantity_change }}</td>
                            <td>{{ $movement->previous_quantity }}</td>
                            <td>{{ $movement->new_quantity }}</td>
                            <td>{{ $movement->reason ?? 'N/A' }}</td>
                            <td>{{ $movement->user?->name ?? 'System' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-body-secondary py-4">No stock movements found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $movements->links() }}</div>
@endsection
