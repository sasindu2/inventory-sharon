@extends('layouts.app')

@section('title', 'Create Product')

@section('content')
    <div class="surface-card p-4 p-lg-5">
        <h1 class="h2 mb-4">Create Product</h1>
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="vstack gap-4">
            @csrf
            @include('admin.products.form')
            <div class="d-flex gap-2">
                <button class="btn btn-dark">Save Product</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
