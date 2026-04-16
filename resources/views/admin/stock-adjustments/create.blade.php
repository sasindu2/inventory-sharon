@extends('layouts.app')

@section('title', 'Adjust Stock')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="surface-card p-4 p-lg-5">
                <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                    <div>
                        <h1 class="h2 mb-1">Adjust Stock</h1>
                        <p class="text-body-secondary mb-0">{{ $product->name }} · {{ $product->sku }}</p>
                    </div>
                    <span class="badge badge-soft px-3 py-2">Current Qty: {{ number_format($product->quantity) }}</span>
                </div>

                <form method="POST" action="{{ route('admin.stock-adjustments.store', $product) }}" class="vstack gap-4">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Adjustment Type</label>
                            <select name="type" class="form-select" required>
                                @foreach($movementTypes as $type)
                                    <option value="{{ $type->value }}" @selected(old('type') === $type->value)>{{ $type->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Quantity</label>
                            <input type="number" min="1" name="quantity" class="form-control" value="{{ old('quantity') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Reason</label>
                            <input type="text" name="reason" class="form-control" value="{{ old('reason') }}" placeholder="Cycle count, sales issue, restock">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" rows="4" class="form-control">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-dark">Submit Adjustment</button>
                        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
