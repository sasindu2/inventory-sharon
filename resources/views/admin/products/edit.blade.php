@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
    <div class="surface-card p-4 p-lg-5">
        <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
            <h1 class="h2 mb-0">Edit Product</h1>
            <a href="{{ route('admin.stock-adjustments.create', $product) }}" class="btn btn-outline-primary">Adjust Stock</a>
        </div>
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="vstack gap-4">
            @csrf
            @method('PUT')
            @include('admin.products.form')
            <div class="d-flex gap-2">
                <button class="btn btn-dark">Update Product</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
