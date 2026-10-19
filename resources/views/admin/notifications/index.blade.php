@extends('admin.layout')

@section('title', 'Notifications')
@section('page-title', 'Notifications Center')
@section('page-subtitle', 'Stay updated with system alerts and reminders')

@section('content')
    <div class="row fade-in">
        <div class="col-12">
            <div class="premium-card p-0 overflow-hidden position-relative"
                style="background: linear-gradient(135deg, rgba(67, 97, 238, 0.05), rgba(67, 97, 238, 0.02)); border: 1px solid rgba(67, 97, 238, 0.1);">
                <div class="p-4 border-0 d-flex flex-wrap justify-content-between align-items-center gap-3 bg-white bg-opacity-25">
                    <div class="d-flex align-items-center gap-3">
                        <h5 class="fw-bold mb-0">Recent Alerts</h5>
                        @php
                            $unreadTotal = auth()->user()->unreadNotifications()->count();
                            $allTotal = auth()->user()->notifications()->count();
                        @endphp
                        <span id="unreadHeaderBadge" class="badge bg-primary rounded-pill px-3 py-2" style="font-size: 11px;">
                            {{ $unreadTotal }} Unread
                        </span>
                    </div>

                    <!-- Filter Tabs & Actions -->
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div class="btn-group p-1 bg-white bg-opacity-75 rounded-pill shadow-sm border" role="group">
                            <button type="button" class="btn btn-sm rounded-pill px-3 filter-btn active" data-filter="all">
                                All <span class="badge bg-secondary rounded-pill ms-1" id="filterCountAll">{{ $allTotal }}</span>
                            </button>
                            <button type="button" class="btn btn-sm rounded-pill px-3 filter-btn" data-filter="unread">
                                Unread <span class="badge bg-primary rounded-pill ms-1" id="filterCountUnread">{{ $unreadTotal }}</span>
                            </button>
                            <button type="button" class="btn btn-sm rounded-pill px-3 filter-btn" data-filter="read">
                                Read <span class="badge bg-light text-dark rounded-pill ms-1" id="filterCountRead">{{ $allTotal - $unreadTotal }}</span>
                            </button>
                        </div>

                        <button id="markAllAsReadBtn" class="btn btn-white bg-white bg-opacity-50 btn-sm rounded-pill px-3 border-0 shadow-sm">
                            <x-fas-check-double class="me-2 text-primary" /> Mark all as read
                        </button>
                    </div>
                </div>

                <div id="fullNotificationsList">
                    @php
                        $pendingApps = \App\Models\AdmissionQuery::where('status', 'pending')->count();
                    @endphp

                    @if($pendingApps > 0)
                        <div class="p-4 border-0 mb-3 mx-4 mt-3 rounded-4 priority-alert-card" style="background: linear-gradient(135deg, rgba(255, 209, 102, 0.15), rgba(255, 209, 102, 0.05)); border: 1px solid rgba(255, 209, 102, 0.25);">
                            <div class="d-flex gap-4 align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="rounded-4 d-flex align-items-center justify-content-center bg-warning text-white shadow-sm"
                                        style="width: 52px; height: 52px;">
                                        <x-fas-clock class="fa-lg" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h6 class="fw-bold mb-0" style="font-size: 16px;">Action Required: Pending Applications</h6>
                                        <span class="badge bg-warning text-dark rounded-pill px-3">High Priority</span>
                                    </div>
                                    <p class="mb-2 text-muted small">There are currently <strong>{{ $pendingApps }}</strong> student applications waiting for your review and processing.</p>
                                    <a href="{{ route('admin.applications', ['status' => 'pending']) }}"
                                        class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm">
                                        Process Applications <x-fas-arrow-right class="ms-2" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @forelse($notifications as $notification)
                        @php
                            $notifType = $notification->data['type'] ?? 'info';
                            $iconClass = match($notifType) {
                                'success' => 'bg-success',
                                'warning' => 'bg-warning',
                                'error', 'danger' => 'bg-danger',
                                default => 'bg-primary',
                            };
                            $isUnread = $notification->unread();
                        @endphp
                        <div class="p-4 border-bottom notification-item {{ $isUnread ? 'unread' : 'is-read' }} bg-transparent"
                            data-notification-id="{{ $notification->id }}"
                            data-status="{{ $isUnread ? 'unread' : 'read' }}"
                            style="transition: all 0.3s ease; border-bottom: 1px solid rgba(0,0,0,0.05) !important;">
                            <div class="d-flex gap-4">
                                <div class="flex-shrink-0">
                                    <div class="rounded-4 d-flex align-items-center justify-content-center {{ $iconClass }} text-white shadow-sm"
                                        style="width: 52px; height: 52px;">
                                        @if($notifType == 'success')
                                            <x-fas-check class="fa-lg" />
                                        @elseif($notifType == 'warning')
                                            <x-fas-exclamation class="fa-lg" />
                                        @elseif(in_array($notifType, ['error', 'danger']))
                                            <x-fas-times class="fa-lg" />
                                        @else
                                            <x-fas-info class="fa-lg" />
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="fw-bold mb-1" style="font-size: 16px;">
                                                {{ $notification->data['subject'] ?? ($notification->data['title'] ?? 'System Update') }}
                                            </h6>
                                            <div class="text-secondary d-flex align-items-center gap-2 small">
                                                <x-far-clock />
                                                <span title="{{ $notification->created_at->format('Y-m-d H:i:s') }}">
                                                    {{ $notification->created_at->format('M d, Y • h:i A') }} ({{ $notification->created_at->diffForHumans() }})
                                                </span>
                                                @if($isUnread)
                                                    <span class="badge bg-primary rounded-pill new-badge"
                                                        style="font-size: 9px; padding: 3px 8px;">New</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-white bg-white bg-opacity-50 btn-sm rounded-circle shadow-sm border-0"
                                                data-bs-toggle="dropdown" style="width: 32px; height: 32px;">
                                                <x-fas-ellipsis-v class="text-muted" />
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3">
                                                @if($isUnread)
                                                    <li class="mark-as-read-li">
                                                        <button class="dropdown-item py-2 mark-as-read"
                                                            data-id="{{ $notification->id }}"><x-fas-eye class="me-2 text-primary" /> Mark as read</button>
                                                    </li>
                                                @endif
                                                <li><button class="dropdown-item py-2 delete-notification"
                                                        data-id="{{ $notification->id }}"><x-fas-trash class="me-2 text-danger" /> Delete
                                                        Notification</button></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <p class="mb-3 text-muted" style="line-height: 1.6;">
                                        {{ $notification->data['message'] ?? '' }}</p>
                                    @if(isset($notification->data['action_url']) || isset($notification->data['url']))
                                        <a href="{{ $notification->data['action_url'] ?? $notification->data['url'] }}"
                                            class="btn btn-outline-primary btn-sm rounded-pill px-4">
                                            {{ $notification->data['action_text'] ?? 'View Details' }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-5 text-center empty-state-container">
                            <div class="mb-4">
                                <x-fas-bell-slash class="text-muted" style="font-size: 60px; opacity: 0.15;" />
                            </div>
                            <h5 class="fw-bold text-muted">No notifications to show</h5>
                            <p class="text-muted mb-0 small">We'll notify you when something important happens.</p>
                        </div>
                    @endforelse
                </div>

                @if($notifications->hasPages())
                    <div class="p-4 bg-transparent border-top" style="border-top: 1px solid rgba(0,0,0,0.05) !important;">
                        {{ $notifications->links() }}
                    </div>
                @endif
                <div class="position-absolute" style="bottom: -20px; right: -20px; opacity: 0.03; pointer-events: none;">
                    <x-fas-bell style="font-size: 150px;" />
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <style>
            .notification-item.unread {
                background-color: rgba(67, 97, 238, 0.04) !important;
                border-left: 3px solid #4361ee !important;
            }

            [data-bs-theme="dark"] .notification-item.unread {
                background-color: rgba(67, 97, 238, 0.12) !important;
                border-left: 3px solid #4361ee !important;
            }

            .filter-btn {
                border: none;
                color: #6c757d;
                font-weight: 500;
                font-size: 12px;
                transition: all 0.2s ease;
            }

            .filter-btn.active {
                background-color: #4361ee !important;
                color: #ffffff !important;
                box-shadow: 0 2px 6px rgba(67, 97, 238, 0.3);
            }

            .filter-btn.active .badge {
                background-color: #ffffff !important;
                color: #4361ee !important;
            }

            .btn-xs {
                padding: 0.2rem 0.5rem;
                font-size: 0.75rem;
            }
        </style>
        <script>
            $(document).ready(function () {
                // CSRF Header Setup
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Filter buttons
                $('.filter-btn').click(function () {
                    $('.filter-btn').removeClass('active');
                    $(this).addClass('active');

                    const filter = $(this).data('filter');
                    if (filter === 'all') {
                        $('.notification-item').fadeIn(200);
                    } else if (filter === 'unread') {
                        $('.notification-item[data-status="unread"]').fadeIn(200);
                        $('.notification-item[data-status="read"]').fadeOut(200);
                    } else if (filter === 'read') {
                        $('.notification-item[data-status="read"]').fadeIn(200);
                        $('.notification-item[data-status="unread"]').fadeOut(200);
                    }
                });

                function updateTabCounts() {
                    const unread = $('.notification-item[data-status="unread"]').length;
                    const read = $('.notification-item[data-status="read"]').length;
                    const total = unread + read;

                    $('#filterCountAll').text(total);
                    $('#filterCountUnread').text(unread);
                    $('#filterCountRead').text(read);
                    $('#unreadHeaderBadge').text(unread + ' Unread');

                    if (unread === 0) {
                        $('#unreadHeaderBadge').removeClass('bg-primary').addClass('bg-secondary');
                    }
                }

                // Mark All as Read
                $('#markAllAsReadBtn').click(function () {
                    const btn = $(this);
                    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Updating...');

                    $.ajax({
                        url: '{{ route("admin.notifications.mark-all-read") }}',
                        method: 'POST',
                        success: function () {
                            $('.notification-item').removeClass('unread').addClass('is-read').attr('data-status', 'read');
                            $('.notification-item .new-badge').remove();
                            $('.mark-as-read-li').remove();
                            updateTabCounts();
                            if (typeof updateNotificationCount === 'function') {
                                updateNotificationCount();
                            }
                            btn.prop('disabled', false).html('<i class="fas fa-check-double me-2 text-primary"></i> Mark all as read');
                        },
                        error: function () {
                            btn.prop('disabled', false).html('<i class="fas fa-check-double me-2 text-primary"></i> Mark all as read');
                        }
                    });
                });

                // Individual Mark as Read
                $(document).on('click', '.mark-as-read', function () {
                    const btn = $(this);
                    const notifId = btn.data('id');
                    const item = btn.closest('.notification-item');

                    $.ajax({
                        url: '/admin/notifications/' + notifId + '/read',
                        method: 'POST',
                        success: function () {
                            item.removeClass('unread').addClass('is-read').attr('data-status', 'read');
                            item.find('.new-badge').remove();
                            btn.closest('li').remove();
                            updateTabCounts();
                            if (typeof updateNotificationCount === 'function') {
                                updateNotificationCount();
                            }
                        }
                    });
                });

                // Delete Notification
                $(document).on('click', '.delete-notification', function () {
                    const btn = $(this);
                    const notifId = btn.data('id');
                    const item = btn.closest('.notification-item');

                    window.appConfirm('Are you sure you want to delete this notification?', {
                        title: 'Delete Notification',
                        confirmText: 'Yes, Delete',
                        icon: 'warning'
                    }).then(result => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '/admin/notifications/' + notifId,
                                method: 'DELETE',
                                success: function () {
                                    item.fadeOut(250, function () {
                                        $(this).remove();
                                        updateTabCounts();
                                        if ($('.notification-item').length === 0) {
                                            $('#fullNotificationsList').append(`
                                                <div class="p-5 text-center empty-state-container">
                                                    <div class="mb-4">
                                                        <svg class="bi-icon text-muted" style="width: 60px; height: 60px; opacity: 0.15;" fill="currentColor" viewBox="0 0 640 512"><path d="M633.8 458.1l-55-42.5c15.4-1.4 29.2-13.7 29.2-31.6 0-14.7-9.6-26.6-23-30.8V224c0-100.1-66.2-184.6-157.1-205.1C427.9 8.2 419 0 408.8 0h-57.6c-10.2 0-19.1 8.2-19.1 18.9-39.7 9-75.1 27.8-103.5 54.3L38.2 4.1C31.5-1 21.9-.3 16 5.8L4.1 18.2c-5.8 6.1-5.1 15.7 1.5 20.8l596.2 468.9c6.7 5.1 16.3 4.4 22.2-1.7l11.9-12.4c5.8-6.1 5.1-15.7-2.1-20.7zM380 448c0 35.3-28.7 64-64 64s-64-28.7-64-64h128z"/></svg>
                                                    </div>
                                                    <h5 class="fw-bold text-muted">No notifications to show</h5>
                                                    <p class="text-muted mb-0 small">We'll notify you when something important happens.</p>
                                                </div>
                                            `);
                                        }
                                    });
                                    if (typeof updateNotificationCount === 'function') {
                                        updateNotificationCount();
                                    }
                                }
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection