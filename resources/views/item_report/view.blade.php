@extends('layouts.default')

@section('title', 'All Lost & Found Reports')

@section('page-css')
  <link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/utils/admin_sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/utils/modal.css') }}">
  <link rel="stylesheet" href="{{ asset('css/LostFoundReport/item_view.css') }}">
@endsection

@section('content')
<div class="layout">
  @if (Auth::user()->userRole === 'Admin')
    @include('layouts.partials.admin_sidebar')
  @else
    @include('layouts.partials.sidebar')
  @endif

  <div class="main-content">

    <h1>All Lost & Found Item Reports</h1>

    <!-- FILTER BAR -->
    <form method="GET" action="{{ route('item_report.view') }}" class="filter-bar">
      <input type="text" name="keyword" placeholder="Search by keyword..." value="{{ request('keyword') }}" />
      <select name="status">
        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
        @foreach ($statusEnum as $status)
          <option value="{{ strtolower($status) }}" {{ request('status') == strtolower($status) ? 'selected' : '' }}>
            {{ $status }}
          </option>
        @endforeach
      </select>
      <select name="category">
        <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>All</option>
        @foreach ($categories as $category)
          <option value="{{ $category->categoryID }}" {{ request('category') == $category->categoryID ? 'selected' : '' }}>
            {{ $category->categoryName }}
          </option>
        @endforeach
      </select>
      <button type="submit" class="btn">Apply Filter</button>
    </form>

    <!-- ITEM REPORT GRID -->
    <div class="item-grid">
      @foreach ($reports as $report)
        <div class="item-card">
          @if ($report->itemImg)
            <img src="{{ asset('storage/' . $report->itemImg) }}" alt="{{ $report->itemName }}">
          @else
            <div class="na-text">N/A</div>
          @endif
          
          <div class="item-info">
            <h3>{{ $report->itemName }}</h3>
            <p>
              <strong>Item Status:</strong> {{ $report->reportType }}&nbsp;&nbsp;
              <strong>Location:</strong> {{ $report->location?->locationName ?? 'N/A' }}
              <br><strong>Date:</strong> {{ \Carbon\Carbon::parse($report->reportDate)->format('d/m/Y') }}
            </p>

            <div class="btn-group">
              <!-- View More Details Button -->
              <a href="javascript:void(0)" class="btn view-details-btn"
                data-report-id="{{ $report->reportID }}"
                data-name="{{ $report->itemName }}"
                data-category="{{ $report->category?->categoryName ?? 'N/A' }}"
                data-status="{{ $report->reportType }}"
                data-location="{{ $report->location?->locationName ?? 'N/A' }}"
                data-date="{{ \Carbon\Carbon::parse($report->reportDate)->format('d M Y') }}"
                data-description="{{ $report->itemDescription }}"
                data-image="{{ $report->itemImg ? asset('storage/' . $report->itemImg) : 'N/A' }}">
                View More Details
              </a>

              <!-- View on Map Button -->
              <a href="{{ route('map.show', $report->reportID) }}" target="_blank" class="btn view-map-btn">
                View on Map
              </a>

              <!-- Handover Button (Only for non-admin users) -->
              <!-- @if (Auth::user()->userRole !== 'Admin')
                <button type="button" class="btn initiate-handover-btn"
                        data-report-id="{{ $report->reportID }}"
                        data-report-type="{{ $report->reportType }}">
                  @if ($report->reportType === 'Lost')
                    Found it!
                  @else
                    Claim it!  
                  @endif
                </button>
              @endif -->

              <!-- Delete Item Report (Admin Only) -->
              <!-- @if (Auth::user()->userRole === 'Admin')
                <button class="btn">DELETE!</button>
              @endif -->
            </div>
          </div>
        </div>
      @endforeach

      @if ($reports->isEmpty())
        <p class="empty-text">No reports found.</p>
      @endif
    </div>

    @include('item_report.partials.item_modal')
    @include('handover.partials.handover_modal')
  </div>
</div>
@endsection

@section('page-js')
  <script src="{{ asset('js/sidebar.js') }}"></script>
  <script src="{{ asset('js/admin_sidebar.js') }}"></script>
  <script src="{{ asset('js/item_modal.js') }}"></script>
  <script src="{{ asset('js/handover_modal.js') }}"></script>
@endsection