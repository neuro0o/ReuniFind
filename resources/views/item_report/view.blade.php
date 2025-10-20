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
      <div class="filter-bar">
        <input type="text" placeholder="Search by keyword..." />

        <select name="status">
          <option value="">-- Item Status --</option>
          <option value="all">All</option>
          <option value="lost">Lost</option>
          <option value="found">Found</option>
        </select>

        <select name="category">
          <option value="">-- Category --</option>
          <option value="accessories">Accessories</option>
          <option value="electronics">Electronics</option>
          <option value="others">Others</option>
        </select>
      </div>

      <!-- ITEM REPORT GRID -->
      <div class="item-grid">
        @foreach ($reports as $report)
          <div class="item-card">
            <img src="{{ $report->itemImg ? asset('storage/' . $report->itemImg) : asset('images/sample/placeholder.png') }}" 
                alt="{{ $report->itemName }}">
            <div class="item-info">
              <h3>{{ $report->itemName }}</h3>
              <p>
                <strong>Item Status:</strong> {{ $report->reportType }} |
                <strong>Location:</strong> {{ $report->itemLocation }} |
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
@endsection
