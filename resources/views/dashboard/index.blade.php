@php($isAdmin = $user->isAdmin())

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="hero-card position-relative p-4 p-lg-5 mb-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="text-uppercase small fw-semibold mb-2">{{ $isAdmin ? 'Admin Overview' : 'Inventory Overview' }}</div>
                <h1 class="display-6 fw-bold mb-3">Live inventory visibility across products, stock levels, and warehouse slots.</h1>
                <p class="mb-0 text-white-50">
                    {{ $isAdmin ? 'Track stock risk, manage inventory operations, and review recent admin activity.' : 'Browse current quantities, low-stock items, and physical warehouse locations.' }}
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                @if($isAdmin)
                    <a href="{{ route('admin.products.export') }}" class="btn btn-light btn-lg">Export CSV</a>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card metric-card surface-card">
                <div class="card-body">
                    <div class="text-body-secondary mb-2">Products</div>
                    <div class="metric-value">{{ number_format($stats['products']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card metric-card surface-card">
                <div class="card-body">
                    <div class="text-body-secondary mb-2">Inventory Units</div>
                    <div class="metric-value">{{ number_format($stats['total_units']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card metric-card surface-card">
                <div class="card-body">
                    <div class="text-body-secondary mb-2">Low Stock</div>
                    <div class="metric-value text-warning">{{ number_format($stats['low_stock']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card metric-card surface-card">
                <div class="card-body">
                    <div class="text-body-secondary mb-2">Out of Stock</div>
                    <div class="metric-value text-danger">{{ number_format($stats['out_of_stock']) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="surface-card p-4 h-100">
                <h2 class="h5 mb-3">Snapshot</h2>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span>Categories</span>
                    <strong>{{ number_format($stats['categories']) }}</strong>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span>Warehouse Locations</span>
                    <strong>{{ number_format($stats['locations']) }}</strong>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span>Inventory Value</span>
                    <strong>${{ number_format((float) $stats['inventory_value'], 2) }}</strong>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="surface-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h5 mb-0">Recent Stock Movements</h2>
                    @if($isAdmin)
                        <a href="{{ route('admin.stock-movements.index') }}" class="btn btn-sm btn-outline-dark">View all</a>
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Type</th>
                                <th>Qty</th>
                                <th>User</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentMovements as $movement)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $movement->product?->name }}</div>
                                        <div class="text-body-secondary small">{{ $movement->product?->sku }}</div>
                                    </td>
                                    <td><span class="badge text-bg-{{ $movement->type->badgeClass() }}">{{ $movement->type->label() }}</span></td>
                                    <td>{{ $movement->quantity_change > 0 ? '+' : '' }}{{ $movement->quantity_change }}</td>
                                    <td>{{ $movement->user?->name ?? 'System' }}</td>
                                    <td>{{ $movement->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-body-secondary py-4">No stock activity yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($isAdmin)
        <div class="surface-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 mb-0">Recent Admin Activity</h2>
                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-sm btn-outline-dark">View all</a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Description</th>
                            <th>User</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivities as $log)
                            <tr>
                                <td><span class="badge badge-soft">{{ str($log->action)->replace('_', ' ')->title() }}</span></td>
                                <td>{{ $log->description }}</td>
                                <td>{{ $log->user?->name ?? 'System' }}</td>
                                <td>{{ $log->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-body-secondary py-4">No admin actions recorded yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
