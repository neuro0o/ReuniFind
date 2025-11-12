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
    <h1>My Item Reports</h1>

    <!-- FILTERS -->
    <div class="filter-tabs">
      @php
        $statuses = ['Pending', 'Published', 'Rejected', 'Completed'];
      @endphp

      @foreach($statuses as $tabStatus)
        <a href="{{ route('item_report.my_report', ['status' => strtolower($tabStatus)]) }}" 
          class="tab-btn {{ isset($status) && strtolower($status) === strtolower($tabStatus) ? 'active' : (!isset($status) && strtolower($tabStatus) === 'pending' ? 'active' : '') }}">
          {{ $tabStatus }}
        </a>
      @endforeach
    </div>


    <!-- Success Message -->
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Report Table -->
    @if($userReports->count() > 0)
      <!-- @php
          $statusMessages = [
              'pending'   => 'List of reports waiting for Admin approval',
              'published' => 'List of reports approved by Admin and published',
              'rejected'  => 'List of reports rejected by Admin and not published',
              'completed' => 'List of reports that have been handed over'
          ];
      @endphp

      @if(isset($status) && isset($statusMessages[strtolower($status)]))
          <div class="status-info-card {{ strtolower($status) }}">
              <i class="fas fa-info-circle"></i>
              <span class="statusMessage">{{ $statusMessages[strtolower($status)] }}</span>
          </div>
      @endif -->
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
                    @if(strtolower($report->reportStatus) === 'rejected')
                        <!-- View Rejection Reason -->
                        <button type="button" class="btn reason" 
                            data-reason="{{ $report->rejectionNote ?? 'No reason provided' }}">
                            Reason
                        </button>

                        <!-- Delete -->
                        <form action="{{ route('item_report.destroy', $report->reportID) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn delete">Delete</button>
                        </form>
                    @else
                        <!-- Normal Edit/Delete for other statuses -->
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
      <p class="empty-text">No reports found.</p>
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
