@extends('admin.layout')

@section('title', 'Advanced Statistics & Reports')
@section('page-title', 'Reports & Analytics')
@section('page-subtitle', 'Detailed insights into your admission system')

@section('content')
    <div class="container-fluid">
        <!-- Key Metrics -->
        <div class="row g-3 fade-in mb-4">
            @php
                $statsConfig = [
                    'total' => [
                        'label' => 'Total Intake',
                        'value' => $stats['total'],
                        'sub' => 'Combined all forms',
                        'color' => 'primary',
                        'icon' => 'fa-users',
                    ],
                    'conversion' => [
                        'label' => 'Conversion Rate',
                        'value' => ($stats['total'] > 0 ? round(($stats['accepted'] / $stats['total']) * 100, 1) : 0) . '%',
                        'sub' => $stats['accepted'] . ' applications admitted',
                        'color' => 'success',
                        'icon' => 'fa-chart-line',
                    ],
                    'pipeline' => [
                        'label' => 'Review Pipeline',
                        'value' => $stats['pending'],
                        'sub' => 'Pending initial screening',
                        'color' => 'warning',
                        'icon' => 'fa-clock',
                    ],
                    'rejection' => [
                        'label' => 'Rejection Rate',
                        'value' => ($stats['total'] > 0 ? round(($stats['rejected'] / $stats['total']) * 100, 1) : 0) . '%',
                        'sub' => $stats['rejected'] . ' applications rejected',
                        'color' => 'danger',
                        'icon' => 'fa-user-times',
                    ],
                ];

                $gradients = [
                    'primary' => 'linear-gradient(135deg, rgba(67, 97, 238, 0.15), rgba(67, 97, 238, 0.05))',
                    'success' => 'linear-gradient(135deg, rgba(6, 214, 160, 0.15), rgba(6, 214, 160, 0.05))',
                    'warning' => 'linear-gradient(135deg, rgba(255, 209, 102, 0.15), rgba(255, 209, 102, 0.05))',
                    'danger' => 'linear-gradient(135deg, rgba(239, 71, 111, 0.15), rgba(239, 71, 111, 0.05))',
                ];
            @endphp

            @foreach($statsConfig as $key => $config)
                <div class="col-xl-3 col-md-6">
                    <div class="premium-card h-100 p-4 position-relative overflow-hidden mb-0"
                        style="background: {{ $gradients[$config['color']] }}; border: 1px solid rgba(var(--bs-{{ $config['color'] }}-rgb), 0.1);">
                        <div class="position-relative z-1">
                            <small class="text-{{ $config['color'] }} text-uppercase fw-bold"
                                style="font-size: 10px; letter-spacing: 1px;">{{ $config['label'] }}</small>
                            <h2 class="display-6 fw-bold mb-1 mt-1 text-dark">{{ $config['value'] }}</h2>
                            <p class="text-muted mb-0 small">{{ $config['sub'] }}</p>
                        </div>
                        <div class="position-absolute" style="bottom: -15px; right: -15px; opacity: 0.08;">
                            @switch($key)
                                @case('total')
                                    <x-fas-users style="width: 80px; height: 80px;" />
                                    @break
                                @case('conversion')
                                    <x-fas-chart-line style="width: 80px; height: 80px;" />
                                    @break
                                @case('pipeline')
                                    <x-fas-clock style="width: 80px; height: 80px;" />
                                    @break
                                @case('rejection')
                                    <x-fas-user-times style="width: 80px; height: 80px;" />
                                    @break
                                @default
                                    <x-fas-chart-pie style="width: 80px; height: 80px;" />
                            @endswitch
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row g-3 fade-in delay-1 pb-4">
            <!-- Demographics -->
            <div class="col-xl-3">
                <div class="premium-card h-100 p-0 overflow-hidden"
                    style="background: linear-gradient(135deg, rgba(67, 97, 238, 0.05), rgba(67, 97, 238, 0.02)); border: 1px solid rgba(67, 97, 238, 0.1);">
                    <div class="p-4 border-0">
                        <h5 class="mb-0 fw-bold"><x-fas-venus-mars class="me-2 text-primary" /> Gender Diversity</h5>
                    </div>
                    <div class="px-4 pb-4">
                        <div style="height: 200px;">
                            <canvas id="genderChart"></canvas>
                        </div>
                        <div class="mt-4">
                            @foreach($genderStats as $g)
                                <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded-3"
                                    style="background: rgba(255,255,255,0.4);">
                                    <span class="small fw-bold text-muted">{{ $g->gender }}</span>
                                    <span class="badge bg-white text-primary rounded-pill shadow-sm">{{ $g->count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Status -->
            <div class="col-xl-3">
                <div class="premium-card h-100 p-0 overflow-hidden"
                    style="background: linear-gradient(135deg, rgba(255, 209, 102, 0.05), rgba(255, 209, 102, 0.02)); border: 1px solid rgba(255, 209, 102, 0.1);">
                    <div class="p-4 border-0">
                        <h5 class="mb-0 fw-bold"><x-fas-money-bill-wave class="me-2 text-warning" /> Payment Status
                        </h5>
                    </div>
                    <div class="px-4 pb-4">
                        <div style="height: 200px;">
                            <canvas id="paymentChart"></canvas>
                        </div>
                        <div class="mt-4">
                            @foreach($paymentStats as $p)
                                <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded-3"
                                    style="background: rgba(255,255,255,0.4);">
                                    <span class="small fw-bold text-muted">{{ ucfirst($p->payment_status ?? 'unpaid') }}</span>
                                    <span
                                        class="badge bg-{{ ($p->payment_status == 'paid') ? 'success' : (($p->payment_status == 'partial') ? 'warning' : (($p->payment_status == 'fee') ? 'primary' : 'danger')) }} rounded-pill">{{ $p->count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application Sources -->
            <div class="col-xl-3">
                <div class="premium-card h-100 p-0 overflow-hidden"
                    style="background: linear-gradient(135deg, rgba(6, 214, 160, 0.05), rgba(6, 214, 160, 0.02)); border: 1px solid rgba(6, 214, 160, 0.1);">
                    <div class="p-4 border-0">
                        <h5 class="mb-0 fw-bold"><x-fab-wpforms class="me-2 text-success" /> Sources</h5>
                    </div>
                    <div class="px-4 pb-4">
                        <div style="height: 200px;">
                            <canvas id="formSourceChart"></canvas>
                        </div>
                        <div class="mt-4">
                            @foreach($formTypeStats as $f)
                                <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded-3"
                                    style="background: rgba(255,255,255,0.4);">
                                    <span class="small fw-bold text-muted">
                                        {{ $f->form_type == 'form1' ? 'Primary' : ($f->form_type == 'form2' ? 'Secondary' : 'HSC') }}
                                    </span>
                                    <span class="badge bg-white text-success rounded-pill shadow-sm">{{ $f->count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stream Distribution -->
            <div class="col-xl-3">
                <div class="premium-card h-100 p-0 overflow-hidden"
                    style="background: linear-gradient(135deg, rgba(17, 138, 178, 0.05), rgba(11, 138, 178, 0.02)); border: 1px solid rgba(17, 138, 178, 0.1);">
                    <div class="p-4 border-0">
                        <h5 class="mb-0 fw-bold"><x-fas-atom class="me-2 text-info" /> Streams</h5>
                    </div>
                    <div class="px-4 pb-4">
                        @if($streamStats->count() > 0)
                            <div style="height: 200px;">
                                <canvas id="streamChart"></canvas>
                            </div>
                            <div class="mt-4">
                                @foreach($streamStats as $s)
                                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded-3"
                                        style="background: rgba(255,255,255,0.4);">
                                        <span class="small fw-bold text-muted text-truncate"
                                            style="max-width: 100px;">{{ $s->stream }}</span>
                                        <span class="badge bg-white text-info rounded-pill shadow-sm">{{ $s->count }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <x-fas-chart-pie class="text-muted opacity-25 mb-3" style="width: 48px; height: 48px;" />
                                <p class="text-muted small">No stream data</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 fade-in delay-2 pb-4">
            <!-- Forms Type Gender Distribution -->
            <div class="col-xl-6">
                <div class="premium-card h-100 p-0 overflow-hidden"
                    style="background: linear-gradient(135deg, rgba(6, 214, 160, 0.05), rgba(6, 214, 160, 0.02)); border: 1px solid rgba(6, 214, 160, 0.15);">
                    <div class="p-4 border-0 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-bold"><x-fas-venus-mars class="me-2 text-success" /> Forms Type Gender Distribution</h5>
                            <small class="text-muted">Male & Female intake comparison across form levels</small>
                        </div>
                    </div>
                    <div class="px-4 pb-4">
                        <div style="height: 280px;">
                            <canvas id="formTypeGenderChart"></canvas>
                        </div>
                        <div class="mt-4">
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless align-middle mb-0">
                                    <thead>
                                        <tr class="text-muted small border-bottom">
                                            <th>Form Type</th>
                                            <th class="text-center"><x-fas-mars class="text-primary me-1" /> Male</th>
                                            <th class="text-center"><x-fas-venus class="text-danger me-1" /> Female</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($formTypeGenderData as $fg)
                                            <tr>
                                                <td class="fw-semibold small py-2">{{ $fg['label'] }}</td>
                                                <td class="text-center small py-2">
                                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2">
                                                        {{ $fg['male'] }} ({{ $fg['total'] > 0 ? round(($fg['male'] / $fg['total']) * 100, 1) : 0 }}%)
                                                    </span>
                                                </td>
                                                <td class="text-center small py-2">
                                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2">
                                                        {{ $fg['female'] }} ({{ $fg['total'] > 0 ? round(($fg['female'] / $fg['total']) * 100, 1) : 0 }}%)
                                                    </span>
                                                </td>
                                                <td class="text-end fw-bold small py-2">{{ $fg['total'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Class-Wise Gender Distribution -->
            <div class="col-xl-6">
                <div class="premium-card h-100 p-0 overflow-hidden"
                    style="background: linear-gradient(135deg, rgba(67, 97, 238, 0.05), rgba(67, 97, 238, 0.02)); border: 1px solid rgba(67, 97, 238, 0.15);">
                    <div class="p-4 border-0 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-bold"><x-fas-graduation-cap class="me-2 text-primary" /> Class-Wise Gender Distribution</h5>
                            <small class="text-muted">Student intake and gender breakdown by class</small>
                        </div>
                    </div>
                    <div class="px-4 pb-4">
                        <div style="height: 280px;">
                            <canvas id="classGenderChart"></canvas>
                        </div>
                        <div class="mt-4" style="max-height: 180px; overflow-y: auto;">
                            <table class="table table-sm table-borderless align-middle mb-0">
                                <thead>
                                    <tr class="text-muted small border-bottom">
                                        <th>Class</th>
                                        <th class="text-center"><x-fas-mars class="text-primary me-1" /> Male</th>
                                        <th class="text-center"><x-fas-venus class="text-danger me-1" /> Female</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($classGenderData as $cg)
                                        <tr>
                                            <td class="fw-semibold small py-2">{{ $cg['class_name'] }}</td>
                                            <td class="text-center small py-2">
                                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2">
                                                    {{ $cg['male'] }}
                                                </span>
                                            </td>
                                            <td class="text-center small py-2">
                                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2">
                                                    {{ $cg['female'] }}
                                                </span>
                                            </td>
                                            <td class="text-end fw-bold small py-2">{{ $cg['total'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted small py-3">No class data available</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 fade-in delay-2 pb-4">
            <!-- Religion Diversity -->
            <div class="col-xl-6">
                <div class="premium-card h-100 p-0 overflow-hidden"
                    style="background: linear-gradient(135deg, rgba(63, 55, 201, 0.05), rgba(63, 55, 201, 0.02)); border: 1px solid rgba(63, 55, 201, 0.1);">
                    <div class="p-4 border-0">
                        <h5 class="mb-0 fw-bold"><x-fas-pray class="me-2 text-indigo" /> Religious Distribution</h5>
                    </div>
                    <div class="px-4 pb-4">
                        <div style="height: 350px;">
                            <canvas id="religionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trend Analysis -->
            <div class="col-xl-6">
                <div class="premium-card h-100 p-0 overflow-hidden"
                    style="background: linear-gradient(135deg, rgba(114, 9, 183, 0.05), rgba(114, 9, 183, 0.02)); border: 1px solid rgba(114, 9, 183, 0.1);">
                    <div class="p-4 border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><x-fas-chart-area class="me-2 text-purple" /> Registration Trends</h5>
                        <select
                            class="form-select form-select-sm w-auto rounded-3 bg-white bg-opacity-50 border-0 shadow-sm"
                            id="trendRange">
                            <option value="daily">Last 30 Days</option>
                            <option value="weekly">Last 12 Weeks</option>
                            <option value="monthly" selected>Last 12 Months</option>
                        </select>
                    </div>
                    <div class="px-4 pb-4">
                        <div style="height: 350px;">
                            <canvas id="trendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Shared colors
                const colors = {
                    primary: '#4361ee',
                    success: '#06d6a0',
                    warning: '#ffd166',
                    danger: '#ef476f',
                    info: '#118ab2',
                    purple: '#7209b7',
                    indigo: '#3f37c9'
                };

                // Gender Chart
                new Chart(document.getElementById('genderChart'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($genderStats->pluck('gender')),
                        datasets: [{
                            data: @json($genderStats->pluck('count')),
                            backgroundColor: [colors.primary, colors.danger, colors.warning, colors.success],
                            borderWidth: 0,
                            hoverOffset: 15
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                        },
                        cutout: '70%'
                    }
                });

                // Payment Chart
                new Chart(document.getElementById('paymentChart'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($paymentStats->pluck('payment_status')).map(s => s ? s.toUpperCase() : 'UNPAID'),
                        datasets: [{
                            data: @json($paymentStats->pluck('count')),
                            backgroundColor: @json($paymentStats->map(fn($p) => ($p->payment_status == 'paid' ? '#06d6a0' : ($p->payment_status == 'partial' ? '#ffd166' : ($p->payment_status == 'fee' ? '#4361ee' : '#ef476f'))))),
                            borderWidth: 0,
                            hoverOffset: 15
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                        },
                        cutout: '70%'
                    }
                });

                // Form Source Chart
                new Chart(document.getElementById('formSourceChart'), {
                    type: 'pie',
                    data: {
                        labels: @json($formTypeStats->map(fn($f) => $f->form_type == 'form1' ? 'Primary' : ($f->form_type == 'form2' ? 'Secondary' : 'HSC'))),
                        datasets: [{
                            data: @json($formTypeStats->pluck('count')),
                            backgroundColor: [colors.success, colors.info, colors.purple],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                        }
                    }
                });

                // Stream Chart (Form 3 only)
                @if($streamStats->count() > 0)
                    new Chart(document.getElementById('streamChart'), {
                        type: 'polarArea',
                        data: {
                            labels: @json($streamStats->pluck('stream')),
                            datasets: [{
                                data: @json($streamStats->pluck('count')),
                                backgroundColor: ['rgba(67, 97, 238, 0.7)', 'rgba(6, 214, 160, 0.7)', 'rgba(255, 209, 102, 0.7)'],
                                borderWidth: 1,
                                borderColor: '#fff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'bottom' }
                            }
                        }
                    });
                @endif

                // Forms Type Gender Chart
                new Chart(document.getElementById('formTypeGenderChart'), {
                    type: 'bar',
                    data: {
                        labels: @json(array_values(array_map(fn($f) => $f['label'], $formTypeGenderData))),
                        datasets: [
                            {
                                label: 'Male',
                                data: @json(array_values(array_map(fn($f) => $f['male'], $formTypeGenderData))),
                                backgroundColor: colors.primary,
                                borderRadius: 6
                            },
                            {
                                label: 'Female',
                                data: @json(array_values(array_map(fn($f) => $f['female'], $formTypeGenderData))),
                                backgroundColor: colors.danger,
                                borderRadius: 6
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                            x: { grid: { display: false } }
                        },
                        plugins: {
                            legend: { position: 'top', labels: { usePointStyle: true, padding: 15 } }
                        }
                    }
                });

                // Class Gender Chart
                new Chart(document.getElementById('classGenderChart'), {
                    type: 'bar',
                    data: {
                        labels: @json(array_column($classGenderData, 'class_name')),
                        datasets: [
                            {
                                label: 'Male',
                                data: @json(array_column($classGenderData, 'male')),
                                backgroundColor: colors.primary,
                                borderRadius: 6
                            },
                            {
                                label: 'Female',
                                data: @json(array_column($classGenderData, 'female')),
                                backgroundColor: colors.danger,
                                borderRadius: 6
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: { stacked: true, grid: { display: false } },
                            y: { stacked: true, beginAtZero: true, grid: { borderDash: [5, 5] } }
                        },
                        plugins: {
                            legend: { position: 'top', labels: { usePointStyle: true, padding: 15 } }
                        }
                    }
                });

                // Religion Chart
                new Chart(document.getElementById('religionChart'), {
                    type: 'bar',
                    data: {
                        labels: @json($religionStats->pluck('religion')),
                        datasets: [{
                            label: 'Students',
                            data: @json($religionStats->pluck('count')),
                            backgroundColor: colors.indigo,
                            borderRadius: 10,
                            barThickness: 30
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                            x: { grid: { display: false } }
                        },
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });

                // Trend Chart with dynamic updates
                let trendChart;
                function updateTrend(range = 'monthly') {
                    fetch(`{{ route('admin.statistics.api') }}?range=${range}`)
                        .then(r => r.json())
                        .then(res => {
                            const ctx = document.getElementById('trendChart');
                            const config = {
                                type: 'line',
                                data: {
                                    labels: res.data.map(d => d.period),
                                    datasets: [{
                                        label: 'Registrations',
                                        data: res.data.map(d => d.count),
                                        borderColor: colors.purple,
                                        backgroundColor: 'rgba(114, 9, 183, 0.1)',
                                        borderWidth: 4,
                                        fill: true,
                                        tension: 0.4,
                                        pointBackgroundColor: '#fff',
                                        pointBorderColor: colors.purple,
                                        pointRadius: 5,
                                        pointHoverRadius: 8
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { display: false },
                                        tooltip: { padding: 15, borderRadius: 10 }
                                    },
                                    scales: {
                                        y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                                        x: { grid: { display: false } }
                                    }
                                }
                            };

                            if (trendChart) trendChart.destroy();
                            trendChart = new Chart(ctx, config);
                        });
                }

                document.getElementById('trendRange').addEventListener('change', (e) => updateTrend(e.target.value));
                updateTrend(); // Initial load
            });
        </script>
    @endpush

    <style>
        .delay-1 {
            animation-delay: 0.1s;
        }

        .delay-2 {
            animation-delay: 0.2s;
        }

        .fw-500 {
            font-weight: 500;
        }

        .text-dark-50 {
            color: rgba(0, 0, 0, 0.5);
        }

        .bg-indigo {
            background-color: #3f37c9;
        }
    </style>
@endsection