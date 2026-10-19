@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')
@section('page-subtitle', 'Track and manage admissions in real-time')

@section('content')
    <div class="row g-4">
        <!-- Status Cards -->
        <div class="col-xl-3 col-sm-6">
            <div class="premium-card h-100 p-3 position-relative overflow-hidden"
                style="background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(67, 97, 238, 0.05)); border: 1px solid rgba(67, 97, 238, 0.1);">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $stats['total'] ?? 0 }}</h4>
                        <small class="text-primary fw-bold text-uppercase"
                            style="font-size: 10px; letter-spacing: 0.5px;">Total Apps</small>
                    </div>
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow-sm"
                        style="width: 38px; height: 38px;">
                        <x-fas-file-invoice style="width: 14px; height: 14px;" />
                    </div>
                </div>
                <div class="position-absolute" style="bottom: -10px; right: -10px; opacity: 0.05;">
                    <x-fas-users style="width: 60px; height: 60px;" />
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="premium-card h-100 p-3 position-relative overflow-hidden"
                style="background: linear-gradient(135deg, rgba(255, 209, 102, 0.1), rgba(255, 209, 102, 0.05)); border: 1px solid rgba(255, 209, 102, 0.1);">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $stats['pending'] ?? 0 }}</h4>
                        <small class="text-warning fw-bold text-uppercase"
                            style="font-size: 10px; letter-spacing: 0.5px;">Pending Review</small>
                    </div>
                    <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center shadow-sm"
                        style="width: 38px; height: 38px;">
                        <x-fas-clock style="width: 14px; height: 14px;" />
                    </div>
                </div>
                <div class="position-absolute" style="bottom: -10px; right: -10px; opacity: 0.05;">
                    <x-fas-hourglass-half style="width: 60px; height: 60px;" />
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="premium-card h-100 p-3 position-relative overflow-hidden"
                style="background: linear-gradient(135deg, rgba(6, 214, 160, 0.1), rgba(6, 214, 160, 0.05)); border: 1px solid rgba(6, 214, 160, 0.1);">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $stats['accepted'] ?? 0 }}</h4>
                        <small class="text-success fw-bold text-uppercase"
                            style="font-size: 10px; letter-spacing: 0.5px;">Admitted</small>
                    </div>
                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center shadow-sm"
                        style="width: 38px; height: 38px;">
                        <x-fas-check-circle style="width: 14px; height: 14px;" />
                    </div>
                </div>
                <div class="position-absolute" style="bottom: -10px; right: -10px; opacity: 0.05;">
                    <x-fas-graduation-cap style="width: 60px; height: 60px;" />
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="premium-card h-100 p-3 position-relative overflow-hidden"
                style="background: linear-gradient(135deg, rgba(239, 71, 111, 0.1), rgba(239, 71, 111, 0.05)); border: 1px solid rgba(239, 71, 111, 0.1);">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $stats['rejected'] ?? 0 }}</h4>
                        <small class="text-danger fw-bold text-uppercase"
                            style="font-size: 10px; letter-spacing: 0.5px;">Rejected</small>
                    </div>
                    <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center shadow-sm"
                        style="width: 38px; height: 38px;">
                        <x-fas-times-circle style="width: 14px; height: 14px;" />
                    </div>
                </div>
                <div class="position-absolute" style="bottom: -10px; right: -10px; opacity: 0.05;">
                    <x-fas-user-slash style="width: 60px; height: 60px;" />
                </div>
            </div>
        </div>

        <!-- Redesigned Payment Stats -->
        <div class="col-lg-3 col-sm-6">
            <div class="premium-card h-100 p-3 position-relative overflow-hidden"
                style="background: linear-gradient(135deg, rgba(6, 214, 160, 0.1), rgba(6, 214, 160, 0.05)); border: 1px solid rgba(6, 214, 160, 0.1);">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $stats['paid'] ?? 0 }}</h4>
                        <small class="text-success fw-bold text-uppercase"
                            style="font-size: 10px; letter-spacing: 0.5px;">Paid Apps</small>
                    </div>
                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center shadow-sm"
                        style="width: 38px; height: 38px;">
                        <x-fas-check-double style="width: 14px; height: 14px;" />
                    </div>
                </div>
                <div class="position-absolute" style="bottom: -10px; right: -10px; opacity: 0.05;">
                    <x-fas-money-bill-wave style="width: 60px; height: 60px;" />
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="premium-card h-100 p-3 position-relative overflow-hidden"
                style="background: linear-gradient(135deg, rgba(255, 209, 102, 0.1), rgba(255, 209, 102, 0.05)); border: 1px solid rgba(255, 209, 102, 0.1);">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $stats['partial'] ?? 0 }}</h4>
                        <small class="text-warning fw-bold text-uppercase"
                            style="font-size: 10px; letter-spacing: 0.5px;">Partial</small>
                    </div>
                    <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center shadow-sm"
                        style="width: 38px; height: 38px;">
                        <x-fas-hand-holding-usd style="width: 14px; height: 14px;" />
                    </div>
                </div>
                <div class="position-absolute" style="bottom: -10px; right: -10px; opacity: 0.05;">
                    <x-fas-hand-holding-usd style="width: 60px; height: 60px;" />
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="premium-card h-100 p-3 position-relative overflow-hidden"
                style="background: linear-gradient(135deg, rgba(239, 71, 111, 0.1), rgba(239, 71, 111, 0.05)); border: 1px solid rgba(239, 71, 111, 0.1);">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $stats['unpaid'] ?? 0 }}</h4>
                        <small class="text-danger fw-bold text-uppercase"
                            style="font-size: 10px; letter-spacing: 0.5px;">Unpaid</small>
                    </div>
                    <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center shadow-sm"
                        style="width: 38px; height: 38px;">
                        <x-fas-exclamation-triangle style="width: 14px; height: 14px;" />
                    </div>
                </div>
                <div class="position-absolute" style="bottom: -10px; right: -10px; opacity: 0.05;">
                    <x-fas-exclamation-triangle style="width: 60px; height: 60px;" />
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="premium-card h-100 p-3 position-relative overflow-hidden"
                style="background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(67, 97, 238, 0.05)); border: 1px solid rgba(67, 97, 238, 0.1);">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">₹{{ number_format($stats['total_collected'] ?? 0) }}</h4>
                        <small class="text-primary fw-bold text-uppercase"
                            style="font-size: 10px; letter-spacing: 0.5px;">Collected</small>
                    </div>
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow-sm"
                        style="width: 38px; height: 38px;">
                        <x-fas-wallet style="width: 14px; height: 14px;" />
                    </div>
                </div>
                <div class="position-absolute" style="bottom: -10px; right: -10px; opacity: 0.05;">
                    <x-fas-university style="width: 60px; height: 60px;" />
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <!-- Trend Analysis -->
        <div class="col-xl-8">
            <div class="premium-card h-100 position-relative overflow-hidden"
                style="background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(67, 97, 238, 0.05)); border: 1px solid rgba(67, 97, 238, 0.1);">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="fw-bold mb-0" id="chartTitle">Registration Trend</h5>
                    <div class="btn-group btn-group-sm rounded-pill p-1 bg-white bg-opacity-10 shadow-sm" role="group">
                        <button type="button"
                            class="btn btn-transparent text-black-50 rounded-pill border-0 px-3 chart-toggle"
                            data-view="monthly">Monthly</button>
                        <button type="button"
                            class="btn btn-white rounded-pill border-0 px-3 active bg-primary text-white chart-toggle"
                            data-view="daily">Daily</button>
                    </div>
                </div>
                <div style="height: 350px;">
                    <canvas id="applicationsChart"></canvas>
                </div>
                <div class="position-absolute" style="bottom: -15px; right: -15px; opacity: 0.03; pointer-events: none;">
                    <x-fas-chart-area style="width: 150px; height: 150px;" />
                </div>
            </div>
        </div>

        <!-- Distribution -->
        <div class="col-xl-4">
            <div class="premium-card h-100 position-relative overflow-hidden"
                style="background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(67, 97, 238, 0.05)); border: 1px solid rgba(67, 97, 238, 0.1);">
                <h5 class="fw-bold mb-4">Application Sources</h5>
                <div style="height: 250px;">
                    <canvas id="formTypeChart"></canvas>
                </div>
                <div class="mt-4">
                    @foreach($formTypeStats as $item)
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="rounded-circle"
                                    style="width: 8px; height: 8px; background: {{ $loop->index == 0 ? '#4361ee' : ($loop->index == 1 ? '#06d6a0' : '#ffd166') }}"></span>
                                <span class="text-muted"
                                    style="font-size: 13px;">{{ $item->form_type == 'form1' ? 'Form 1' : ($item->form_type == 'form2' ? 'Form 2' : 'Form 3') }}</span>
                            </div>
                            <span class="fw-semibold" style="font-size: 13px;">{{ $item->count }}
                                ({{ $stats['total'] > 0 ? number_format(($item->count / $stats['total']) * 100, 1) : 0 }}%)</span>
                        </div>
                    @endforeach
                </div>
                <div class="position-absolute" style="bottom: -15px; right: -15px; opacity: 0.03; pointer-events: none;">
                    <x-fas-project-diagram style="width: 120px; height: 120px;" />
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <!-- Top Classes -->
        <div class="col-xl-4">
            <div class="premium-card h-100 position-relative overflow-hidden"
                style="background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(67, 97, 238, 0.05)); border: 1px solid rgba(67, 97, 238, 0.1);">
                <h5 class="fw-bold mb-4">Popular Classes</h5>
                <div class="list-group list-group-flush" style="--bs-list-group-bg: transparent;">
                    @forelse($topClasses as $class)
                        <div
                            class="list-group-item px-0 py-3 border-0 d-flex align-items-center justify-content-between bg-transparent">
                            <div class="d-flex align-items-center gap-3">
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">{{ $class->class_name ?? 'N/A' }}</h6>
                                    <small
                                        class="text-muted">{{ $class->form_type == 'form1' ? 'Lower Grade' : ($class->form_type == 'form2' ? 'Middle/High' : 'Senior') }}</small>
                                </div>
                            </div>
                            <span class="badge bg-primary rounded-pill px-3">{{ $class->total }} apps</span>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <x-fas-school class="text-muted mb-3 d-block mx-auto" style="width: 40px; height: 40px;" />
                            <p class="text-muted mb-0">No data available</p>
                        </div>
                    @endforelse
                </div>
                <div class="position-absolute" style="bottom: -15px; right: -15px; opacity: 0.03; pointer-events: none;">
                    <x-fas-award style="width: 120px; height: 120px;" />
                </div>
            </div>
        </div>

        <!-- Recent Applications -->
        <div class="col-xl-8">
            <div class="premium-card h-100 position-relative overflow-hidden"
                style="background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(67, 97, 238, 0.05)); border: 1px solid rgba(67, 97, 238, 0.1);">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="fw-bold mb-0">Recent Applications</h5>
                    <a href="{{ route('admin.applications') }}" class="btn btn-primary btn-sm rounded-pill px-3">View
                        All</a>
                </div>
                <div class="table-responsive bg-transparent">
                    <table class="table table-hover align-middle mb-0 bg-transparent"
                        style="--bs-table-bg: transparent; --bs-table-hover-bg: rgba(67, 97, 238, 0.05);">
                        <thead>
                            <tr>
                                <th class="border-0" style="font-size: 12px; font-weight: 600; text-transform: uppercase;">
                                    Student</th>
                                <th class="border-0" style="font-size: 12px; font-weight: 600; text-transform: uppercase;">
                                    Class</th>
                                <th class="border-0" style="font-size: 12px; font-weight: 600; text-transform: uppercase;">
                                    Type</th>
                                <th class="border-0" style="font-size: 12px; font-weight: 600; text-transform: uppercase;">
                                    Status</th>
                                <th class="border-0" style="font-size: 12px; font-weight: 600; text-transform: uppercase;">
                                    Date</th>
                                <th class="border-0" style="font-size: 12px; font-weight: 600; text-transform: uppercase;">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentApplications as $app)
                                <tr>
                                    <td>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 14px;">
                                                {{ $app->full_name ?? ($app->first_name . ' ' . $app->surname) }}
                                            </div>
                                            <small class="text-muted" style="font-size: 11px;">#{{ $app->id }}</small>
                                        </div>
                                    </td>
                                    <td style="font-size: 14px;">
                                        {{ $app->form_type == 'form1' ? ($app->admission_sought_for_class ?? 'N/A') : ($app->admission_class ?? 'N/A') }}
                                    </td>
                                    <td>
                                        <span
                                            class="badge rounded-pill bg-opacity-10 text-nowrap {{ $app->form_type == 'form1' ? 'bg-info text-info' : ($app->form_type == 'form2' ? 'bg-success text-success' : 'bg-warning text-warning') }}"
                                            style="font-size: 11px;">
                                            {{ strtoupper($app->form_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($app->status == 'accepted')
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3"
                                                style="font-size: 11px;">Admitted</span>
                                        @elseif($app->status == 'reviewed')
                                            <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3"
                                                style="font-size: 11px;">Application Accepted</span>
                                        @elseif($app->status == 'rejected')
                                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3"
                                                style="font-size: 11px;">Rejected</span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3"
                                                style="font-size: 11px;">Pending</span>
                                        @endif
                                    </td>
                                    <td class="text-muted" style="font-size: 13px;">{{ $app->created_at->format('M d, Y') }}
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button
                                                class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                                data-bs-toggle="dropdown" style="width: 32px; height: 32px;">
                                                <x-fas-ellipsis-v style="width: 12px; height: 12px;" />
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3">
                                                <li><a class="dropdown-item py-2"
                                                        href="{{ route('admin.application.view', $app->id) }}"><x-fas-eye
                                                            class="me-2 text-primary" /> View Details</a></li>
                                                <li><a class="dropdown-item py-2"
                                                        href="{{ route('admin.application.download', $app->id) }}"><x-fas-download
                                                            class="me-2 text-success" /> Download PDF</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="position-absolute" style="bottom: -15px; right: -15px; opacity: 0.03; pointer-events: none;">
                    <x-fas-file-alt style="width: 150px; height: 150px;" />
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Live Activity Feed Auto-Refresh
            function refreshActivityFeed() {
                fetch('/api/admin/activity/recent')
                    .then(response => response.json())
                    .then(activities => {
                        const feedContainer = document.getElementById('activityFeed');
                        if (!feedContainer) return;

                        if (activities.length === 0) {
                            feedContainer.innerHTML = `
                                        <div class="text-center py-5">
                                            <i class="fas fa-history text-muted mb-3 d-block" style="font-size: 40px;"></i>
                                            <p class="text-muted mb-0">No recent activity</p>
                                        </div>
                                    `;
                            return;
                        }

                        const escapeHtml = (str) => {
                            if (!str) return '';
                            return String(str)
                                .replace(/&/g, '&amp;')
                                .replace(/</g, '&lt;')
                                .replace(/>/g, '&gt;')
                                .replace(/"/g, '&quot;')
                                .replace(/'/g, '&#039;');
                        };

                        let html = '';
                        activities.forEach(activity => {
                            html += `
                                        <div class="activity-item d-flex gap-3 mb-3 pb-3 border-bottom">
                                            <div class="flex-shrink-0">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                                    style="width: 40px; height: 40px; background: linear-gradient(135deg, #4361ee, #4cc9f0); font-size: 14px;">
                                                    ${escapeHtml(activity.user_initial)}
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <h6 class="mb-0 fw-bold" style="font-size: 13px;">
                                                            ${escapeHtml(activity.user_name)}
                                                        </h6>
                                                        <small class="text-muted" style="font-size: 11px;">
                                                            ${escapeHtml(activity.user_role)}
                                                        </small>
                                                    </div>
                                                    <small class="text-muted" style="font-size: 10px;">
                                                        ${escapeHtml(activity.time_ago)}
                                                    </small>
                                                </div>
                                                <p class="mb-0 mt-1 text-muted" style="font-size: 12px; line-height: 1.5;">
                                                    <span class="badge bg-light text-dark me-1" style="font-size: 10px;">
                                                        ${escapeHtml(activity.activity)}
                                                    </span>
                                                    ${escapeHtml(activity.description)}
                                                </p>
                                                ${activity.ip_address ? `
                                                    <small class="text-muted" style="font-size: 10px;">
                                                        <i class="fas fa-map-marker-alt me-1"></i>${escapeHtml(activity.ip_address)}
                                                    </small>
                                                ` : ''}
                                            </div>
                                        </div>
                                    `;
                        });

                        feedContainer.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error refreshing activity feed:', error);
                    });
            }

            // Refresh activity feed every 60 seconds (only when tab is visible)
            setInterval(() => {
                if (!document.hidden) {
                    refreshActivityFeed();
                }
            }, 60000);

            document.addEventListener('DOMContentLoaded', function () {
                // Applications Trend Chart logic
                const ctx = document.getElementById('applicationsChart').getContext('2d');
                const monthlyData = @json($monthlyStats ?? []);
                const dailyData = @json($dailyStats ?? []);

                const monthlyLabels = monthlyData.map(item => {
                    const [year, month] = item.month.split('-');
                    return new Date(year, month - 1).toLocaleString('default', { month: 'short' });
                });
                const monthlyValues = monthlyData.map(item => item.count);

                const dailyLabels = dailyData.map(item => {
                    const date = new Date(item.date);
                    return date.getDate() + ' ' + date.toLocaleString('default', { month: 'short' });
                });
                const dailyValues = dailyData.map(item => item.count);

                const gradient = ctx.createLinearGradient(0, 0, 0, 350);
                gradient.addColorStop(0, 'rgba(67, 97, 238, 0.4)');
                gradient.addColorStop(1, 'rgba(67, 97, 238, 0)');

                let currentChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: dailyLabels,
                        datasets: [{
                            label: 'Applications',
                            data: dailyValues,
                            backgroundColor: gradient,
                            borderColor: '#4361ee',
                            borderWidth: 1,
                            borderRadius: 6,
                            barThickness: 'flex',
                            maxBarThickness: 40,
                            hoverBackgroundColor: '#4361ee',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                padding: 12,
                                titleFont: { size: 14, weight: 'bold' },
                                bodyFont: { size: 13 },
                                displayColors: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                                ticks: { font: { size: 11 }, stepSize: 1 }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 11 } }
                            }
                        }
                    }
                });

                // Toggle logic
                document.querySelectorAll('.chart-toggle').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const view = this.getAttribute('data-view');

                        // Update UI
                        document.querySelectorAll('.chart-toggle').forEach(b => b.classList.remove('active', 'bg-primary', 'text-white'));
                        this.classList.add('active', 'bg-primary', 'text-white');

                        // Update Chart
                        if (view === 'monthly') {
                            currentChart.data.labels = monthlyLabels;
                            currentChart.data.datasets[0].data = monthlyValues;
                        } else {
                            currentChart.data.labels = dailyLabels;
                            currentChart.data.datasets[0].data = dailyValues;
                        }
                        currentChart.update();
                    });
                });

                // Set initial active state removed (handled above)

                // Form Type Distribution Chart
                const formTypeCtx = document.getElementById('formTypeChart').getContext('2d');
                const formTypeData = @json($formTypeStats ?? []);

                const counts = formTypeData.map(item => item.count);

                new Chart(formTypeCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Form 1', 'Form 2', 'Form 3'],
                        datasets: [{
                            data: counts,
                            backgroundColor: ['#4361ee', '#06d6a0', '#ffd166'],
                            borderWidth: 0,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '80%',
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection