@extends('layouts.default')

@section('title', 'My Item Reports')

@section('page-css')
  <link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/LostFoundReport/item_myReport.css') }}">
@endsection

@section('content')
<div class="layout">
  @include('layouts.partials.sidebar')

  <div class="main-content">
    <div class="page-header">
      <h1>My Item Reports</h1>
    </div>

    <!-- Success Message -->
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
          <div class="stat-label">Total Reports</div>
          <div class="stat-value">{{ \App\Models\ItemReport::where('userID', Auth::id())->count() }}</div>
        </div>
      </div>
      <div class="stat-card pending">
        <div class="stat-icon"></div>
        <div class="stat-info">
          <div class="stat-label">Pending</div>
          <div class="stat-value">{{ \App\Models\ItemReport::where('userID', Auth::id())->where('reportStatus', 'Pending')->count() }}</div>
        </div>
      </div>
      <div class="stat-card published">
        <div class="stat-icon"></div>
        <div class="stat-info">
          <div class="stat-label">Published</div>
          <div class="stat-value">{{ \App\Models\ItemReport::where('userID', Auth::id())->where('reportStatus', 'Published')->count() }}</div>
        </div>
      </div>
      <div class="stat-card rejected">
        <div class="stat-icon"></div>
        <div class="stat-info">
          <div class="stat-label">Rejected</div>
          <div class="stat-value">{{ \App\Models\ItemReport::where('userID', Auth::id())->where('reportStatus', 'Rejected')->count() }}</div>
        </div>
      </div>
      <div class="stat-card completed">
        <div class="stat-icon"></div>
        <div class="stat-info">
          <div class="stat-label">Completed</div>
          <div class="stat-value">{{ \App\Models\ItemReport::where('userID', Auth::id())->where('reportStatus', 'Completed')->count() }}</div>
        </div>
      </div>
    </div>

    <!-- FILTERS CARD -->
    <div class="filters-card">
      <form action="{{ route('item_report.my_report') }}" method="GET" class="filters-form">
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

        <div class="filter-group">
          <label for="type">Filter by Type</label>
          <select name="type" id="type" class="filter-select">
            <option value="">All Types</option>
            <option value="lost" {{ request('type') == 'lost' ? 'selected' : '' }}>
              Lost
            </option>
            <option value="found" {{ request('type') == 'found' ? 'selected' : '' }}>
              Found
            </option>
          </select>
        </div>

        <button type="submit" class="btn-filter">Apply Filters</button>
        <a href="{{ route('item_report.my_report') }}" class="btn-reset">Reset</a>
      </form>
    </div>
    <!-- Report Table -->
    @if($userReports->count() > 0)
      <!-- <h5>_</h5> -->
      <div class="table-container">
        <table class="report-table">
          <thead>
            <tr>
                <th>Report Type</th>
                <th>Item Name</th>
                <th>Category</th>
                <th>Description</th>
                <th>Location</th>
                <th>Date</th>
                <th>Image</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($userReports as $report)
              <tr>
                <td data-label="Report Type">{{ $report->reportType }}</td>
                <td data-label="Item Name">{{ $report->itemName }}</td>
                <td data-label="Category">{{ $report->category?->categoryName ?? 'N/A' }}</td>
                <td data-label="Description">{{ $report->itemDescription }}</td>
                <td data-label="Location">{{ $report->location?->locationName ?? 'N/A' }}</td>
                <td data-label="Date">{{ \Carbon\Carbon::parse($report->reportDate)->format('d/m/Y') }}</td>
                <td data-label="Image">
                    @if ($report->itemImg)
                      <img src="{{ asset('storage/' . $report->itemImg) }}" alt="{{ $report->itemName }}">
                    @else
                      <div class="na-text">N/A</div>
                    @endif
                </td>
                <td data-label="Status">
                    <span class="status {{ strtolower($report->reportStatus ?? 'pending') }}">
                        {{ ucfirst($report->reportStatus ?? 'Pending') }}
                    </span>
                </td>
            
                <td data-label="Action">
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
                                View Form
                            </a>
                            
                            {{-- Download Form --}}
                            <a href="{{ route('handover.form.download_uploaded', $completedHandover->requestID) }}" 
                               class="btn download-form">
                                Download
                            </a>
                        @else
                            <span class="btn disabled">No Form</span>
                        @endif

                    @elseif(strtolower($report->reportStatus) === 'rejected')
                        {{-- View Rejection Reason --}}
                        <button type="button" class="btn reason" 
                            data-reason="{{ $report->rejectionNote ?? 'No reason provided' }}">
                            Reason
                        </button>

                        {{-- Delete --}}
                        <form action="{{ route('item_report.destroy', $report->reportID) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn delete">Delete</button>
                        </form>
                    @else
                        {{-- Normal Edit/Delete for other statuses (Pending/Published) --}}
                        <a href="{{ route('item_report.edit', $report->reportID) }}" class="btn edit">Edit</a>
                        <form action="{{ route('item_report.destroy', $report->reportID) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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
      <div class="empty-state">
        <div class="empty-icon"></div>
        <h3>No Reports Found</h3>
        <p>
          @if(request('status') || request('type'))
            No reports match your current filters
          @else
            You don't have any reports yet
          @endif
        </p>
      </div>
    @endif
  </div>
</div>
@endsection

@section('page-js')
  <script src="{{ asset('js/sidebar.js') }}"></script>

  <!-- show reason -->
  <script>
    document.querySelectorAll('.btn.reason').forEach(btn => {
      btn.addEventListener('click', () => {
        alert(btn.dataset.reason);
          });
    });
  </script>
@endsection