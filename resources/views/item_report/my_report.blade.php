@extends('layouts.default')

<!-- TITLE -->
@section('title', 'My Lost & Found Item Report')

<!-- PAGE SPECIFIC CSS -->
@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">
@endsection

<!-- HEADER SECTION -->
@section('header')
    
@endsection

<!-- CONTENT SECTION -->
@section('content')
  <div class="layout">
    @include('layouts.partials.sidebar')
    <div class="main-content">
      <h1>My Lost & Found Item Report</h1>

      @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($userReports->count() > 0)
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userReports as $report)
                        <tr>
                            <td>{{ $report->reportType }}</td>
                            <td>{{ $report->itemName }}</td>
                            <td>{{ $report->itemCategory }}</td>
                            <td>{{ $report->itemDescription }}</td>
                            <td>{{ $report->itemLocation }}</td>
                            <td>{{ $report->reportDate }}</td>
                            <td>
                                @if($report->itemImg)
                                    <img src="{{ asset('storage/' . $report->itemImg) }}" alt="Item Image" width="80">
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('item_report.edit', $report->reportID) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('item_report.destroy', $report->reportID) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this report?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>You have not submitted any reports yet.</p>
        @endif
        
    </div>
  </div>
@endsection


<!-- FOOTER SECTION -->
@section('footer')
    
@endsection

<!-- PAGE SPECIFIC JS -->
@section('page-js')
    <!-- FIXME: Fix Sidebar Collapse Behavior -->
    <script src="{{ asset('js/sidebar.js') }}"></script>
@endsection