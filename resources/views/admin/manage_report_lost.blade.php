@extends('layouts.default')

@section('title', 'Manage Lost Reports')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/admin_sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/LostFoundReport/item_myReport.css') }}">
@endsection

@section('content')
<div class="layout">
    @include('layouts.partials.admin_sidebar')

    <div class="main-content">
        <h1>Manage Lost Reports</h1>

        <!-- FILTER TABS -->
        <div class="filter-tabs">
            @php
                $statuses = ['Pending', 'Published', 'Rejected', 'Completed'];
            @endphp

            @foreach($statuses as $tabStatus)
                <a href="{{ route('admin.manage_report_lost', ['status' => strtolower($tabStatus)]) }}" 
                class="tab-btn {{ isset($status) && ucfirst($status) === $tabStatus ? 'active' : (!isset($status) && $tabStatus === 'Pending' ? 'active' : '') }}">
                {{ $tabStatus }}
                </a>
            @endforeach
        </div>

        @if($lostReports->count() > 0)
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
                    @foreach($lostReports as $report)
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
                                <a href="{{ route('admin.report_detail', $report->reportID) }}" class="btn edit">View</a>

                                @if(strtolower($report->reportStatus) === 'completed')
                                <form action="{{ route('admin.delete_report', $report->reportID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this completed report?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn delete">Delete</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <p class="empty-text">No lost reports found.</p>
        @endif
    </div>
</div>
@endsection

<!-- PAGE SPECIFIC JS -->
@section('page-js')
    <!-- FIXME: Fix Sidebar Collapse Behavior -->
    <script src="{{ asset('js/admin_sidebar.js') }}"></script>
@endsection
