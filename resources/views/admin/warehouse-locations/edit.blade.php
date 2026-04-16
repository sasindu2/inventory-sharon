@extends('layouts.app')

@section('title', 'Edit Warehouse Location')

@section('content')
    <div class="surface-card p-4 p-lg-5">
        <h1 class="h2 mb-4">Edit Warehouse Location</h1>
        <form method="POST" action="{{ route('admin.warehouse-locations.update', $location) }}" class="vstack gap-4">
            @csrf
            @method('PUT')
            @include('admin.warehouse-locations.form')
            <div class="d-flex gap-2">
                <button class="btn btn-dark">Update Location</button>
                <a href="{{ route('admin.warehouse-locations.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
