@extends('layouts.app')

@section('title', 'Warehouse Locations')

@section('content')
    <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h2 mb-1">Warehouse Locations</h1>
            <p class="text-body-secondary mb-0">Manage physical storage slots for inventory placement and picking.</p>
        </div>
        <a href="{{ route('admin.warehouse-locations.create') }}" class="btn btn-dark">Add Location</a>
    </div>

    <div class="surface-card p-4">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Area</th>
                        <th>Rack</th>
                        <th>Shelf</th>
                        <th>Products</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $location)
                        <tr>
                            <td class="fw-semibold">{{ $location->code }}</td>
                            <td>{{ $location->warehouse_area }}</td>
                            <td>{{ $location->rack_number }}</td>
                            <td>{{ $location->shelf_number }}</td>
                            <td>{{ $location->products_count }}</td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('admin.warehouse-locations.edit', $location) }}" class="btn btn-sm btn-outline-dark">Edit</a>
                                    <form method="POST" action="{{ route('admin.warehouse-locations.destroy', $location) }}" onsubmit="return confirm('Delete this location?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-body-secondary py-4">No warehouse locations found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $locations->links() }}</div>
@endsection
