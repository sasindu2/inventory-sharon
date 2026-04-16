@extends('layouts.app')

@section('title', 'Create Category')

@section('content')
    <div class="surface-card p-4 p-lg-5">
        <h1 class="h2 mb-4">Create Category</h1>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="vstack gap-4">
            @csrf
            @include('admin.categories.form')
            <div class="d-flex gap-2">
                <button class="btn btn-dark">Save Category</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
