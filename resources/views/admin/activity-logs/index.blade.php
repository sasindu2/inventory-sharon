@extends('layouts.app')

@section('title', 'Activity Logs')

@section('content')
    <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h2 mb-1">Admin Activity Log</h1>
            <p class="text-body-secondary mb-0">Review the latest administrative actions recorded in the system.</p>
        </div>
    </div>

    <div class="surface-card p-4">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>User</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('M d, Y H:i') }}</td>
                            <td><span class="badge badge-soft">{{ str($log->action)->replace('_', ' ')->title() }}</span></td>
                            <td>{{ $log->description }}</td>
                            <td>{{ $log->user?->name ?? 'System' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-body-secondary py-4">No activity logs found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $logs->links() }}</div>
@endsection
