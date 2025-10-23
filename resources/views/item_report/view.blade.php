@extends('layouts.default')

@section('title', 'All Lost & Found Reports')

@section('page-css')
  <link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/LostFoundReport/item_view.css') }}">
@endsection

@section('content')
  <div class="layout">
    @include('layouts.partials.sidebar')
    <div class="main-content">

      <h1>All Lost & Found Item Reports</h1>

      <!-- FILTER BAR -->
      <form method="GET" action="{{ route('item_report.view') }}" class="filter-bar">
        <input 
          type="text" 
          name="keyword" 
          placeholder="Search by keyword..." 
          value="{{ request('keyword') }}"
        />

        <!-- STATUS FILTER -->
        <select name="status">
          <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
          @foreach ($statusEnum as $status)
            <option value="{{ strtolower($status) }}" {{ request('status') == strtolower($status) ? 'selected' : '' }}>
              {{ $status }}
            </option>
          @endforeach
        </select>

        <!-- CATEGORY FILTER -->
        <select name="category">
          <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>All</option>
          @foreach ($categoryEnum as $category)
            <option value="{{ strtolower($category) }}" {{ request('category') == strtolower($category) ? 'selected' : '' }}>
              {{ $category }}
            </option>
          @endforeach
        </select>

        <button type="submit" class="btn">Filter</button>
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
                <strong>Location:</strong> {{ $report->itemLocation }}
                <br><strong>Date:</strong> {{ \Carbon\Carbon::parse($report->reportDate)->format('d/m/Y') }}
              </p>
              <div class="btn-group">
                <a href="{{ route('reports.show', $report->reportID ?? '') }}" class="btn">View More Details</a>
                @if ($report->reportType === 'Lost')
                  <button class="btn">Found it!</button>
                @else
                  <button class="btn">Claim it!</button>
                @endif
              </div>
            </div>
          </div>
        @endforeach

        @if ($reports->isEmpty())
          <p>No reports found.</p>
        @endif
      </div>
    </div>
  </div>
@endsection

@section('page-js')
  <script src="{{ asset('js/sidebar.js') }}"></script>

  <!-- Enable this to make filters auto submit without clicking filter button -->
  <!-- <script>
    document.querySelectorAll('.filter-bar select, .filter-bar input').forEach(el => {
      el.addEventListener('change', () => el.form.submit());
    });
  </script> -->

@endsection
