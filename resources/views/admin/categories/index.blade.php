@extends('layouts.default')

@section('title', 'Manage Item Categories')

@section('page-css')
<link rel="stylesheet" href="{{ asset('css/utils/admin_sidebar.css') }}">
<link rel="stylesheet" href="{{ asset('css/LostFoundReport/item_myReport.css') }}">
@endsection

@section('content')
<div class="layout">
    @include('layouts.partials.admin_sidebar')

    <div class="main-content">
        <h1>Manage Item Category</h1>

        <a href="{{ route('categories.create') }}" class="btn btn-primary" style="margin-bottom: 1rem;">+ Add Category</a>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($categories->count() > 0)
        <div class="table-container">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td data-label="No.">{{ $loop->iteration }}.</td>
                        <td data-label="Category Name">{{ $category->categoryName }}</td>
                        <td data-label="Description">{{ $category->description ?? 'N/A' }}</td>
                        <td data-label="Action">
                            <div class="btn-group">
                                <a href="{{ route('categories.edit', $category->categoryID) }}" class="btn edit">Edit</a>
                                <form action="{{ route('categories.destroy', $category->categoryID) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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
            <p class="empty-text">No categories available.</p>
        @endif
    </div>
</div>
@endsection

@section('page-js')
    <script src="{{ asset('js/admin_sidebar.js') }}"></script>
@endsection
