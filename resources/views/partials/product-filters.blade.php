<form method="GET" action="{{ $action }}" class="surface-card p-3 mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-lg-4">
            <label class="form-label">Search</label>
            <input type="text" name="search" class="form-control" value="{{ $filters['search'] ?? '' }}" placeholder="Name, SKU, description, or location">
        </div>
        <div class="col-lg-2">
            <label class="form-label">Category</label>
            <select name="category_id" class="form-select">
                <option value="">All categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(($filters['category_id'] ?? '') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-2">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">All statuses</option>
                <option value="active" @selected(($filters['status'] ?? '') === 'active')>Active</option>
                <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Inactive</option>
                <option value="discontinued" @selected(($filters['status'] ?? '') === 'discontinued')>Discontinued</option>
            </select>
        </div>
        <div class="col-lg-2">
            <label class="form-label">Stock</label>
            <select name="stock" class="form-select">
                <option value="">All</option>
                <option value="available" @selected(($filters['stock'] ?? '') === 'available')>Available</option>
                <option value="low" @selected(($filters['stock'] ?? '') === 'low')>Low stock</option>
                <option value="out" @selected(($filters['stock'] ?? '') === 'out')>Out of stock</option>
            </select>
        </div>
        <div class="col-lg-2">
            <label class="form-label">Sort</label>
            <select name="sort" class="form-select">
                <option value="updated_desc" @selected(($filters['sort'] ?? '') === 'updated_desc')>Newest</option>
                <option value="name_asc" @selected(($filters['sort'] ?? '') === 'name_asc')>Name A-Z</option>
                <option value="name_desc" @selected(($filters['sort'] ?? '') === 'name_desc')>Name Z-A</option>
                <option value="sku_asc" @selected(($filters['sort'] ?? '') === 'sku_asc')>SKU</option>
                <option value="qty_low_high" @selected(($filters['sort'] ?? '') === 'qty_low_high')>Qty low-high</option>
                <option value="qty_high_low" @selected(($filters['sort'] ?? '') === 'qty_high_low')>Qty high-low</option>
                <option value="price_low_high" @selected(($filters['sort'] ?? '') === 'price_low_high')>Price low-high</option>
                <option value="price_high_low" @selected(($filters['sort'] ?? '') === 'price_high_low')>Price high-low</option>
            </select>
        </div>
        <div class="col-12 d-flex gap-2">
            <button class="btn btn-dark">Apply Filters</button>
            <a href="{{ $action }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </div>
</form>
