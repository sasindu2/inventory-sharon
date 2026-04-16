<div class="row g-3">
    <div class="col-12">
        <label class="form-label">Category Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
    </div>
    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" rows="4" class="form-control">{{ old('description', $category->description) }}</textarea>
    </div>
</div>
