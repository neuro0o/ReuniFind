@extends('layouts.default')

@section('title', 'Manage Found Reports')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/admin_sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/LostFoundReport/item_myReport.css') }}">
@endsection

@section('content')
<div class="layout">
    @include('layouts.partials.admin_sidebar')

    <div class="main-content">
        <div class="page-header">
            <h1>Manage Found Reports</h1>
            <br>
        </div>

        @if(session('success'))
            <div class="status-info-card published">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon"></div>
                <div class="stat-info">
                    <div class="stat-label">Total Found Reports</div>
                    <div class="stat-value">{{ \App\Models\ItemReport::where('reportType', 'Found')->count() }}</div>
                </div>
            </div>
            <div class="stat-card pending">
                <div class="stat-icon"></div>
                <div class="stat-info">
                    <div class="stat-label">Pending</div>
                    <div class="stat-value">{{ \App\Models\ItemReport::where('reportType', 'Found')->where('reportStatus', 'Pending')->count() }}</div>
                </div>
            </div>
            <div class="stat-card published">
                <div class="stat-icon"></div>
                <div class="stat-info">
                    <div class="stat-label">Published</div>
                    <div class="stat-value">{{ \App\Models\ItemReport::where('reportType', 'Found')->where('reportStatus', 'Published')->count() }}</div>
                </div>
            </div>
            <div class="stat-card rejected">
                <div class="stat-icon"></div>
                <div class="stat-info">
                    <div class="stat-label">Rejected</div>
                    <div class="stat-value">{{ \App\Models\ItemReport::where('reportType', 'Found')->where('reportStatus', 'Rejected')->count() }}</div>
                </div>
            </div>
            <div class="stat-card completed">
                <div class="stat-icon"></div>
                <div class="stat-info">
                    <div class="stat-label">Completed</div>
                    <div class="stat-value">{{ \App\Models\ItemReport::where('reportType', 'Found')->where('reportStatus', 'Completed')->count() }}</div>
                </div>
            </div>
        </div>

        <!-- FILTERS CARD -->
        <div class="filters-card">
            <form action="{{ route('admin.manage_report_found') }}" method="GET" class="filters-form">
                <div class="filter-group">
                    <label for="status">Filter by Status</label>
                    <select name="status" id="status" class="filter-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>
                            Published
                        </option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                            Rejected
                        </option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                            Completed
                        </option>
                    </select>
                </div>

                <button type="submit" class="btn-filter">Apply Filter</button>
                <a href="{{ route('admin.manage_report_found') }}" class="btn-reset">Reset</a>
            </form>
        </div>

        @if($foundReports->count() > 0)
        <!-- <h5>_</h5> -->
        <div class="table-container">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Image</th>
                        <th>Category</th>
                        <th>Status</th>
                        @if(isset($status) && strtolower($status) === 'rejected')
                            <th>Reason for Rejection</th>
                        @endif
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($foundReports as $report)
                    <tr>
                        <td data-label="Item Name">{{ $report->itemName }}</td>
                        <td data-label="Image">
                            @if ($report->itemImg)
                            <img src="{{ asset('storage/' . $report->itemImg) }}" alt="{{ $report->itemName }}">
                            @else
                            <div class="na-text">N/A</div>
                            @endif
                        </td>
                        <td data-label="Category">{{ $report->category?->categoryName ?? 'N/A' }}</td>
                        <td data-label="Status">
                            <span class="status {{ strtolower($report->reportStatus ?? 'pending') }}">
                                {{ ucfirst($report->reportStatus ?? 'Pending') }}
                            </span>
                        </td>

                        @if(isset($status) && strtolower($status) === 'rejected')
                            <td data-label="Reason for Rejection">
                                {{ $report->rejectionNote ?? '-' }}
                            </td>
                        @endif

                        <td data-label="Actions">
                            <div class="btn-group">
                                @if(strtolower($report->reportStatus) === 'completed')
                                    {{-- View & Download Handover Form for Completed Reports --}}
                                    @php
                                        $completedHandover = \App\Models\HandoverRequest::where(function ($query) use ($report) {
                                            $query->where('reportID', $report->reportID)
                                                  ->orWhere('senderReportID', $report->reportID);
                                        })
                                        ->whereNotNull('handoverForm')
                                        ->latest()
                                        ->first();
                                    @endphp

                                    @if($completedHandover && $completedHandover->handoverForm)
                                        {{-- View Form (opens in new tab) --}}
                                        <a href="{{ route('handover.form.view', $completedHandover->requestID) }}" 
                                           class="btn view-form" 
                                           target="_blank">
                                            View
                                        </a>
                                        
                                        {{-- Download Form --}}
                                        <a href="{{ route('handover.form.download_uploaded', $completedHandover->requestID) }}" 
                                           class="btn download-form">
                                            Download
                                        </a>
                                    @else
                                        <span class="btn disabled">No Form</span>
                                    @endif

                                    {{-- Delete button for completed reports (deletes BOTH reports) --}}
                                    <form action="{{ route('admin.delete_completed_pair', $report->reportID) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Are you sure? This will delete BOTH reports in this handover pair!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn delete">Delete Pair</button>
                                    </form>
                                @else
                                    {{-- View button for all other statuses --}}
                                    <a href="{{ route('admin.report_detail', $report->reportID) }}" class="btn edit">View</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="empty-state">
                <div class="empty-icon"></div>
                <h3>No Found Reports Found</h3>
                <p>
                    @if(request('status'))
                        There are no {{ request('status') }} found reports at the moment
                    @else
                        There are no found reports at the moment
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
@endsection

<!-- PAGE SPECIFIC JS -->
@section('page-js')
    <script src="{{ asset('js/admin_sidebar.js') }}"></script>
@endsection