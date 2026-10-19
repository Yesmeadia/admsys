@extends('admin.layout')

@section('title', 'Applications')
@section('page-title', 'Manage Applications')
@section('page-subtitle', 'View and process admission requests')

@section('content')
    <div class="row g-3 mb-4">
        @php
            $statsStyle = [
                'total' => ['color' => 'primary', 'icon' => 'fa-file-alt', 'bg_icon' => 'fa-users'],
                'pending' => ['color' => 'warning', 'icon' => 'fa-clock', 'bg_icon' => 'fa-hourglass-start'],
                'accepted' => ['color' => 'success', 'icon' => 'fa-check', 'bg_icon' => 'fa-graduation-cap'],
                'paid' => ['color' => 'success', 'icon' => 'fa-check-double', 'bg_icon' => 'fa-money-bill-wave'],
                'unpaid' => ['color' => 'danger', 'icon' => 'fa-exclamation-circle', 'bg_icon' => 'fa-exclamation-triangle'],
                'partial' => ['color' => 'warning', 'icon' => 'fa-hand-holding-usd', 'bg_icon' => 'fa-hand-holding-heart'],
            ];

            $gradients = [
                'primary' => 'linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(67, 97, 238, 0.05))',
                'warning' => 'linear-gradient(135deg, rgba(255, 209, 102, 0.1), rgba(255, 209, 102, 0.05))',
                'success' => 'linear-gradient(135deg, rgba(6, 214, 160, 0.1), rgba(6, 214, 160, 0.05))',
                'danger' => 'linear-gradient(135deg, rgba(239, 71, 111, 0.1), rgba(239, 71, 111, 0.05))',
            ];
        @endphp

        @foreach($statsStyle as $key => $style)
            <div class="col-xl-2 col-md-4 col-sm-6">
                @php $colorType = $style['color']; @endphp
                <div class="premium-card h-100 p-3 position-relative overflow-hidden mb-0"
                    style="background: {{ $gradients[$colorType] }}; border: 1px solid rgba(var(--bs-{{ $colorType }}-rgb), 0.1);">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="rounded-circle bg-{{ $colorType }} text-white d-flex align-items-center justify-content-center shadow-sm"
                            style="width: 32px; height: 32px; z-index: 1;">
                            @switch($key)
                                @case('total')
                                    <x-fas-file-alt style="width: 12px; height: 12px;" />
                                    @break
                                @case('pending')
                                    <x-fas-clock style="width: 12px; height: 12px;" />
                                    @break
                                @case('accepted')
                                    <x-fas-check style="width: 12px; height: 12px;" />
                                    @break
                                @case('paid')
                                    <x-fas-check-double style="width: 12px; height: 12px;" />
                                    @break
                                @case('unpaid')
                                    <x-fas-exclamation-circle style="width: 12px; height: 12px;" />
                                    @break
                                @case('partial')
                                    <x-fas-hand-holding-usd style="width: 12px; height: 12px;" />
                                    @break
                                @default
                                    <x-fas-file-alt style="width: 12px; height: 12px;" />
                            @endswitch
                        </div>
                        <h5 class="mb-0 fw-bold text-dark" style="z-index: 1;">{{ $statusCounts[$key] ?? 0 }}</h5>
                    </div>
                    <p class="text-{{ $colorType }} fw-bold mb-0 text-uppercase"
                        style="font-size: 9px; letter-spacing: 0.5px; z-index: 1;">
                        {{ $key == 'accepted' ? 'admitted' : $key }}
                    </p>
                    <div class="position-absolute" style="bottom: -10px; right: -10px; opacity: 0.05;">
                        @switch($key)
                            @case('total')
                                <x-fas-users style="width: 40px; height: 40px;" />
                                @break
                            @case('pending')
                                <x-fas-hourglass-start style="width: 40px; height: 40px;" />
                                @break
                            @case('accepted')
                                <x-fas-graduation-cap style="width: 40px; height: 40px;" />
                                @break
                            @case('paid')
                                <x-fas-money-bill-wave style="width: 40px; height: 40px;" />
                                @break
                            @case('unpaid')
                                <x-fas-exclamation-triangle style="width: 40px; height: 40px;" />
                                @break
                            @case('partial')
                                <x-fas-hand-holding-heart style="width: 40px; height: 40px;" />
                                @break
                            @default
                                <x-fas-users style="width: 40px; height: 40px;" />
                        @endswitch
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Filters Card -->
    <div class="card mb-4 bg-white p-4 rounded-4 shadow-sm border" style="border-color: #f1f5f9 !important;">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.25rem; font-weight: 700; text-transform: none !important; letter-spacing: -0.2px;">Search & Filter</h5>
            <button class="btn btn-sm btn-light border bg-white rounded-pill px-3 py-1 shadow-sm fw-semibold text-secondary" type="button"
                data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="true" style="text-transform: none !important; font-size: 13px;">
                <x-fas-sliders-h class="me-2" /> Toggle Filters
            </button>
        </div>

        <div class="collapse show" id="filterCollapse">
            <form method="GET" action="{{ route('admin.applications') }}" id="filterForm">
                <!-- Row 1: Keyword, Form Type, Academic Class -->
                <div class="row g-3 mb-3">
                    <div class="col-lg-6 col-md-12">
                        <label class="form-label small fw-bold text-secondary mb-1" style="text-transform: none !important; font-size: 13px;">Keyword Search</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 ps-3 pe-2 text-primary rounded-start-3">
                                <x-fas-search style="color: #0284c7; width: 14px; height: 14px;" />
                            </span>
                            <input type="text" class="form-control bg-light border-0 py-2 ps-1 rounded-end-3 text-secondary" 
                                name="search" value="{{ request('search') }}" 
                                placeholder="SEARCH NAME, UID, EMAIL, MOBILE..." style="font-size: 12.5px;">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label small fw-bold text-secondary mb-1" style="text-transform: none !important; font-size: 13px;">Form Type</label>
                        <select class="form-select bg-light border-0 py-2 rounded-3 text-uppercase fw-semibold text-secondary" name="form_type" style="font-size: 12.5px;">
                            <option value="">ALL TYPES</option>
                            <option value="form1" {{ request('form_type') == 'form1' ? 'selected' : '' }}>Form 1 (Pre-Primary)</option>
                            <option value="form2" {{ request('form_type') == 'form2' ? 'selected' : '' }}>Form 2 (Class 2 to 10)</option>
                            <option value="form3" {{ request('form_type') == 'form3' ? 'selected' : '' }}>Form 3 (Higher Secondary)</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label small fw-bold text-secondary mb-1" style="text-transform: none !important; font-size: 13px;">Academic Class</label>
                        <select class="form-select bg-light border-0 py-2 rounded-3 text-uppercase fw-semibold text-secondary" name="class" style="font-size: 12.5px;">
                            <option value="">ALL CLASSES</option>
                            @if(isset($classList) && is_iterable($classList))
                                @foreach($classList as $class)
                                    <option value="{{ $class }}" {{ request('class') == $class ? 'selected' : '' }}>{{ strtoupper($class) }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <!-- Row 2: Status & Filters, Payment Date Range, Application Date Range -->
                <div class="row g-3 mb-4 align-items-end">
                    <div class="col-xl-4 col-lg-12">
                        <label class="form-label small fw-bold text-secondary mb-1" style="text-transform: none !important; font-size: 13px;">Status & Filters</label>
                        <div class="d-flex gap-2">
                            <select class="form-select bg-light border-0 py-2 rounded-3 text-uppercase fw-semibold text-secondary flex-grow-1" name="status" style="font-size: 11.5px; padding: 7px 24px 7px 10px; min-width: 0;">
                                <option value="">APP STAT</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            <select class="form-select bg-light border-0 py-2 rounded-3 text-uppercase fw-semibold text-secondary flex-grow-1" name="payment_status" style="font-size: 11.5px; padding: 7px 24px 7px 10px; min-width: 0;">
                                <option value="">FEE STAT</option>
                                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Partial</option>
                                <option value="fee" {{ request('payment_status') == 'fee' ? 'selected' : '' }}>Fee Quoted</option>
                            </select>
                            <select class="form-select bg-light border-0 py-2 rounded-3 text-uppercase fw-semibold text-secondary flex-grow-1" name="doc_status" style="font-size: 11.5px; padding: 7px 24px 7px 10px; min-width: 0;">
                                <option value="">DOC STA</option>
                                <option value="attached" {{ request('doc_status') == 'attached' ? 'selected' : '' }}>Attached</option>
                                <option value="missing" {{ request('doc_status') == 'missing' ? 'selected' : '' }}>Missing</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6">
                        <label class="form-label small fw-bold text-secondary mb-1" style="text-transform: none !important; font-size: 13px;">Payment Date Range</label>
                        <div class="d-flex gap-2 align-items-center">
                            <select class="form-select bg-light border-0 py-2 rounded-3 text-uppercase fw-semibold text-secondary" id="quickPaymentDate" style="width: 84px; font-size: 11px; padding: 7px 18px 7px 8px; flex-shrink: 0;">
                                <option value="">QUICK</option>
                                <option value="today">Today</option>
                                <option value="yesterday">Yesterday</option>
                                <option value="this_week">This Week</option>
                                <option value="this_month">This Month</option>
                                <option value="last_month">Last Month</option>
                            </select>
                            <input type="date" class="form-control bg-light border-0 py-2 px-2 rounded-3 text-secondary flex-grow-1" 
                                name="payment_date_from" id="payment_date_from" value="{{ request('payment_date_from') }}" 
                                placeholder="DD / MM / YYYY" style="font-size: 11.5px; min-width: 115px;">
                            <input type="date" class="form-control bg-light border-0 py-2 px-2 rounded-3 text-secondary flex-grow-1" 
                                name="payment_date_to" id="payment_date_to" value="{{ request('payment_date_to') }}" 
                                placeholder="DD / MM / YYYY" style="font-size: 11.5px; min-width: 115px;">
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6">
                        <label class="form-label small fw-bold text-secondary mb-1" style="text-transform: none !important; font-size: 13px;">Application Date Range</label>
                        <div class="d-flex gap-2 align-items-center">
                            <select class="form-select bg-light border-0 py-2 rounded-3 text-uppercase fw-semibold text-secondary" id="quickAppDate" style="width: 84px; font-size: 11px; padding: 7px 18px 7px 8px; flex-shrink: 0;">
                                <option value="">QUICK</option>
                                <option value="today">Today</option>
                                <option value="yesterday">Yesterday</option>
                                <option value="this_week">This Week</option>
                                <option value="this_month">This Month</option>
                                <option value="last_month">Last Month</option>
                            </select>
                            <input type="date" class="form-control bg-light border-0 py-2 px-2 rounded-3 text-secondary flex-grow-1" 
                                name="date_from" id="date_from" value="{{ request('date_from') }}" 
                                placeholder="DD / MM / YYYY" style="font-size: 11.5px; min-width: 115px;">
                            <input type="date" class="form-control bg-light border-0 py-2 px-2 rounded-3 text-secondary flex-grow-1" 
                                name="date_to" id="date_to" value="{{ request('date_to') }}" 
                                placeholder="DD / MM / YYYY" style="font-size: 11.5px; min-width: 115px;">
                        </div>
                    </div>
                </div>

                <!-- Row 3: Action Buttons (Apply, Reset on left, Export CSV on right) -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-2">
                    <div class="d-flex align-items-center gap-2">
                        <button type="submit" class="btn text-white px-4 py-2 rounded-pill fw-bold shadow-sm d-inline-flex align-items-center border-0" 
                            style="background: linear-gradient(135deg, #38bdf8 0%, #2563eb 100%); font-size: 14px; text-transform: none !important;">
                            <x-fas-filter class="me-2" /> Apply Filters
                        </button>
                        <a href="{{ route('admin.applications') }}" 
                            class="btn btn-light bg-light text-secondary px-4 py-2 rounded-pill fw-bold shadow-sm d-inline-flex align-items-center border" 
                            style="font-size: 14px; border-color: #e2e8f0 !important; text-transform: none !important;">
                            <x-fas-sync-alt class="me-2" /> Reset
                        </a>
                    </div>
                    <div>
                        <button type="submit" name="export" value="true" 
                            class="btn text-white px-4 py-2 rounded-pill fw-bold shadow-sm d-inline-flex align-items-center border-0" 
                            style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); font-size: 14px; text-transform: none !important;">
                            <x-fas-file-excel class="me-2" /> Export CSV
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="premium-card p-0 overflow-hidden position-relative"
        style="background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(67, 97, 238, 0.05)); border: 1px solid rgba(67, 97, 238, 0.1);">
        <div class="table-responsive bg-transparent">
            <table class="table table-hover align-middle mb-0 bg-transparent"
                style="--bs-table-bg: transparent; --bs-table-hover-bg: rgba(67, 97, 238, 0.05);">
                <thead>
                    <tr>
                        <th class="ps-4 py-3 border-0"
                            style="font-size: 11px; font-weight: 700; text-transform: uppercase;">ID</th>
                        <th class="py-3 border-0"
                            style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b;">Student
                            Information</th>
                        <th class="py-3 border-0"
                            style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b;">Class &
                            Type</th>
                        <th class="py-3 border-0"
                            style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b;">Status
                        </th>
                        <th class="py-3 border-0 text-center"
                            style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b;">Fee Status
                        </th>
                        <th class="py-3 border-0"
                            style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b;">Date
                            Applied</th>
                        <th class="pe-4 py-3 border-0 text-end"
                            style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b;">Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $app)
                        <tr style="transition: all 0.2s ease;">
                            <td class="ps-4 fw-bold text-muted" style="font-size: 13px;">#{{ $app->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @php
                                        $photoPath = $app->passport_photo_path ?? $app->student_photo_path;
                                        $photoUrl = $photoPath ? Storage::url($photoPath) : null;
                                    @endphp
                                    <div class="position-relative">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm"
                                            style="width: 42px; height: 42px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
                                            @if($photoUrl)
                                                <img src="{{ $photoUrl }}" class="w-100 h-100 rounded-circle object-fit-cover">
                                            @else
                                                {{ strtoupper(substr($app->first_name ?? 'A', 0, 1)) }}
                                            @endif
                                        </div>
                                        <span
                                            class="position-absolute bottom-0 end-0 p-1 bg-{{ $app->status == 'accepted' ? 'success' : ($app->status == 'rejected' ? 'danger' : 'warning') }} rounded-circle border border-2 border-white"
                                            style="width: 12px; height: 12px;"></span>
                                    </div>
                                    <div>
                                        <div class="fw-bold mb-0" style="font-size: 14px; color: #1e293b;">
                                            {{ $app->first_name }} {{ $app->surname }}
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <small class="text-muted" style="font-size: 11px;"><x-fas-envelope class="me-1" />{{ Str::limit($app->email, 20) }}</small>
                                            <small class="text-muted" style="font-size: 11px;"><x-fas-phone class="me-1" />{{ $app->mobile_1 ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="badge bg-light text-dark fw-bold border mb-1 d-block w-fit"
                                    style="font-size: 11px;">
                                    {{ $app->admission_sought_for_class ?? $app->admission_class ?? 'N/A' }}
                                </div>
                                <small class="text-muted text-uppercase fw-bold"
                                    style="font-size: 10px;">{{ $app->form_type }}</small>
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
                            <td class="text-center">
                                <div class="dropdown">
                                    @if($app->payment_status == 'paid')
                                        <button class="badge bg-success border-0 rounded-pill px-3 py-2 dropdown-toggle w-100"
                                            data-bs-toggle="dropdown" style="font-size: 10px; cursor: pointer;">
                                            <x-fas-money-bill-wave class="me-1" />PAID
                                        </button>
                                    @elseif($app->payment_status == 'partial')
                                        <button class="badge bg-warning border-0 rounded-pill px-3 py-2 dropdown-toggle w-100"
                                            data-bs-toggle="dropdown" style="font-size: 10px; cursor: pointer;">
                                            <x-fas-hand-holding-usd class="me-1" />PARTIAL
                                            ({{ number_format($app->paid_amount) }})
                                        </button>
                                    @elseif($app->payment_status == 'fee')
                                        <button class="badge bg-primary border-0 rounded-pill px-3 py-2 dropdown-toggle w-100"
                                            data-bs-toggle="dropdown" style="font-size: 10px; cursor: pointer;">
                                            <x-fas-file-invoice-dollar class="me-1" />FEE: {{ number_format($app->total_fee) }}
                                        </button>
                                    @else
                                        <button class="badge bg-danger border-0 rounded-pill px-3 py-2 dropdown-toggle w-100"
                                            data-bs-toggle="dropdown" style="font-size: 10px; cursor: pointer;">
                                            <x-fas-times-circle class="me-1" />UNPAID
                                        </button>
                                    @endif
                                    <ul class="dropdown-menu shadow-sm border-0">
                                        <li><button class="dropdown-item py-2"
                                                onclick="updatePaymentStatus({{ $app->id }}, 'paid')">
                                                <x-fas-check class="text-success me-2" /> Mark as Paid</button></li>
                                        <li><button class="dropdown-item py-2"
                                                onclick="showPartialPaymentModal({{ $app->id }}, {{ $app->total_fee ?? 0 }}, {{ $app->paid_amount ?? 0 }})">
                                                <x-fas-hand-holding-usd class="text-warning me-2" /> Partial
                                                Payment</button></li>
                                        <li><button class="dropdown-item py-2"
                                                onclick="showFeeModal({{ $app->id }}, {{ $app->total_fee ?? 0 }})">
                                                <x-fas-file-invoice-dollar class="text-primary me-2" /> Set Fee
                                                Amount</button></li>
                                        <li><button class="dropdown-item py-2"
                                                onclick="updatePaymentStatus({{ $app->id }}, 'unpaid')">
                                                <x-fas-times class="text-danger me-2" /> Mark as Unpaid</button></li>
                                    </ul>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-muted" style="font-size: 13px;">
                                    {{ $app->created_at->format('M d, Y') }}
                                </div>
                                <small class="text-muted"
                                    style="font-size: 11px;">{{ $app->created_at->diffForHumans() }}</small>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('admin.application.view', $app->id) }}"
                                        class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px;" title="View Details">
                                        <x-fas-eye class="text-primary" style="width: 12px; height: 12px;" />
                                    </a>
                                    <a href="{{ route('admin.application.download', $app->id) }}"
                                        class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px;" title="Download PDF">
                                        <x-fas-download class="text-success" style="width: 12px; height: 12px;" />
                                    </a>

                                    <div class="dropdown">
                                        <button
                                            class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px;" data-bs-toggle="dropdown">
                                            <x-fas-ellipsis-v class="text-muted" style="width: 12px; height: 12px;" />
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 p-2">
                                            @if($app->status == 'pending')
                                                <li><button class="dropdown-item rounded-2 py-2"
                                                        onclick="updateStatus({{ $app->id }}, 'accepted')"><x-fas-check class="me-2 text-success" /> Mark as Admitted</button>
                                                </li>
                                                <li><button class="dropdown-item rounded-2 py-2 text-danger"
                                                        onclick="updateStatus({{ $app->id }}, 'rejected')"><x-fas-times class="me-2 text-danger" /> Mark as Rejected</button></li>
                                            @endif
                                            <li><a class="dropdown-item rounded-2 py-2"
                                                    href="{{ route('admin.application.view', $app->id) }}"><x-fas-edit class="me-2 text-primary" /> Edit Application</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            @if(auth()->user()->isAdmin())
                                                <li><button class="dropdown-item rounded-2 py-2 text-danger"
                                                        onclick="deleteApplication({{ $app->id }})"><x-fas-trash-alt class="me-2 text-danger" /> Delete Record</button></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 24 24' fill='none' stroke='%23cbd5e1' stroke-width='1' stroke-linecap='round' stroke-linejoin='round'><circle cx='12' cy='12' r='10'></circle><line x1='12' y1='8' x2='12' y2='12'></line><line x1='12' y1='16' x2='12.01' y2='16'></line></svg>"
                                    class="mb-3 opacity-50" width="80">
                                <h6 class="text-muted fw-bold">No applications matching your criteria</h6>
                                <p class="text-muted small">Try adjusting your filters or search keywords</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="position-absolute" style="bottom: -15px; right: -15px; opacity: 0.03; pointer-events: none;">
            <x-fas-file-invoice style="width: 150px; height: 150px;" />
        </div>
    </div>

    <!-- Partial Payment Modal -->
    <div class="modal fade" id="partialPaymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Update Partial Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="partialPaymentForm">
                        <input type="hidden" id="partial_app_id">
                        <input type="hidden" id="partial_total_fee">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Paid Amount</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">₹</span>
                                <input type="number" class="form-control bg-light border-0" id="partial_paid_amount"
                                    placeholder="0.00">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary rounded-pill px-4"
                        onclick="submitPartialPayment()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Set Fee Modal -->
    <div class="modal fade" id="feeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Set Fee Amount</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="feeForm">
                        <input type="hidden" id="fee_app_id">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Total Fee Amount</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">₹</span>
                                <input type="number" class="form-control bg-light border-0" id="fee_total_amount"
                                    placeholder="0.00">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary rounded-pill px-4" onclick="submitFee()">Save</button>
                </div>
            </div>
        </div>
    </div>

    @if($applications->hasPages())
        <div class="px-4 py-3 bg-transparent d-flex justify-content-between align-items-center">
            <small class="text-muted">Showing <b>{{ $applications->firstItem() }}</b> to
                <b>{{ $applications->lastItem() }}</b> of <b>{{ $applications->total() }}</b> results</small>
            {{ $applications->links('pagination::bootstrap-5') }}
        </div>
    @endif

    <!-- Status Update Form (Hidden) -->
    <form id="status-form" method="POST" style="display: none;">
        @csrf
        @method('POST')
        <input type="hidden" name="status" id="status-input">
    </form>

    @push('scripts')
        <style>
            .w-fit {
                width: fit-content;
            }

            .pagination {
                margin-bottom: 0;
            }

            .page-link {
                border-radius: 8px !important;
                border: 0 !important;
                margin: 0 3px;
                font-weight: 600;
                font-size: 13px;
                color: #64748b;
            }

            .page-item.active .page-link {
                background: var(--primary-color) !important;
                color: white !important;
                box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
            }
        </style>
        <script>
            function updateStatus(applicationId, status) {
                const statusLabel = status.charAt(0).toUpperCase() + status.slice(1);
                window.appConfirm(`Are you sure you want to mark this application as ${statusLabel}?`, {
                    title: 'Update Application Status',
                    confirmText: `Yes, Mark as ${statusLabel}`,
                    icon: status === 'accepted' ? 'success' : (status === 'rejected' ? 'error' : 'question')
                }).then(result => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('status-form');
                        form.action = `/admin/application/${applicationId}/status`;
                        document.getElementById('status-input').value = status;
                        form.submit();
                    }
                });
            }

            function updatePaymentStatus(id, paymentStatus, totalFee = null, paidAmount = null) {
                const statusLabel = paymentStatus.toUpperCase();
                window.appConfirm(`Are you sure you want to mark this application as ${statusLabel}?`, {
                    title: 'Update Fee Status',
                    confirmText: `Yes, Set ${statusLabel}`,
                    icon: paymentStatus === 'paid' ? 'success' : 'question'
                }).then(result => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/application/${id}/update`,
                            method: 'POST',
                            data: {
                                payment_status: paymentStatus,
                                total_fee: totalFee,
                                paid_amount: paidAmount,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (res) {
                                location.reload();
                            },
                            error: function (xhr) {
                                window.appAlert('Something went wrong. Please try again.', { icon: 'error' });
                            }
                        });
                    }
                });
            }

            function showPartialPaymentModal(id, total, paid) {
                document.getElementById('partial_app_id').value = id;
                document.getElementById('partial_total_fee').value = total;
                document.getElementById('partial_paid_amount').value = paid;
                const modal = new bootstrap.Modal(document.getElementById('partialPaymentModal'));
                modal.show();
            }

            function showFeeModal(id, total) {
                document.getElementById('fee_app_id').value = id;
                document.getElementById('fee_total_amount').value = total;
                const modal = new bootstrap.Modal(document.getElementById('feeModal'));
                modal.show();
            }

            function submitPartialPayment() {
                const id = document.getElementById('partial_app_id').value;
                const total = document.getElementById('partial_total_fee').value;
                const paid = document.getElementById('partial_paid_amount').value;
                updatePaymentStatus(id, 'partial', total, paid);
            }

            function submitFee() {
                const id = document.getElementById('fee_app_id').value;
                const total = document.getElementById('fee_total_amount').value;
                updatePaymentStatus(id, 'fee', total);
            }

            function deleteApplication(id) {
                window.appConfirm('Are you absolutely sure you want to delete this application? This action cannot be undone.', {
                    title: 'Delete Application Record?',
                    confirmText: 'Yes, Delete Application',
                    icon: 'warning'
                }).then(result => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/application/${id}`,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (res) {
                                if (res.success) {
                                    location.reload();
                                } else {
                                    window.appAlert(res.message || 'Deletion failed', { icon: 'error' });
                                }
                            },
                            error: function (xhr) {
                                window.appAlert('Something went wrong. Please try again.', { icon: 'error' });
                            }
                        });
                    }
                });
            }

            // Quick Date Ranges handler
            function setupQuickDate(selectId, fromId, toId) {
                const select = document.getElementById(selectId);
                const fromInput = document.getElementById(fromId);
                const toInput = document.getElementById(toId);
                if (!select || !fromInput || !toInput) return;

                select.addEventListener('change', function () {
                    const val = this.value;
                    const now = new Date();
                    const formatDate = (d) => {
                        const year = d.getFullYear();
                        const month = String(d.getMonth() + 1).padStart(2, '0');
                        const day = String(d.getDate()).padStart(2, '0');
                        return `${year}-${month}-${day}`;
                    };

                    if (val === 'today') {
                        fromInput.value = formatDate(now);
                        toInput.value = formatDate(now);
                    } else if (val === 'yesterday') {
                        const yest = new Date(now);
                        yest.setDate(yest.getDate() - 1);
                        fromInput.value = formatDate(yest);
                        toInput.value = formatDate(yest);
                    } else if (val === 'this_week') {
                        const start = new Date(now);
                        const day = start.getDay() || 7;
                        start.setDate(start.getDate() - day + 1);
                        fromInput.value = formatDate(start);
                        toInput.value = formatDate(now);
                    } else if (val === 'this_month') {
                        const start = new Date(now.getFullYear(), now.getMonth(), 1);
                        fromInput.value = formatDate(start);
                        toInput.value = formatDate(now);
                    } else if (val === 'last_month') {
                        const start = new Date(now.getFullYear(), now.getMonth() - 1, 1);
                        const end = new Date(now.getFullYear(), now.getMonth(), 0);
                        fromInput.value = formatDate(start);
                        toInput.value = formatDate(end);
                    }
                });
            }
            setupQuickDate('quickPaymentDate', 'payment_date_from', 'payment_date_to');
            setupQuickDate('quickAppDate', 'date_from', 'date_to');
        </script>
    @endpush
@endsection