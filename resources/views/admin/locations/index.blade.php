@extends('layouts.default')

@section('title', 'Manage Item Locations')

@section('page-css')
  <link rel="stylesheet" href="{{ asset('css/utils/admin_sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/LostFoundReport/item_myReport.css') }}">
@endsection

@section('content')
<div class="layout">
    @include('layouts.partials.admin_sidebar')

    <div class="main-content">
        <h1>Item Locations</h1>

        <a href="{{ route('locations.create') }}" class="btn btn-primary" style="margin-bottom: 1rem;">+ Add Location</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($locations->count() > 0)
        <div class="table-container">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Location Name</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locations as $location)
                    <tr>
                        <td data-label="No.">{{ $loop->iteration }}.</td>
                        <td data-label="Location Name">{{ $location->locationName }}</td>
                        <td data-label="Latitude">{{ $location->latitude }}</td>
                        <td data-label="Longitude">{{ $location->longitude }}</td>
                        <td data-label="Action">
                            <div class="btn-group">
                                <a href="{{ route('locations.edit', $location->locationID) }}" class="btn edit">Edit</a>
                                <form action="{{ route('locations.destroy', $location->locationID) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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
            <p class="empty-text">No locations available.</p>
        @endif
    </div>
</div>
@endsection

@section('page-js')
  <script src="{{ asset('js/admin_sidebar.js') }}"></script>
@endsection
