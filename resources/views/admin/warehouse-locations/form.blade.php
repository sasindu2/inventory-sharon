<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Code</label>
        <input type="text" name="code" class="form-control" value="{{ old('code', $location->code) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Warehouse Area</label>
        <input type="text" name="warehouse_area" class="form-control" value="{{ old('warehouse_area', $location->warehouse_area) }}" required>
    </div>
    <div class="col-md-2">
        <label class="form-label">Rack Number</label>
        <input type="text" name="rack_number" class="form-control" value="{{ old('rack_number', $location->rack_number) }}" required>
    </div>
    <div class="col-md-2">
        <label class="form-label">Shelf Number</label>
        <input type="text" name="shelf_number" class="form-control" value="{{ old('shelf_number', $location->shelf_number) }}" required>
    </div>
    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" rows="4" class="form-control">{{ old('description', $location->description) }}</textarea>
    </div>
</div>
