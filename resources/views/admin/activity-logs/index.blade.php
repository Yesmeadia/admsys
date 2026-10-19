@extends('admin.layout')

@section('title', 'Activity Logs')
@section('page-title', 'Activity Logs')
@section('page-subtitle', 'Monitor all user activities and system events')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="premium-card position-relative overflow-hidden mb-4"
                style="background: linear-gradient(135deg, rgba(67, 97, 238, 0.05), rgba(67, 97, 238, 0.02)); border: 1px solid rgba(67, 97, 238, 0.1);">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-filter me-2 text-primary"></i>Filter Activities
                    </h5>
                    <a href="{{ route('admin.activity-logs.export') }}"
                        class="btn btn-success btn-sm rounded-pill px-3 shadow-sm">
                        <i class="fas fa-file-excel me-1"></i> Export to CSV
                    </a>
                </div>
                <form method="GET" action="{{ route('admin.activity-logs.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">User</label>
                            <select class="form-select bg-white bg-opacity-50 border-0 shadow-sm" name="user_id">
                                <option value="">All Users</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->isAdmin() ? 'Admin' : 'Staff' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">Activity Type</label>
                            <input type="text" class="form-control bg-white bg-opacity-50 border-0 shadow-sm"
                                name="activity" placeholder="e.g., Login, Created User..."
                                value="{{ request('activity') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-muted">From Date</label>
                            <input type="date" class="form-control bg-white bg-opacity-50 border-0 shadow-sm"
                                name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-muted">To Date</label>
                            <input type="date" class="form-control bg-white bg-opacity-50 border-0 shadow-sm" name="date_to"
                                value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1 rounded-pill shadow-sm">
                                <i class="fas fa-search me-1"></i> Filter
                            </button>
                            <a href="{{ route('admin.activity-logs.index') }}"
                                class="btn btn-white bg-white bg-opacity-50 border-0 shadow-sm">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </form>
                <div class="position-absolute" style="bottom: -20px; right: -20px; opacity: 0.03; pointer-events: none;">
                    <i class="fas fa-search" style="font-size: 80px;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="premium-card p-0 overflow-hidden position-relative"
        style="background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(67, 97, 238, 0.05)); border: 1px solid rgba(67, 97, 238, 0.1);">
        <div class="table-responsive bg-transparent">
            <table class="table table-hover align-middle mb-0 bg-transparent"
                style="--bs-table-bg: transparent; --bs-table-hover-bg: rgba(67, 97, 238, 0.05);">
                <thead>
                    <tr>
                        <th class="ps-4 py-3 border-0"
                            style="font-size: 11px; font-weight: 700; text-transform: uppercase;">User</th>
                        <th class="py-3 border-0" style="font-size: 11px; font-weight: 700; text-transform: uppercase;">
                            Activity
                        </th>
                        <th class="py-3 border-0" style="font-size: 11px; font-weight: 700; text-transform: uppercase;">
                            Description</th>
                        <th class="py-3 border-0" style="font-size: 11px; font-weight: 700; text-transform: uppercase;">IP
                            Address
                        </th>
                        <th class="pe-4 py-3 border-0"
                            style="font-size: 11px; font-weight: 700; text-transform: uppercase;">Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                        style="width: 36px; height: 36px; background: linear-gradient(135deg, #4361ee, #4cc9f0); font-size: 12px;">
                                        @if($activity->user)
                                            {{ strtoupper(substr($activity->user->name, 0, 1)) }}
                                        @else
                                            <i class="fas fa-robot"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold" style="font-size: 14px;">
                                            {{ $activity->user ? $activity->user->name : 'System' }}
                                        </div>
                                        <small class="text-muted" style="font-size: 11px;">
                                            @if($activity->user)
                                                {{ $activity->user->isAdmin() ? 'Administrator' : 'Office Staff' }}
                                            @else
                                                Automated
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size: 11px;">
                                    {{ $activity->activity }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted" style="font-size: 13px;">{{ $activity->description }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark" style="font-size: 11px;">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $activity->ip_address }}
                                </span>
                            </td>
                            <td class="pe-4">
                                <div class="text-muted" style="font-size: 13px;">{{ $activity->created_at->format('M d, Y') }}
                                </div>
                                <small class="text-muted"
                                    style="font-size: 11px;">{{ $activity->created_at->diffForHumans() }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-history text-muted mb-3 d-block" style="font-size: 40px;"></i>
                                <h6 class="text-muted fw-bold">No activity logs found</h6>
                                <p class="text-muted small">Try adjusting your filters</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($activities->hasPages())
            <div class="px-4 py-3 bg-transparent border-top" style="border-top: 1px solid rgba(0,0,0,0.05) !important;">
                <small class="text-muted">Showing <b>{{ $activities->firstItem() }}</b> to <b>{{ $activities->lastItem() }}</b>
                    of <b>{{ $activities->total() }}</b> results</small>
                {{ $activities->links('pagination::bootstrap-5') }}
            </div>
        @endif
        <div class="position-absolute" style="bottom: -20px; right: -20px; opacity: 0.03; pointer-events: none;">
            <i class="fas fa-history" style="font-size: 150px;"></i>
        </div>
    </div>
@endsection