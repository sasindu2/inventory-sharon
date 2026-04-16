@extends('layouts.app')

@section('title', 'Create Warehouse Location')

@section('content')
    <div class="surface-card p-4 p-lg-5">
        <h1 class="h2 mb-4">Create Warehouse Location</h1>
        <form method="POST" action="{{ route('admin.warehouse-locations.store') }}" class="vstack gap-4">
            @csrf
            @include('admin.warehouse-locations.form')
            <div class="d-flex gap-2">
                <button class="btn btn-dark">Save Location</button>
                <a href="{{ route('admin.warehouse-locations.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
