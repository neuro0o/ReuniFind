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
      <div class="page-header">
        <h1>All Lost & Found Item Reports</h1>
      </div>

      <!-- FILTERS CARD -->
      <div class="filters-card">
        <form method="GET" action="{{ route('item_report.view') }}" class="filters-form">
          <div class="filter-group">
            <label for="keyword">Search Keyword</label>
            <input type="text" name="keyword" id="keyword" class="filter-input" 
                  placeholder="Search by name or description..." 
                  value="{{ request('keyword') }}" />
          </div>

          <div class="filter-group">
            <label for="status">Filter by Type</label>
            <select name="status" id="status" class="filter-select">
              <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Types</option>
              @foreach ($statusEnum as $status)
                <option value="{{ strtolower($status) }}" {{ request('status') == strtolower($status) ? 'selected' : '' }}>
                  {{ $status }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="filter-group">
            <label for="category">Filter by Category</label>
            <select name="category" id="category" class="filter-select">
              <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>All Categories</option>
              @foreach ($categories as $category)
                <option value="{{ $category->categoryID }}" {{ request('category') == $category->categoryID ? 'selected' : '' }}>
                  {{ $category->categoryName }}
                </option>
              @endforeach
            </select>
          </div>

          <button type="submit" class="btn-filter">Apply Filters</button>
          <a href="{{ route('item_report.view') }}" class="btn-reset">Reset</a>
        </form>
      </div>

      <!-- ITEM REPORT GRID -->
      @if ($reports->isEmpty())
        <div class="empty-state">
          <div class="empty-icon"></div>
          <h3>No Reports Found</h3>
          <p>
            @if(request('keyword') || request('status') != 'all' || request('category') != 'all')
              No reports match your current filters
            @else
              There are no published reports at the moment
            @endif
          </p>
        </div>
      @else
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
                  <br><strong>Report Date:</strong> {{ \Carbon\Carbon::parse($report->reportDate)->format('d M Y, h:i A') }}
                </p>

                <div class="btn-group">
                  <!-- View More Details Button -->
                  <a href="javascript:void(0)" class="btn view-details-btn"
                    data-report-id="{{ $report->reportID }}"
                    data-name="{{ $report->itemName }}"
                    data-category="{{ $report->category?->categoryName ?? 'N/A' }}"
                    data-status="{{ $report->reportType }}"
                    data-location="{{ $report->location?->locationName ?? 'N/A' }}"
                    data-date="{{ \Carbon\Carbon::parse($report->reportDate)->format('d M Y, h:i A') }}"
                    data-description="{{ $report->itemDescription }}"
                    data-image="{{ $report->itemImg ? asset('storage/' . $report->itemImg) : 'N/A' }}">
                    View More Details
                  </a>

                  <!-- View on Map Button -->
                  <a href="{{ route('map.show', $report->reportID) }}" target="_blank" class="btn view-map-btn">
                    View on Map
                  </a>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @endif

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