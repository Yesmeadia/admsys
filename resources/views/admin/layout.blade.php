<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <x-csrf-protection />
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">


    <!-- DNS Prefetch & Preconnect for Fast Admin Asset Delivery -->
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://cdn.datatables.net">
    <link rel="dns-prefetch" href="https://code.jquery.com">
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- jQuery (Moved to head for early availability) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Pusher for realtime -->
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <!-- SweetAlert2 for modern browser alerts & confirms -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #4f46e5;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
            --dark-color: #0f172a;
            --light-color: #f8fafc;
            --sidebar-width: 270px;
            --sidebar-collapsed-width: 80px;
            --header-height: 80px;
            --glass-bg: rgba(255, 255, 255, 0.9);
            --body-bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --sidebar-bg: transparent;
        }

        [data-bs-theme="dark"] {
            --glass-bg: rgba(30, 41, 59, 0.95);
            --body-bg: #0f172a;
            --card-bg: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --light-color: #1e293b;
            --sidebar-bg: transparent;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--body-bg);
            color: var(--text-main);
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .form-control,
        .form-select,
        .form-control:read-only {
            text-transform: uppercase !important;
        }

        .form-control[type="email"],
        .form-control[type="password"] {
            text-transform: none !important;
        }

        /* View detail values */
        .fw-semibold.text-dark,
        .fw-bold.text-dark {
            text-transform: uppercase;
        }

        /* Exceptions for labels and specific text */
        .page-title h1,
        .navbar-brand,
        .sidebar .logo,
        .small.text-muted,
        .text-muted.small {
            text-transform: none !important;
        }

        /* Premium Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--sidebar-bg);
            padding: 30px 20px;
            z-index: 1001;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            color: var(--text-main);
            box-shadow: none;
            border-right: none;
        }

        .sidebar .logo {
            padding: 0 15px;
            margin-bottom: 50px;
        }

        .sidebar .logo a {
            color: white;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 1px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar .nav-links {
            list-style: none;
            padding: 0;
        }

        .sidebar .nav-links li {
            margin-bottom: 8px;
        }

        .sidebar .nav-links a {
            display: flex;
            align-items: center;
            gap: 15px;
            color: var(--text-muted);
            text-decoration: none;
            padding: 14px 18px;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 15px;
        }

        .sidebar .nav-links a i {
            font-size: 18px;
            color: var(--primary-color);
            opacity: 0.7;
        }

        .sidebar .nav-links a:hover {
            background: rgba(99, 102, 241, 0.05);
            color: var(--primary-color);
            transform: translateX(8px);
        }

        .sidebar .nav-links a.active {
            background: var(--primary-color);
            color: white;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2);
        }

        .sidebar .nav-links a.active i {
            color: white;
            opacity: 1;
        }

        .sidebar .nav-links .logout-link {
            position: absolute;
            bottom: 30px;
            left: 20px;
            right: 20px;
        }

        .sidebar.collapsed .logout-link {
            left: 10px;
            right: 10px;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
            padding: 30px 15px;
        }

        .sidebar.collapsed .logo span,
        .sidebar.collapsed .nav-links span {
            display: none;
        }

        .sidebar.collapsed .logo {
            padding: 0;
            display: flex;
            justify-content: center;
        }

        .sidebar.collapsed .logo a {
            gap: 0;
            justify-content: center;
        }

        .sidebar.collapsed .logo a .logo-full {
            display: none;
            opacity: 0;
        }

        .sidebar.collapsed .logo a .logo-icon {
            display: block;
            opacity: 1;
            width: 45px;
            animation: fadeIn 0.4s ease;
        }

        .sidebar .logo a .logo-icon {
            display: none;
            opacity: 0;
        }

        .sidebar .logo a .logo-full {
            width: 100%;
            max-height: 60px;
            object-fit: contain;
            opacity: 1;
            padding: 0;
            border-radius: 0;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .sidebar.collapsed .nav-links a {
            justify-content: center;
            padding: 14px 0;
            gap: 0;
        }

        .sidebar.collapsed .nav-links a i {
            font-size: 20px;
        }

        .sidebar.collapsed .nav-links a:hover,
        .sidebar.collapsed .nav-links a.active {
            transform: scale(1.1);
        }

        .sidebar.collapsed .badge {
            display: none;
        }

        /* Main Area */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            display: flex;
            flex-direction: column;
        }

        .main-content.collapsed {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Modern Topbar */
        .topbar {
            height: var(--header-height);
            background: transparent;
            backdrop-filter: none;
            padding: 0 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: none;
            transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), background-color 0.3s ease;
        }

        .topbar.header-hidden {
            transform: translateY(-100%);
        }

        .topbar.header-scrolled {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }

        .topbar h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
        }

        .topbar p {
            font-size: 13px;
            color: var(--text-muted);
            margin: 0;
        }

        /* Content spacing */
        .content-area {
            padding: 40px;
            flex: 1;
        }

        /* Animation */
        .fade-in {
            animation: fadeIn 0.8s ease backwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Common Premium Card */
        .premium-card {
            background: var(--card-bg);
            border-radius: 24px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
            padding: 30px;
            margin-bottom: 30px;
            transition: transform 0.3s ease, background-color 0.3s ease;
            color: var(--text-main);
        }

        .premium-card:hover {
            transform: translateY(-5px);
        }

        .dashboard-footer {
            background: transparent;
            border-top: none !important;
            transition: all 0.3s ease;
        }

        [data-bs-theme="dark"] .dashboard-footer {
            border-top: none !important;
        }

        .hover-scale {
            transition: transform 0.3s ease;
        }

        .hover-scale:hover {
            transform: scale(1.1);
        }

        /* Responsive Sidebar */
        @media (max-width: 991.98px) {
            .sidebar {
                left: calc(-1 * var(--sidebar-width));
            }

            .sidebar.active {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .topbar {
                padding: 0 20px;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(3px);
                z-index: 1000;
                transition: all 0.3s ease;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }

        #sidebarToggle {
            cursor: pointer;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: var(--card-bg);
            border: none;
            transition: all 0.3s ease;
        }

        #sidebarToggle:hover {
            background: var(--light-color);
            transform: scale(1.05);
        }

        #sidebarToggle i {
            font-size: 20px;
            color: var(--text-main);
        }

        /* Premium Alerts */
        .premium-alert {
            background: var(--card-bg);
            border: none;
            border-radius: 1.25rem;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 1.25rem;
            position: relative;
            overflow: hidden;
            border-left: 6px solid #e5e7eb;
            transition: all 0.3s ease;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .premium-alert.alert-success {
            border-left-color: var(--success-color);
        }

        .premium-alert.alert-danger {
            border-left-color: var(--danger-color);
        }

        .premium-alert.alert-warning {
            border-left-color: var(--warning-color);
        }

        .premium-alert.alert-info {
            border-left-color: var(--info-color);
        }

        .alert-icon-wrapper {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .alert-success .alert-icon-wrapper {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .alert-danger .alert-icon-wrapper {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        .alert-warning .alert-icon-wrapper {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .alert-info .alert-icon-wrapper {
            background: rgba(99, 102, 241, 0.1);
            color: var(--info-color);
        }

        .alert-content-wrapper {
            flex-grow: 1;
        }

        .alert-content-wrapper h6 {
            margin: 0 0 0.15rem;
            font-weight: 700;
            color: var(--text-main);
            font-size: 1rem;
        }

        .alert-content-wrapper p,
        .alert-content-wrapper ul {
            margin: 0;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .alert-content-wrapper ul {
            padding-left: 1.2rem;
            margin-top: 0.5rem;
        }

        /* Premium SweetAlert2 Dialog Styling */
        div.swal2-container {
            z-index: 99999 !important;
        }
        .swal2-backdrop-show {
            background: rgba(15, 23, 42, 0.45) !important;
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
        }
        .premium-swal-popup {
            border-radius: 1.5rem !important;
            padding: 2.25rem 2rem !important;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25) !important;
            border: 1px solid rgba(226, 232, 240, 0.8) !important;
            font-family: 'Poppins', sans-serif !important;
            background: #ffffff !important;
            color: #0f172a !important;
            width: 28rem !important;
            max-width: 90vw !important;
        }
        [data-bs-theme="dark"] .premium-swal-popup {
            background: #1e293b !important;
            border-color: rgba(51, 65, 85, 0.8) !important;
            color: #f8fafc !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
        }
        .premium-swal-title {
            font-size: 1.25rem !important;
            font-weight: 700 !important;
            color: #0f172a !important;
            margin-bottom: 0.5rem !important;
            letter-spacing: -0.2px !important;
        }
        [data-bs-theme="dark"] .premium-swal-title {
            color: #f8fafc !important;
        }
        .premium-swal-text {
            font-size: 0.95rem !important;
            color: #64748b !important;
            line-height: 1.55 !important;
            margin: 0.5rem 0 1.5rem !important;
        }
        [data-bs-theme="dark"] .premium-swal-text {
            color: #94a3b8 !important;
        }
        .swal2-icon {
            border-width: 3px !important;
            transform: scale(0.9) !important;
            margin: 0.5rem auto 1.25rem !important;
        }
        .swal2-actions {
            margin-top: 1.25rem !important;
            gap: 0.75rem !important;
        }
        .premium-swal-btn-confirm {
            background: linear-gradient(135deg, #6366f1, #4f46e5) !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 9999px !important;
            padding: 0.65rem 1.6rem !important;
            font-weight: 600 !important;
            font-size: 0.9rem !important;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.35) !important;
            transition: all 0.2s ease !important;
        }
        .premium-swal-btn-confirm:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 18px rgba(99, 102, 241, 0.45) !important;
        }
        .premium-swal-btn-cancel {
            background: #f1f5f9 !important;
            color: #475569 !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 9999px !important;
            padding: 0.65rem 1.6rem !important;
            font-weight: 600 !important;
            font-size: 0.9rem !important;
            transition: all 0.2s ease !important;
        }
        [data-bs-theme="dark"] .premium-swal-btn-cancel {
            background: #334155 !important;
            color: #e2e8f0 !important;
            border-color: #475569 !important;
        }
        .premium-swal-btn-cancel:hover {
            background: #e2e8f0 !important;
        }
        [data-bs-theme="dark"] .premium-swal-btn-cancel:hover {
            background: #475569 !important;
        }

        /* Blade Icons SVG styling */
        svg.bi-icon, .svg-icon, .sidebar .nav-links a svg, .topbar svg, .dropdown-menu svg {
            display: inline-block;
            vertical-align: -0.125em;
            width: 1em;
            height: 1em;
            fill: currentColor;
        }
        .sidebar .nav-links a svg {
            width: 18px;
            height: 18px;
            color: var(--primary-color);
            opacity: 0.7;
            transition: all 0.3s ease;
        }
        .sidebar .nav-links a:hover svg,
        .sidebar .nav-links a.active svg {
            opacity: 1;
        }
        .sidebar .nav-links a.active svg {
            color: white;
        }
    </style>
    @stack('styles')
</head>

<body>
    @php
        $unreadNotifs = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0;
        $pendingApps = \App\Models\AdmissionQuery::where('status', 'pending')->count();
        $totalAlerts = $unreadNotifs + ($pendingApps > 0 ? 1 : 0);
    @endphp
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <a href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('images/logo.png') }}" class="logo-full" alt="Full Logo">
                <img src="{{ asset('images/favicon.png') }}" class="logo-icon" alt="Icon"
                    style="width: 45px; height: 45px; object-fit: contain;">
            </a>
        </div>

        <ul class="nav-links">
            <li>
                <a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <x-fas-th-large />
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.applications') }}"
                    class="{{ request()->routeIs('admin.applications', 'admin.applications.*') ? 'active' : '' }}">
                    <x-fas-users />
                    <span>Applications</span>
                    @php
                        $pendingCount = App\Models\AdmissionQuery::where('status', 'pending')->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="badge bg-warning ms-auto" id="pendingApplicationsBadge"
                            style="font-size: 10px; padding: 4px 8px;">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('admin.statistics') }}"
                    class="{{ request()->routeIs('admin.statistics') ? 'active' : '' }}">
                    <x-fas-chart-pie />
                    <span>Statistics & Reports</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.notifications.index') }}"
                    class="{{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                    <x-fas-bell />
                    <span>Notifications</span>
                    @if($totalAlerts > 0)
                        <span class="badge bg-danger ms-auto" style="font-size: 10px; padding: 4px 8px;">
                            {{ $totalAlerts }}
                        </span>
                    @endif
                </a>
            </li>
            @if(auth()->user()->isAdmin())
                <li>
                    <a href="{{ route('admin.users.index') }}"
                        class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <x-fas-users-cog />
                        <span>User Management</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.settings') }}"
                        class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                        <x-fas-cog />
                        <span>System Settings</span>
                    </a>
                </li>
            @endif
            @if(auth()->user()->isAdmin())
                <li>
                    <a href="{{ route('admin.activity-logs.index') }}"
                        class="{{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                        <x-fas-history />
                        <span>Activity Logs</span>
                    </a>
                </li>
            @endif
            <li>
                <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <x-fas-user-circle />
                    <span>My Account</span>
                </a>
            </li>
            <li class="logout-link">
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    style="color: #ef476f;">
                    <x-fas-power-off style="color: #ef476f;" />
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle">
                    <x-fas-bars />
                </button>
                <div class="page-title d-none d-md-block">
                    <h1>@yield('page-title', 'Dashboard Overview')</h1>
                    <p>@yield('page-subtitle', 'Welcome back, ' . auth()->user()->name)</p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- Theme Toggle -->
                <button class="btn btn-light rounded-circle shadow-sm" id="themeToggle"
                    style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                    <x-fas-moon class="text-dark" />
                </button>

                <div class="dropdown">
                    <button class="btn btn-light rounded-circle shadow-sm position-relative" data-bs-toggle="dropdown"
                        id="notificationBell"
                        style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                        <x-fas-bell class="text-dark" />
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            id="notificationCount"
                            style="font-size: 9px; padding: 3px 6px; {{ $totalAlerts == 0 ? 'display:none;' : '' }}">
                            {{ $totalAlerts }}
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end notification-dropdown premium-card p-0 overflow-hidden"
                        style="width: 350px;">
                        <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
                            <h6 class="mb-0 fw-bold">Notifications</h6>
                            @if($unreadNotifs > 0)
                                <button class="btn btn-link btn-xs p-0 text-primary text-decoration-none"
                                    id="markAllAsRead">Mark all as read</button>
                            @endif
                        </div>
                        <div id="notificationsList" style="max-height: 400px; overflow-y: auto;">
                            @if($pendingApps > 0)
                                <div class="p-3 border-bottom bg-warning bg-opacity-10">
                                    <div class="d-flex gap-3">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning text-white"
                                                style="width: 40px; height: 40px;">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold" style="font-size: 14px;">Pending Applications</h6>
                                            <p class="mb-1 text-muted" style="font-size: 12px;">There are
                                                <strong>{{ $pendingApps }}</strong> applications awaiting review.
                                            </p>
                                            <a href="{{ route('admin.applications', ['status' => 'pending']) }}"
                                                class="btn btn-xs btn-primary py-1 px-2 mt-1"
                                                style="font-size: 10px;">Review Now</a>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                                <div class="p-3 border-bottom notification-item {{ $notification->unread() ? 'unread' : '' }}"
                                    data-notification-id="{{ $notification->id }}"
                                    style="transition: all 0.3s ease; cursor: pointer;">
                                    <div class="d-flex gap-3">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px; background: {{ $notification->data['type'] == 'success' ? '#06d6a0' : ($notification->data['type'] == 'warning' ? '#ffd166' : ($notification->data['type'] == 'error' ? '#ef476f' : '#4361ee')) }}; color: white;">
                                                <i class="fas {{ $notification->data['type'] == 'success' ? 'fa-check' : ($notification->data['type'] == 'warning' ? 'fa-exclamation' : ($notification->data['type'] == 'error' ? 'fa-times' : 'fa-info')) }}"
                                                    style="font-size: 14px;"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold" style="font-size: 14px;">
                                                {{ $notification->data['subject'] ?? 'Notification' }}
                                            </h6>
                                            <p class="mb-1 text-muted" style="font-size: 12px; line-height: 1.4;">
                                                {{ Str::limit($notification->data['message'] ?? '', 80) }}
                                            </p>
                                            <small class="text-secondary"
                                                style="font-size: 10px;">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-muted">
                                    <i class="bi bi-bell-slash mb-2 d-block" style="font-size: 24px;"></i>
                                    <p class="mb-0">No new notifications</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="p-2 text-center bg-light">
                            <a href="{{ route('admin.notifications.index') }}"
                                class="text-primary text-decoration-none fw-semibold" style="font-size: 13px;">View All
                                Notifications</a>
                        </div>
                    </div>
                </div>

                <!-- Profile -->
                <div class="dropdown">
                    <div class="d-flex align-items-center gap-3 ps-3 border-start" data-bs-toggle="dropdown"
                        style="cursor: pointer;">
                        <div class="text-end d-none d-lg-block">
                            <h6 class="mb-0 fw-bold" style="font-size: 14px;">{{ auth()->user()->name }}</h6>
                            <small class="text-muted" style="font-size: 11px;">
                                {{ auth()->user()->isAdmin() ? 'Administrator' : 'Office Staff' }}
                            </small>
                        </div>
                        <div class="position-relative">
                            <div class="rounded-circle overflow-hidden shadow-sm border border-2 border-white"
                                style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
                                @if(auth()->user()->profile_photo_url)
                                    <img src="{{ auth()->user()->profile_photo_url }}" class="w-100 h-100 object-fit-cover">
                                @else
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                @endif
                            </div>
                            <span
                                class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-2 border-white"
                                style="width: 14px; height: 14px;"></span>
                        </div>
                    </div>
                    <div class="dropdown-menu dropdown-menu-end premium-card p-0 overflow-hidden" style="width: 250px;">
                        <div class="p-3 border-bottom text-center bg-light">
                            <h6 class="mb-0 fw-bold">{{ auth()->user()->name }}</h6>
                            <small class="text-muted">{{ auth()->user()->email }}</small>
                        </div>
                        <div class="p-2">
                            <a class="dropdown-item rounded-3 py-2" href="{{ route('profile.edit') }}">
                                My Profile
                            </a>
                            <a class="dropdown-item rounded-3 py-2" href="{{ route('admin.settings') }}">
                                Settings
                            </a>
                        </div>
                        <div class="p-2 border-top">
                            <form id="logout-form" method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item rounded-3 py-2 text-danger">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Sound -->
        <audio id="notificationSound" src="{{ asset('audio/notification.mp3') }}" preload="auto"></audio>


        <!-- Content Area -->
        <div class="content-area">
            <!-- Flash Messages -->
            @if(session('status'))
                <div class="alert premium-alert alert-success shadow-sm fade show" role="alert">
                    <div class="alert-icon-wrapper">
                        <x-fas-check />
                    </div>
                    <div class="alert-content-wrapper">
                        <h6>Status</h6>
                        <p>{{ session('status') == 'password-updated' ? 'Password updated successfully!' : session('status') }}</p>
                    </div>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert premium-alert alert-success shadow-sm fade show" role="alert">
                    <div class="alert-icon-wrapper">
                        <x-fas-check />
                    </div>
                    <div class="alert-content-wrapper">
                        <h6>Success</h6>
                        <p>{{ session('success') }}</p>
                    </div>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert premium-alert alert-danger shadow-sm fade show" role="alert">
                    <div class="alert-icon-wrapper">
                        <x-fas-exclamation-triangle />
                    </div>
                    <div class="alert-content-wrapper">
                        <h6>Error</h6>
                        <p>{{ session('error') }}</p>
                    </div>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert premium-alert alert-danger shadow-sm fade show" role="alert">
                    <div class="alert-icon-wrapper">
                        <x-fas-times-circle />
                    </div>
                    <div class="alert-content-wrapper">
                        <h6>Validation Error</h6>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Page Content -->
            <div class="fade-in">
                @yield('content')
            </div>
        </div>
        <!-- Footer -->
        <footer class="dashboard-footer mt-auto py-3 px-4 border-top">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div class="text-muted small">
                    &copy; {{ date('Y') }} <span class="fw-bold">YES INDIA FOUNDATION</span>. All rights reserved.
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small">Designed & Developed by</span>
                    <a href="https://cyberduce.com" target="_blank" class="transition-base hover-scale">
                        <img src="{{ asset('images/cyberduce.png') }}" alt="Cyberduce Logo" style="height: 22px;">
                    </a>
                </div>
            </div>
        </footer>
    </div>

    <!-- Notification Sound -->


    <!-- Logout Form -->
    <form id="logout-form" method="POST" action="{{ route('admin.logout') }}" style="display: none;">
        @csrf
    </form>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-dismiss alerts
            setTimeout(function () {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Mobile menu toggle logic
            const sidebar = document.querySelector('.sidebar');
            const sidebarBtn = document.getElementById('sidebarToggle');
            const overlay = document.getElementById('sidebarOverlay');
            const mainContent = document.querySelector('.main-content');

            // Initialize sidebar state from localStorage
            if (window.innerWidth >= 992) {
                const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
                if (isCollapsed) {
                    sidebar.classList.add('collapsed');
                    mainContent.classList.add('collapsed');
                }
            }

            function toggleSidebar() {
                if (window.innerWidth < 992) {
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('show');
                } else {
                    const willCollapse = !sidebar.classList.contains('collapsed');
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('collapsed');
                    localStorage.setItem('sidebar-collapsed', willCollapse);
                }
            }

            if (sidebarBtn) {
                sidebarBtn.addEventListener('click', toggleSidebar);
            }

            if (overlay) {
                overlay.addEventListener('click', toggleSidebar);
            }

            // Close sidebar when clicking outside on mobile (redundant with overlay but safe)
            document.addEventListener('click', function (e) {
                if (window.innerWidth < 992 &&
                    !sidebar.contains(e.target) &&
                    sidebarBtn && !sidebarBtn.contains(e.target) &&
                    overlay && !overlay.contains(e.target) &&
                    sidebar.classList.contains('active')) {
                    toggleSidebar();
                }
            });

            // Theme Toggle
            const themeToggle = document.getElementById('themeToggle');
            const currentTheme = localStorage.getItem('theme') || 'light';

            if (currentTheme === 'dark') {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
                themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            } else {
                themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            }

            themeToggle.addEventListener('click', function () {
                const currentTheme = document.documentElement.getAttribute('data-bs-theme');
                if (currentTheme === 'dark') {
                    document.documentElement.setAttribute('data-bs-theme', 'light');
                    localStorage.setItem('theme', 'light');
                    themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
                } else {
                    document.documentElement.setAttribute('data-bs-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                    themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
                }
            });

            // Notification handling
            const notificationSound = document.getElementById('notificationSound');

            // Mark notification as read
            $(document).on('click', '.mark-as-read', function () {
                const notificationId = $(this).data('id');
                const notificationItem = $(this).closest('.notification-item');

                $.ajax({
                    url: '/admin/notifications/' + notificationId + '/read',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        notificationItem.removeClass('unread');
                        updateNotificationCount();
                    }
                });
            });

            // Delete notification
            $(document).on('click', '.delete-notification', function () {
                const notificationId = $(this).data('id');
                const notificationItem = $(this).closest('.notification-item');

                $.ajax({
                    url: '/admin/notifications/' + notificationId,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        notificationItem.remove();
                        updateNotificationCount();
                    }
                });
            });

            // Mark all as read
            $('#markAllAsRead').click(function (e) {
                e.preventDefault();

                $.ajax({
                    url: '/admin/notifications/mark-all-read',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $('.notification-item').removeClass('unread');
                        updateNotificationCount();
                    }
                });
            });

            function updateNotificationCount() {
                $.ajax({
                    url: '{{ route("admin.notifications.count.unread") }}',
                    method: 'GET',
                    success: function (response) {
                        const unreadNotifs = response.count;

                        $.ajax({
                            url: '{{ route("admin.applications.count.pending") }}',
                            method: 'GET',
                            success: function (res) {
                                const pendingApps = res.count;
                                const total = unreadNotifs + (pendingApps > 0 ? 1 : 0);

                                $('#notificationCount').text(total);
                                if (total === 0) {
                                    $('#notificationCount').hide();
                                } else {
                                    $('#notificationCount').show();
                                }
                            }
                        });
                    }
                });
            }

            // Global AJAX setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Initialize real-time notifications (Pusher)
            initializeRealtimeNotifications();

            function initializeRealtimeNotifications() {
                // Check if Pusher is available
                if (typeof Pusher === 'undefined') {
                    console.log('Pusher not loaded');
                    return;
                }

                // Enable pusher logging - don't include this in production
                // Pusher.logToConsole = true;

                // Initialize Pusher with your credentials
                const pusher = new Pusher('{{ config("broadcasting.connections.pusher.key") }}', {
                    cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}',
                    authEndpoint: '/broadcasting/auth'
                });

                // Subscribe to the user's private channel
                const channel = pusher.subscribe('private-user.{{ auth()->id() }}');

                // Listen for new notification events
                channel.bind('notification.received', function (data) {
                    console.log('New notification received:', data);

                    // Play sound
                    const sound = document.getElementById('notificationSound');
                    if (sound) {
                        sound.play().catch(e => console.log('Sound play error:', e));
                    }

                    // Update notification count
                    const countElement = document.getElementById('notificationCount');
                    let currentCount = parseInt(countElement.textContent) || 0;
                    countElement.textContent = currentCount + 1;
                    countElement.style.display = 'flex';

                    // Add notification to dropdown
                    addNotificationToDropdown(data.notification);

                    // Update pending applications badge if it's a new application
                    if (data.notification.type === 'new_application') {
                        updatePendingApplicationsCount();
                    }

                    // Show desktop notification if browser supports it
                    if ("Notification" in window && Notification.permission === "granted") {
                        new Notification(data.notification.subject, {
                            body: data.notification.message,
                            icon: '{{ asset("images/favicon.png") }}'
                        });
                    }

                    // Animate the notification bell
                    const bell = document.getElementById('notificationBell');
                    bell.classList.add('pulse');
                    setTimeout(() => bell.classList.remove('pulse'), 1000);
                });

                // Listen for application status updates
                channel.bind('application.updated', function (data) {
                    console.log('Application updated:', data);
                    updatePendingApplicationsCount();
                });

                // Request notification permission
                if ("Notification" in window && Notification.permission === "default") {
                    Notification.requestPermission();
                }
            }

            function addNotificationToDropdown(notification) {
                const notificationsList = document.getElementById('notificationsList');

                // Create notification item
                const notificationItem = document.createElement('div');
                notificationItem.className = 'notification-item unread';
                notificationItem.setAttribute('data-notification-id', notification.id);

                const iconClass = notification.type === 'success' ? 'bg-success' :
                    notification.type === 'warning' ? 'bg-warning' :
                        notification.type === 'error' ? 'bg-danger' : 'bg-primary';

                const iconIcon = notification.type === 'success' ? 'fa-check' :
                    notification.type === 'warning' ? 'fa-exclamation' :
                        notification.type === 'error' ? 'fa-times' : 'fa-info';

                const escapeHtml = (str) => {
                    if (!str) return '';
                    return String(str)
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;');
                };

                const safeSubject = escapeHtml(notification.subject || 'Notification');
                const rawMsg = notification.message || '';
                const safeMessage = escapeHtml(rawMsg.length > 60 ? rawMsg.substring(0, 60) + '...' : rawMsg);
                const safeActionUrl = notification.action_url ? encodeURI(notification.action_url) : '';

                notificationItem.innerHTML = `
                <div class="d-flex align-items-start gap-3">
                    <div class="notification-icon ${iconClass}">
                        <i class="fas ${iconIcon}"></i>
                    </div>
                    <div class="notification-content flex-grow-1">
                        <div class="d-flex justify-content-between">
                            <strong>${safeSubject}</strong>
                            <small class="notification-time">Just now</small>
                        </div>
                        <p class="mb-1" style="font-size: 13px;">
                            ${safeMessage}
                        </p>
                        ${safeActionUrl ? `
                        <a href="${safeActionUrl}" class="btn btn-sm btn-outline-primary btn-xs mt-1">
                            View Details
                        </a>
                        ` : ''}
                    </div>
                    <div class="notification-actions">
                        <button class="btn btn-sm btn-link text-success mark-as-read" data-id="${escapeHtml(notification.id)}">
                            <i class="fas fa-check"></i>
                        </button>
                        <button class="btn btn-sm btn-link text-danger delete-notification" data-id="${escapeHtml(notification.id)}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;

                // Add to top of list
                if (notificationsList.firstChild) {
                    notificationsList.insertBefore(notificationItem, notificationsList.firstChild);
                } else {
                    notificationsList.appendChild(notificationItem);
                }

                // Remove "no notifications" message if present
                const noNotifications = notificationsList.querySelector('.text-center');
                if (noNotifications) {
                    noNotifications.remove();
                }
            }

            function updatePendingApplicationsCount() {
                $.ajax({
                    url: '{{ route("admin.applications.count.pending") }}',
                    method: 'GET',
                    success: function (response) {
                        const badge = document.getElementById('pendingApplicationsBadge');
                        if (badge) {
                            badge.textContent = response.count;
                            if (response.count > 0) {
                                badge.classList.add('pulse');
                            } else {
                                badge.classList.remove('pulse');
                            }
                        }
                    }
                });
            }

            // Update pending applications count every 60 seconds (only when tab is visible)
            setInterval(() => {
                if (!document.hidden) {
                    updatePendingApplicationsCount();
                }
            }, 60000);

            // Smart Header Scroll Logic
            let lastScrollTop = 0;
            const topbar = document.querySelector('.topbar');
            const scrollThreshold = 10;

            window.addEventListener('scroll', function () {
                let st = window.pageYOffset || document.documentElement.scrollTop;

                // Add glass effect background when scrolled
                if (st > 20) {
                    topbar.classList.add('header-scrolled');
                } else {
                    topbar.classList.remove('header-scrolled');
                }

                // Hide/Show logic
                if (Math.abs(lastScrollTop - st) <= scrollThreshold) return;

                if (st > lastScrollTop && st > topbar.offsetHeight) {
                    // Scroll Down
                    topbar.classList.add('header-hidden');
                } else {
                    // Scroll Up
                    topbar.classList.remove('header-hidden');
                }

                lastScrollTop = st;
            }, { passive: true });
        });

        // CSRF Token Keep-Alive & Refresh
        function refreshCsrfToken() {
            fetch('/refresh-csrf')
                .then(response => response.json())
                .then(data => {
                    // Update all CSRF inputs
                    document.querySelectorAll('input[name="_token"]').forEach(input => {
                        input.value = data.csrf_token;
                    });

                    // Update meta tag
                    const metaTag = document.querySelector('meta[name="csrf-token"]');
                    if (metaTag) metaTag.content = data.csrf_token;

                    // Update global JS variable from x-csrf-protection component
                    if (window.CSRF_TOKEN) window.CSRF_TOKEN = data.csrf_token;

                    console.log('CSRF Token Refreshed');
                })
                .catch(error => console.error('Token refresh failed:', error));
        }

        // Refresh token every 15 minutes
        setInterval(refreshCsrfToken, 15 * 60 * 1000);

        // Premium SweetAlert2 Dialog System & Native Alert Interceptor
        window.appConfirm = function(message, options = {}) {
            return Swal.fire({
                title: options.title || 'Are you sure?',
                text: message,
                icon: options.icon || 'warning',
                showCancelButton: true,
                confirmButtonText: options.confirmText || 'Yes, Proceed',
                cancelButtonText: options.cancelText || 'Cancel',
                reverseButtons: true,
                customClass: {
                    popup: 'premium-swal-popup',
                    title: 'premium-swal-title',
                    htmlContainer: 'premium-swal-text',
                    confirmButton: 'premium-swal-btn-confirm',
                    cancelButton: 'premium-swal-btn-cancel'
                },
                buttonsStyling: false
            });
        };

        window.appAlert = function(message, options = {}) {
            return Swal.fire({
                title: options.title || 'Notification',
                text: message,
                icon: options.icon || 'info',
                confirmButtonText: options.confirmText || 'Got it',
                customClass: {
                    popup: 'premium-swal-popup',
                    title: 'premium-swal-title',
                    htmlContainer: 'premium-swal-text',
                    confirmButton: 'premium-swal-btn-confirm'
                },
                buttonsStyling: false
            });
        };

        // Intercept native submit events for forms with data-confirm or onsubmit="return confirm(...)"
        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (form.dataset.confirmed === 'true') {
                form.removeAttribute('data-confirmed');
                return;
            }

            let confirmMsg = form.getAttribute('data-confirm');
            const onsubmitAttr = form.getAttribute('onsubmit');
            if (!confirmMsg && onsubmitAttr && onsubmitAttr.includes('confirm(')) {
                const match = onsubmitAttr.match(/confirm\(\s*['"`](.*?)['"`]\s*\)/);
                if (match && match[1]) {
                    confirmMsg = match[1];
                }
            }

            if (confirmMsg) {
                e.preventDefault();
                e.stopImmediatePropagation();

                window.appConfirm(confirmMsg, {
                    title: 'Confirm Action',
                    confirmText: 'Yes, Proceed'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.dataset.confirmed = 'true';
                        HTMLFormElement.prototype.submit.call(form);
                    }
                });
            }
        }, true);

        // Intercept click events on buttons/links with data-confirm or onclick="return confirm(...)"
        document.addEventListener('click', function(e) {
            const el = e.target.closest('a, button');
            if (!el) return;

            if (el.dataset.confirmed === 'true') {
                el.removeAttribute('data-confirmed');
                return;
            }

            let confirmMsg = el.getAttribute('data-confirm');
            const onclickAttr = el.getAttribute('onclick');
            if (!confirmMsg && onclickAttr && onclickAttr.includes('confirm(')) {
                const match = onclickAttr.match(/confirm\(\s*['"`](.*?)['"`]\s*\)/);
                if (match && match[1]) {
                    confirmMsg = match[1];
                }
            }

            if (confirmMsg) {
                e.preventDefault();
                e.stopImmediatePropagation();

                window.appConfirm(confirmMsg, {
                    title: 'Confirm Action',
                    confirmText: 'Yes, Proceed'
                }).then((result) => {
                    if (result.isConfirmed) {
                        el.dataset.confirmed = 'true';
                        if (el.tagName.toLowerCase() === 'a') {
                            window.location.href = el.href;
                        } else if (el.type === 'submit') {
                            const form = el.closest('form');
                            if (form) {
                                form.dataset.confirmed = 'true';
                                HTMLFormElement.prototype.submit.call(form);
                            }
                        } else {
                            el.click();
                        }
                    }
                });
            }
        }, true);

        // Auto-dismiss layout flash messages after 6 seconds with smooth fade
        setTimeout(() => {
            document.querySelectorAll('.premium-alert').forEach(alertEl => {
                alertEl.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                alertEl.style.opacity = '0';
                alertEl.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    if (alertEl.parentNode) alertEl.remove();
                }, 600);
            });
        }, 6000);
    </script>

    @stack('scripts')
</body>

</html>