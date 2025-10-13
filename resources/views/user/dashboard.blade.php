@extends('layouts.default')

<!-- TITLE -->
@section('title', 'Dashboard')

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
            <div class="dashboard-header">
                <h1>Welcome to ReuniFind!</h1>
                <p>Found something? ReuniFind it! Because every lost thing deserves a way home.</p>
            </div>

            <div class="quick-actions">
                <button>Scan QR Item Tag</button>
                <button>Report Lost Item</button>
                <button>Report Found Item</button>
                <button>View My Reports</button>
                <button>Search Items</button>
                <button>Forums</button>
            </div>

            <section class="activity-summary">
                <div class="activity-card">
                    <h3>7</h3>
                    <p>Total Lost Items Reported</p>
                </div>
                <div class="activity-card">
                    <h3>5</h3>
                    <p>Total Found Items Reported</p>
                </div>
                <div class="activity-card">
                    <h3>1</h3>
                    <p>My Active Lost Report</p>
                </div>
                <div class="activity-card">
                    <h3>0</h3>
                    <p>My Active Found Report</p>
                </div>
            </section>

            <div class="announcement">
                <h4>Announcement</h4>
                <ul>
                    <li>🛠 System Maintenance: May 22, 10PM–12AM</li>
                    <li>🆕 New Feature: QR Tag Registration is now live!</li>
                </ul>
            </div>
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