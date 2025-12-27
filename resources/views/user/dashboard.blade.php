@extends('layouts.default')

<!-- TITLE -->
@section('title', 'Dashboard')

<!-- PAGE SPECIFIC CSS -->
@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/user_dashboard.css') }}">
@endsection

<!-- HEADER SECTION -->
@section('header')
    
@endsection

<!-- CONTENT SECTION -->
@section('content')
    <div class="layout">
        @include('layouts.partials.sidebar')
        <div class="content">
            <div class="page-title">
                <h1>Welcome to ReuniFind!</h1>
                <p>Found something? ReuniFind it! Because every lost thing deserves a way home.</p>
                <!-- <hr> -->
            </div>
            
            <h1 class="section-title">Quick Action</h1>
            <hr>
            <div class="quick-actions">
                <!-- <a href="{{ route('item_report.report_lost') }}" class="action-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M80-680v-200h200v80H160v120H80Zm0 600v-200h80v120h120v80H80Zm600 0v-80h120v-120h80v200H680Zm120-600v-120H680v-80h200v200h-80ZM700-260h60v60h-60v-60Zm0-120h60v60h-60v-60Zm-60 60h60v60h-60v-60Zm-60 60h60v60h-60v-60Zm-60-60h60v60h-60v-60Zm120-120h60v60h-60v-60Zm-60 60h60v60h-60v-60Zm-60-60h60v60h-60v-60Zm240-320v240H520v-240h240ZM440-440v240H200v-240h240Zm0-320v240H200v-240h240Zm-60 500v-120H260v120h120Zm0-320v-120H260v120h120Zm320 0v-120H580v120h120Z"/></svg>
                    <span>Scan QR Item Tag</span>
                </a>                     -->
                
                <a href="{{ route('item_report.report_lost') }}" class="action-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M720-120q8 0 14-6t6-14q0-8-6-14t-14-6q-8 0-14 6t-6 14q0 8 6 14t14 6Zm-20-80h40v-160h-40v160Zm-540 0v-15 15-440 440Zm0 80q-33 0-56.5-23.5T80-200v-440q0-33 23.5-56.5T160-720h160v-80q0-33 23.5-56.5T400-880h160q33 0 56.5 23.5T640-800v80h160q33 0 56.5 23.5T880-640v171q-18-13-38-22.5T800-508v-132H160v440h283q3 21 9 41t15 39H160Zm240-600h160v-80H400v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40Z"/></svg>
                    <span>Report Lost Item</span>
                </a>
                
                <a href="{{ route('item_report.report_found') }}" class="action-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M440-756q11-2 20-3t20-1q11 0 20 1t20 3v-4q0-17-11.5-28.5T480-800q-17 0-28.5 11.5T440-760v4ZM280-80q-33 0-56.5-23.5T200-160v-320q0-85 44.5-152T360-732v-28q0-50 34.5-85t85.5-35q51 0 85.5 35t34.5 85v28q63 29 105 85.5T758-518q-18-2-40-2t-40 3q-14-69-69-116t-129-47q-83 0-141.5 58.5T280-480v320h172q6 20 16.5 41.5T490-80H280Zm40-320h170q14-21 37.5-43t48.5-37H320v80Zm400-40q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440Zm0 320q11 0 18.5-7.5T746-146q0-11-7.5-18.5T720-172q-11 0-18.5 7.5T694-146q0 11 7.5 18.5T720-120Zm-18-76h36v-10q0-11 6-19.5t14-16.5q14-12 22-23t8-31q0-29-19-46.5T720-360q-23 0-41.5 13.5T652-310l32 14q3-12 12.5-21t23.5-9q15 0 23.5 7.5T752-296q0 11-6 18.5T732-262q-6 6-12.5 12T708-236q-3 6-4.5 12t-1.5 14v14ZM490-400Z"/></svg>
                    <span>Report Found Item</span>
                </a>
                
                <a href="{{ route('item_report.my_report') }}" class="action-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/></svg>
                    <span>My Report</span>
                </a>
                
                <a href="{{ route('item_report.suggested_matches') }}" class="action-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 512 512" width="24px" fill="#e3e3e3"><path d="M192 104.8c0-9.2-5.8-17.3-13.2-22.8C167.2 73.3 160 61.3 160 48c0-26.5 28.7-48 64-48s64 21.5 64 48c0 13.3-7.2 25.3-18.8 34c-7.4 5.5-13.2 13.6-13.2 22.8c0 12.8 10.4 23.2 23.2 23.2l56.8 0c26.5 0 48 21.5 48 48l0 56.8c0 12.8 10.4 23.2 23.2 23.2c9.2 0 17.3-5.8 22.8-13.2c8.7-11.6 20.7-18.8 34-18.8c26.5 0 48 28.7 48 64s-21.5 64-48 64c-13.3 0-25.3-7.2-34-18.8c-5.5-7.4-13.6-13.2-22.8-13.2c-12.8 0-23.2 10.4-23.2 23.2L384 464c0 26.5-21.5 48-48 48l-56.8 0c-12.8 0-23.2-10.4-23.2-23.2c0-9.2 5.8-17.3 13.2-22.8c11.6-8.7 18.8-20.7 18.8-34c0-26.5-28.7-48-64-48s-64 21.5-64 48c0 13.3 7.2 25.3 18.8 34c7.4 5.5 13.2 13.6 13.2 22.8c0 12.8-10.4 23.2-23.2 23.2L48 512c-26.5 0-48-21.5-48-48L0 343.2C0 330.4 10.4 320 23.2 320c9.2 0 17.3 5.8 22.8 13.2C54.7 344.8 66.7 352 80 352c26.5 0 48-28.7 48-64s-21.5-64-48-64c-13.3 0-25.3 7.2-34 18.8C40.5 250.2 32.4 256 23.2 256C10.4 256 0 245.6 0 232.8L0 176c0-26.5 21.5-48 48-48l120.8 0c12.8 0 23.2-10.4 23.2-23.2z"/></svg>
                    <span>Suggested Match</span>
                </a>

                <a href="{{ route('item_report.view') }}" class="action-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>
                    <span>Search Item</span>
                </a>
                


                <a href="{{ route('handover.index') }}" class="action-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" width="24px" fill="#e3e3e3" viewBox="0 0 576 512"><path d="M7.8 207.7c-13.1-17.8-9.3-42.8 8.5-55.9L142.9 58.5C166.2 41.3 194.5 32 223.5 32L384 32l160 0c17.7 0 32 14.3 32 32l0 64c0 17.7-14.3 32-32 32l-36.8 0-44.9 36c-22.7 18.2-50.9 28-80 28L304 224l-16 0-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32l64 0 16 0c8.8 0 16-7.2 16-16s-7.2-16-16-16l-120.6 0L63.7 216.2c-17.8 13.1-42.8 9.3-55.9-8.5zM382.4 160c0 0 0 0 0 0l.9 0c-.3 0-.6 0-.9 0zM568.2 304.3c13.1 17.8 9.3 42.8-8.5 55.9L433.1 453.5c-23.4 17.2-51.6 26.5-80.7 26.5L192 480 32 480c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l36.8 0 44.9-36c22.7-18.2 50.9-28 80-28l78.3 0 16 0 64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0-16 0c-8.8 0-16 7.2-16 16s7.2 16 16 16l120.6 0 119.7-88.2c17.8-13.1 42.8-9.3 55.9 8.5zM193.6 352c0 0 0 0 0 0l-.9 0c.3 0 .6 0 .9 0z"/></svg>    
                    <span>My Handover</span>
                </a>

                <a href="{{ route('handover.chat.index') }}" class="action-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M880-80 720-240H320q-33 0-56.5-23.5T240-320v-40h440q33 0 56.5-23.5T760-440v-280h40q33 0 56.5 23.5T880-640v560ZM160-473l47-47h393v-280H160v327ZM80-280v-520q0-33 23.5-56.5T160-880h440q33 0 56.5 23.5T680-800v280q0 33-23.5 56.5T600-440H240L80-280Zm80-240v-280 280Z"/></svg>
                    <span>Private Chat</span>
                </a>

                <!-- <a href="{{ route('item_report.view') }}" class="action-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M0-240v-63q0-43 44-70t116-27q13 0 25 .5t23 2.5q-14 21-21 44t-7 48v65H0Zm240 0v-65q0-32 17.5-58.5T307-410q32-20 76.5-30t96.5-10q53 0 97.5 10t76.5 30q32 20 49 46.5t17 58.5v65H240Zm540 0v-65q0-26-6.5-49T754-397q11-2 22.5-2.5t23.5-.5q72 0 116 26.5t44 70.5v63H780Zm-455-80h311q-10-20-55.5-35T480-370q-55 0-100.5 15T325-320ZM160-440q-33 0-56.5-23.5T80-520q0-34 23.5-57t56.5-23q34 0 57 23t23 57q0 33-23 56.5T160-440Zm640 0q-33 0-56.5-23.5T720-520q0-34 23.5-57t56.5-23q34 0 57 23t23 57q0 33-23 56.5T800-440Zm-320-40q-50 0-85-35t-35-85q0-51 35-85.5t85-34.5q51 0 85.5 34.5T600-600q0 50-34.5 85T480-480Zm0-80q17 0 28.5-11.5T520-600q0-17-11.5-28.5T480-640q-17 0-28.5 11.5T440-600q0 17 11.5 28.5T480-560Zm1 240Zm-1-280Z"/></svg>
                    <span>Forum</span>
                </a> -->
            </div>

            <h1 class="section-title">My Activity Summary</h1>
            <hr>
            <div class="activity-summary">

                <!-- ------------------ Item Reports ------------------ -->
                <h2 class="summary-group-title">Item Reports</h2><br>
                <div class="summary-grid">
                    <!-- <div class="summary-card">
                        <h3>My Total Lost Item Reports</h3>
                        <span>{{ $lostReportsCount }}</span>
                    </div>
                    <div class="summary-card">
                        <h3>My Total Found Item Reports</h3>
                        <span>{{ $foundReportsCount }}</span>
                    </div> -->
                    <div class="summary-card">
                        <h3>Pending Item Reports</h3>
                        <span>{{ $pendingReportsCount }}</span>
                    </div>
                    <div class="summary-card">
                        <h3>Published Item Reports</h3>
                        <span>{{ $publishedReportsCount }}</span>
                    </div>
                    <div class="summary-card">
                        <h3>Rejected Item Reports</h3>
                        <span>{{ $rejectedReportsCount }}</span>
                    </div>
                    <div class="summary-card">
                        <h3>Completed Item Reports</h3>
                        <span>{{ $completedReportsCount }}</span>
                    </div>
                </div><br><br>

                <!-- ------------------ Handover Requests ------------------ -->
                <h2 class="summary-group-title">Handover Requests</h2><br>
                <div class="summary-grid">
                    <div class="summary-card">
                        <h3>New Claim Requests</h3>
                        <span>{{ $newClaimRequestsCount }}</span>
                    </div>
                    <div class="summary-card">
                        <h3>New Return Requests</h3>
                        <span>{{ $newReturnRequestsCount }}</span>
                    </div>
                    <div class="summary-card">
                        <h3>Pending Handover Requests</h3>
                        <span>{{ $pendingHandoverCount }}</span>
                    </div>
                    <div class="summary-card">
                        <h3>Accepted Handover Requests</h3>
                        <span>{{ $acceptedHandoverCount }}</span>
                    </div>
                    <div class="summary-card">
                        <h3>Rejected Handover Requests</h3>
                        <span>{{ $rejectedHandoverCount }}</span>
                    </div>
                </div><br><br>

                <!-- ------------------ Matches ------------------ -->
                <h2 class="summary-group-title">Matches</h2><br>
                <div class="summary-grid">
                    <div class="summary-card">
                        <h3>New Suggested Matches</h3>
                        <span>{{ $newSuggestedMatchesCount }}</span>
                    </div>
                </div><br><br>
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