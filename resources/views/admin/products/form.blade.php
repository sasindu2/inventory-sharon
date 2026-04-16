@php($editing = $product->exists)

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Product Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">SKU</label>
        <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            @foreach(\App\Enums\ProductStatus::cases() as $status)
                <option value="{{ $status->value }}" @selected(old('status', $product->status?->value ?? \App\Enums\ProductStatus::Active->value) === $status->value)>{{ $status->label() }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-select" required>
            <option value="">Select category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Warehouse Location</label>
        <select name="warehouse_location_id" class="form-select" required>
            <option value="">Select location</option>
            @foreach($locations as $location)
                <option value="{{ $location->id }}" @selected(old('warehouse_location_id', $product->warehouse_location_id) == $location->id)>
                    {{ $location->code }} | {{ $location->warehouse_area }} / {{ $location->rack_number }} / {{ $location->shelf_number }}
                </option>
            @endforeach
        </select>
    </div>
    @unless($editing)
        <div class="col-md-4">
            <label class="form-label">Opening Quantity</label>
            <input type="number" min="0" name="quantity" class="form-control" value="{{ old('quantity', 0) }}" required>
        </div>
    @else
        <div class="col-md-4">
            <label class="form-label">Current Quantity</label>
            <input type="text" class="form-control" value="{{ number_format($product->quantity) }}" readonly>
            <div class="form-text">Use stock adjustments to change quantity.</div>
        </div>
    @endunless
    <div class="col-md-4">
        <label class="form-label">Minimum Stock Level</label>
        <input type="number" min="0" name="minimum_stock_level" class="form-control" value="{{ old('minimum_stock_level', $product->minimum_stock_level ?? 0) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Unit Price</label>
        <input type="number" min="0" step="0.01" name="unit_price" class="form-control" value="{{ old('unit_price', $product->unit_price ?? 0) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Product Image</label>
        <input type="file" name="image" class="form-control" accept="image/*">
    </div>
    @if($editing && $product->image_path)
        <div class="col-md-6 d-flex align-items-end">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" name="remove_image" id="remove_image">
                <label class="form-check-label" for="remove_image">Remove current image</label>
            </div>
        </div>
        <div class="col-12">
            <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="img-thumb">
        </div>
    @endif
    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" rows="5" class="form-control">{{ old('description', $product->description) }}</textarea>
    </div>
</div>
