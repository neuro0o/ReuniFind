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
      <button class="tab-btn active">Pending</button>
      <button class="tab-btn">Approved</button>
      <button class="tab-btn">Claimed</button>
    </div>

    <!-- Success Message -->
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Report Table -->
    @if($userReports->count() > 0)
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
                <td data-label="Category">{{ $report->itemCategory }}</td>
                <td data-label="Description">{{ $report->itemDescription }}</td>
                <td data-label="Location">{{ $report->itemLocation }}</td>
                <td data-label="Date">{{ \Carbon\Carbon::parse($report->reportDate)->format('d/m/Y') }}</td>
                <td data-label="Image">
                    @if ($report->itemImg)
                      <img src="{{ asset('storage/' . $report->itemImg) }}" alt="{{ $report->itemName }}">
                    @else
                      <div class="na-text">N/A</div>
                    @endif
                </td>
                <td data-label="Status">
                    <span class="status {{ strtolower($report->status ?? 'pending') }}">
                        {{ ucfirst($report->status ?? 'Pending') }}
                    </span>
                </td>
            
                <td data-label="Action">
                  <div class="btn-group">
                    <a href="{{ route('item_report.edit', $report->reportID) }}" class="btn edit">Edit</a>
                    <form action="{{ route('item_report.destroy', $report->reportID) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn delete">Delete</button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <p class="empty-text">You have not submitted any reports yet.</p>
    @endif
  </div>
</div>
@endsection

@section('page-js')
  <script src="{{ asset('js/sidebar.js') }}"></script>
@endsection
