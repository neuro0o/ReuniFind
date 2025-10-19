@extends('layouts.default')

@section('title', 'Lost & Found Reports')

@section('page-css')
<link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
<link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">
<style>
  body {
    background-color: #e7ebf7;
    font-family: 'Inter', sans-serif;
  }

  .main-content {
    padding: 40px;
  }

  h1 {
    color: #2d3e5e;
    font-weight: 800;
    font-size: 2rem;
    margin-bottom: 30px;
  }

  .filter-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: center;
    margin-bottom: 25px;
  }

  .filter-bar input,
  .filter-bar select {
    padding: 10px 14px;
    border: 1.5px solid #cdd3e1;
    border-radius: 8px;
    font-size: 14px;
    color: #333;
    outline: none;
  }

  .filter-bar input {
    width: 250px;
  }

  .filter-bar select {
    width: 180px;
  }

  .item-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
    gap: 25px;
  }

  .item-card {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    background: #f5f7ff;
    border-radius: 12px;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
    padding: 18px 25px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .item-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.12);
  }

  .item-card img {
    width: 80px;
    height: 80px;
    border-radius: 10px;
    object-fit: cover;
  }

  .item-info {
    flex: 1;
  }

  .item-info h3 {
    margin: 0;
    font-size: 1.05rem;
    font-weight: 700;
    color: #223354;
  }

  .item-info p {
    font-size: 0.9rem;
    margin: 4px 0;
    color: #44536e;
  }

  .btn-group {
    display: flex;
    gap: 10px;
    margin-top: 10px;
  }

  .btn {
    border: 1.5px solid #44536e;
    border-radius: 8px;
    background: none;
    color: #2b3d6d;
    font-weight: 600;
    font-size: 0.85rem;
    padding: 6px 14px;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .btn:hover {
    background: #2b3d6d;
    color: white;
  }
</style>
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
        <option value="">-- Select Value --</option>
        <option value="all">All</option>
        <option value="lost">Lost</option>
        <option value="found">Found</option>
      </select>

      <select name="category">
        <option value="">-- Select Value --</option>
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
              <strong>Date:</strong> {{ \Carbon\Carbon::parse($report->reportDate)->format('d/m/Y') }}
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
