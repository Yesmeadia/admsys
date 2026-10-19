@extends('admin.layout')

@section('title', 'User Management')
@section('page-title', 'User Management')
@section('page-subtitle', 'Manage system administrators and staff access')

@section('content')
    <div class="premium-card p-0 overflow-hidden position-relative mb-4"
        style="background: linear-gradient(135deg, rgba(67, 97, 238, 0.05), rgba(67, 97, 238, 0.02)); border: 1px solid rgba(67, 97, 238, 0.1);">
        <div class="p-4 d-flex justify-content-between align-items-center bg-white bg-opacity-25">
            <h5 class="mb-0 fw-bold">System Users</h5>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                <x-fas-plus class="me-1" /> Add New User
            </a>
        </div>
        <div class="p-0">
            <div class="table-responsive bg-transparent">
                <table class="table table-hover align-middle mb-0 bg-transparent"
                    style="--bs-table-bg: transparent; --bs-table-hover-bg: rgba(67, 97, 238, 0.05);">
                    <thead>
                        <tr>
                            <th class="ps-4 py-3 border-0"
                                style="font-size: 11px; font-weight: 700; text-transform: uppercase;">Name</th>
                            <th class="py-3 border-0" style="font-size: 11px; font-weight: 700; text-transform: uppercase;">
                                Email</th>
                            <th class="py-3 border-0" style="font-size: 11px; font-weight: 700; text-transform: uppercase;">
                                Role</th>
                            <th class="py-3 border-0" style="font-size: 11px; font-weight: 700; text-transform: uppercase;">
                                Phone</th>
                            <th class="py-3 border-0" style="font-size: 11px; font-weight: 700; text-transform: uppercase;">
                                Status</th>
                            <th class="py-3 border-0" style="font-size: 11px; font-weight: 700; text-transform: uppercase;">
                                Last Login</th>
                            <th class="text-end pe-4 py-3 border-0"
                                style="font-size: 11px; font-weight: 700; text-transform: uppercase;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3"
                                            style="width: 36px; height: 36px; font-weight: 600;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span class="fw-semibold">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->isAdmin())
                                        <span class="badge bg-danger">Administrator</span>
                                    @else
                                        <span class="badge bg-info">Office Staff</span>
                                    @endif
                                </td>
                                <td>{{ $user->phone ?? 'N/A' }}</td>
                                <td>
                                    @if($user->isBanned())
                                        <span class="badge bg-danger bg-opacity-10 text-danger">
                                            <x-fas-ban class="me-1" />Banned
                                        </span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            <x-fas-check-circle class="me-1" />Active
                                        </span>
                                    @endif
                                </td>
                                <td class="small text-muted">
                                    {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                            class="btn btn-sm btn-outline-secondary" title="Edit">
                                            <x-fas-edit />
                                        </a>
                                        @if($user->id !== auth()->id())
                                            @if($user->isBanned())
                                                <form action="{{ route('admin.users.unban', $user->id) }}" method="POST"
                                                    class="d-inline" data-confirm="Are you sure you want to unban {{ $user->name }}?">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Unban User">
                                                        <x-fas-user-check />
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline-warning" title="Ban User"
                                                    data-bs-toggle="modal" data-bs-target="#banModal{{ $user->id }}">
                                                    <x-fas-ban />
                                                </button>
                                            @endif
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                class="d-inline"
                                                data-confirm="Are you sure you want to delete this user?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <x-fas-trash />
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- Ban Modal -->
                            <div class="modal fade" id="banModal{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning bg-opacity-10">
                                            <h5 class="modal-title">
                                                <x-fas-ban class="me-2 text-warning" />Ban User: {{ $user->name }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.users.ban', $user->id) }}" method="POST">
                                             @csrf
                                            <div class="modal-body">
                                                <div class="alert alert-warning">
                                                    <x-fas-exclamation-triangle class="me-2" />
                                                    This user will be immediately logged out and unable to access the system.
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Reason for Ban <span
                                                            class="text-danger">*</span></label>
                                                    <textarea class="form-control" name="reason" rows="3" required
                                                        placeholder="Enter the reason for banning this user..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-warning">
                                                    <x-fas-ban class="me-1" />Ban User
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="px-4 py-3 bg-transparent border-top" style="border-top: 1px solid rgba(0,0,0,0.05) !important;">
            {{ $users->links() }}
        </div>
        <div class="position-absolute" style="bottom: -20px; right: -20px; opacity: 0.03; pointer-events: none;">
            <x-fas-users-cog style="width: 150px; height: 150px;" />
        </div>
    </div>
@endsection