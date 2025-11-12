@extends('layouts.default')

@section('title', 'Report Details')

@section('page-css')
<link rel="stylesheet" href="{{ asset('css/utils/admin_sidebar.css') }}">
<link rel="stylesheet" href="{{ asset('css/LostFoundReport/item_myReport.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/admin_report_detail.css') }}">
@endsection

@section('content')
<div class="layout">
    @include('layouts.partials.admin_sidebar')

    <div class="main-content">
        <div class="report-card">
            <h2>{{ $report->itemName }}</h2>

            <div class="field"><strong>Category:</strong> {{ $report->category?->categoryName ?? 'N/A' }}</div>
            <div class="field"><strong>Description:</strong> {{ $report->itemDescription ?? '-' }}</div>
            <div class="field"><strong>Location:</strong> {{ $report->location?->locationName ?? 'N/A' }}</div>
            <div class="field"><strong>Date:</strong> {{ \Carbon\Carbon::parse($report->reportDate)->format('d/m/Y') }}</div>
            <div class="field"><strong>Status:</strong> 
                <span class="status {{ strtolower($report->reportStatus ?? 'pending') }}">
                    {{ ucfirst($report->reportStatus ?? 'Pending') }}
                </span>
            </div>
            <div class="field"><strong>Verification Note:</strong> {{ $report->verificationNote ?? '-' }}</div><br>

            <div class="images">
                <div>
                    <strong>Item Image:</strong>
                    @if($report->itemImg)
                        <img src="{{ asset('storage/' . $report->itemImg) }}" alt="Item Image">
                    @else
                        <div class="na-text">N/A</div>
                    @endif
                </div>

                <div>
                    <strong>Verification Image:</strong>
                    @if($report->verificationImg)
                        <img src="{{ asset('storage/' . $report->verificationImg) }}" alt="Verification Image">
                    @else
                        <div class="na-text">N/A</div>
                    @endif
                </div>
            </div>

            <!-- Button Group -->
            <div class="btn-group">
                @if(strtolower($report->reportStatus) === 'completed')
                    <!-- Only show Delete button -->
                    <form action="{{ route('admin.delete_report', $report->reportID) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this completed report?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn delete">Delete</button>
                    </form>
                @else
                    <!-- Publish Button -->
                    <form action="{{ route('admin.approve_report', $report->reportID) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn edit">Publish</button>
                    </form>

                    <!-- Reject Button -->
                    <button type="button" class="btn delete" id="showRejectBtn">Reject</button>

                    <!-- Delete button for non-completed can optionally be here if needed -->
                @endif
            </div>

            <!-- Rejection Input -->
            @if(strtolower($report->reportStatus) !== 'completed')
                <form id="rejectForm" action="{{ route('admin.reject_report', $report->reportID) }}" method="POST">
                    @csrf
                    <div id="rejectionContainer" style="display: none;">
                        <input 
                            type="text" 
                            name="rejectionNote" 
                            placeholder="Enter rejection reason" 
                            value="{{ old('rejectionNote') ?? $report->rejectionNote ?? '' }}" 
                            required
                        >
                        <button type="submit" class="btn delete">Submit Rejection</button>
                    </div>
                </form>
                @endif
        </div>
    </div>
</div>
@endsection

@section('page-js')
<script src="{{ asset('js/admin_sidebar.js') }}"></script>

<script>
    const showRejectBtn = document.getElementById('showRejectBtn');
    const rejectionContainer = document.getElementById('rejectionContainer');

    showRejectBtn.addEventListener('click', () => {
        rejectionContainer.style.display = 'block'; // show input below btn-group
        rejectionContainer.querySelector('input').focus();
        showRejectBtn.style.display = 'none'; // hide original reject button
    });
</script>
@endsection
