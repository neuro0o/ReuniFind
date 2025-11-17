@extends('layouts.default')

@section('title', 'Manage Users')

@section('page-css')
<link rel="stylesheet" href="{{ asset('css/utils/admin_sidebar.css') }}">
<link rel="stylesheet" href="{{ asset('css/LostFoundReport/item_myReport.css') }}">
@endsection

@section('content')
<div class="layout">
    @include('layouts.partials.admin_sidebar')

    <div class="main-content">
        <h1>Manage Users</h1>

        <a href="{{ route('admin.users.create') }}" class="btn btn-primary" style="margin-bottom: 1rem;">
            + Add User
        </a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($users->count() > 0)
        <div class="table-container">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Profile</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Contact</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td data-label="No.">{{ $loop->iteration }}.</td>
                        <td data-label="Profile">
                            @php
                                $profileUrl = $user->profileImg 
                                    ? asset('storage/' . $user->profileImg)
                                    : ($user->userRole === 'Admin' 
                                        ? asset('images/profiles/admin_default.png') 
                                        : asset('images/profiles/user_default.png'));
                            @endphp

                            <img src="{{ $profileUrl }}" 
                                alt="{{ $user->userName }}" 
                                style="width:50px; height:50px; border-radius:50%;">
                        </td>
                        <td data-label="Name">{{ $user->userName }}</td>
                        <td data-label="Email">{{ $user->userEmail }}</td>
                        <td data-label="Role">{{ $user->userRole }}</td>
                        <td data-label="Contact">{{ $user->contactInfo ?? 'N/A' }}</td>

                        <td data-label="Action">
                            <div class="btn-group">
                                <a href="{{ route('admin.users.edit', $user->userID) }}" class="btn edit">Edit</a>

                                <form action="{{ route('admin.users.delete', $user->userID) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this user?')">
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
            <p class="empty-text">No users found.</p>
        @endif
    </div>
</div>
@endsection

@section('page-js')
  <script src="{{ asset('js/admin_sidebar.js') }}"></script>
@endsection
