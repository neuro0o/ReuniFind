@extends('layouts.default')

<!-- TITLE -->
@section('title', 'Admin Dashboard')

<!-- PAGE SPECIFIC CSS -->
@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/admin_sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/admin_dashboard.css') }}">
@endsection

<!-- HEADER SECTION -->
@section('header')
    
@endsection

<!-- CONTENT SECTION -->
@section('content')
    <div class="layout">
        @include('layouts.partials.admin_sidebar')
        <div class="content">
            <div class="page-title">
                <h1>Welcome to ReuniFind Admin Dashboard</h1>
            </div>

            <h1 class="section-title">Quick Action</h1>
            <hr>
            <div class="quick-actions">
                <a href="{{ route('admin.users.index') }}" class="action-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="24px" fill="#e3e3e3"><path d="M320 312C386.3 312 440 258.3 440 192C440 125.7 386.3 72 320 72C253.7 72 200 125.7 200 192C200 258.3 253.7 312 320 312zM290.3 368C191.8 368 112 447.8 112 546.3C112 562.7 125.3 576 141.7 576L498.3 576C514.7 576 528 562.7 528 546.3C528 447.8 448.2 368 349.7 368L290.3 368z"/></svg>
                    <span>Manage User</span>
                </a>

                <a href="{{ route('categories.index') }}" class="action-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="24px" fill="#e3e3e3"><path d="M104 112C90.7 112 80 122.7 80 136L80 184C80 197.3 90.7 208 104 208L152 208C165.3 208 176 197.3 176 184L176 136C176 122.7 165.3 112 152 112L104 112zM256 128C238.3 128 224 142.3 224 160C224 177.7 238.3 192 256 192L544 192C561.7 192 576 177.7 576 160C576 142.3 561.7 128 544 128L256 128zM256 288C238.3 288 224 302.3 224 320C224 337.7 238.3 352 256 352L544 352C561.7 352 576 337.7 576 320C576 302.3 561.7 288 544 288L256 288zM256 448C238.3 448 224 462.3 224 480C224 497.7 238.3 512 256 512L544 512C561.7 512 576 497.7 576 480C576 462.3 561.7 448 544 448L256 448zM80 296L80 344C80 357.3 90.7 368 104 368L152 368C165.3 368 176 357.3 176 344L176 296C176 282.7 165.3 272 152 272L104 272C90.7 272 80 282.7 80 296zM104 432C90.7 432 80 442.7 80 456L80 504C80 517.3 90.7 528 104 528L152 528C165.3 528 176 517.3 176 504L176 456C176 442.7 165.3 432 152 432L104 432z"/></svg>
                    <span>Manage Item Category</span>
                </a>

                <a href="{{ route('locations.index') }}" class="action-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="24px" fill="#e3e3e3"><path d="M128 252.6C128 148.4 214 64 320 64C426 64 512 148.4 512 252.6C512 371.9 391.8 514.9 341.6 569.4C329.8 582.2 310.1 582.2 298.3 569.4C248.1 514.9 127.9 371.9 127.9 252.6zM320 320C355.3 320 384 291.3 384 256C384 220.7 355.3 192 320 192C284.7 192 256 220.7 256 256C256 291.3 284.7 320 320 320z"/></svg>
                    <span>Manage Item Location</span>
                </a>
                
                <a href="{{ route('admin.manage_report_lost') }}" class="action-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M440-756q11-2 20-3t20-1q11 0 20 1t20 3v-4q0-17-11.5-28.5T480-800q-17 0-28.5 11.5T440-760v4ZM280-80q-33 0-56.5-23.5T200-160v-320q0-85 44.5-152T360-732v-28q0-50 34.5-85t85.5-35q51 0 85.5 35t34.5 85v28q63 29 105 85.5T758-518q-18-2-40-2t-40 3q-14-69-69-116t-129-47q-83 0-141.5 58.5T280-480v320h172q6 20 16.5 41.5T490-80H280Zm40-320h170q14-21 37.5-43t48.5-37H320v80Zm400-40q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440Zm0 320q11 0 18.5-7.5T746-146q0-11-7.5-18.5T720-172q-11 0-18.5 7.5T694-146q0 11 7.5 18.5T720-120Zm-18-76h36v-10q0-11 6-19.5t14-16.5q14-12 22-23t8-31q0-29-19-46.5T720-360q-23 0-41.5 13.5T652-310l32 14q3-12 12.5-21t23.5-9q15 0 23.5 7.5T752-296q0 11-6 18.5T732-262q-6 6-12.5 12T708-236q-3 6-4.5 12t-1.5 14v14ZM490-400Z"/></svg>
                    <span>Manage Lost Item Report</span>
                </a>
                
                <a href="{{ route('admin.manage_report_found') }}" class="action-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M720-120q8 0 14-6t6-14q0-8-6-14t-14-6q-8 0-14 6t-6 14q0 8 6 14t14 6Zm-20-80h40v-160h-40v160Zm-540 0v-15 15-440 440Zm0 80q-33 0-56.5-23.5T80-200v-440q0-33 23.5-56.5T160-720h160v-80q0-33 23.5-56.5T400-880h160q33 0 56.5 23.5T640-800v80h160q33 0 56.5 23.5T880-640v171q-18-13-38-22.5T800-508v-132H160v440h283q3 21 9 41t15 39H160Zm240-600h160v-80H400v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40Z"/></svg>
                    <span>Manage Found Item Report</span>
                </a>
                
                <a href="{{ route('item_report.view') }}" class="action-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>
                    <span>Search Items</span>
                </a>

                <a href="{{ route('admin.feedbacks') }}" class="action-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-360q17 0 28.5-11.5T520-400q0-17-11.5-28.5T480-440q-17 0-28.5 11.5T440-400q0 17 11.5 28.5T480-360Zm-40-160h80v-240h-80v240ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z"/></svg>
                    <span>Manage Feedback</span>
                </a>

                <a href="{{ route('admin.faqs') }}" class="action-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M424-320q0-81 14.5-116.5T500-514q41-36 62.5-62.5T584-637q0-41-27.5-68T480-732q-51 0-77.5 31T365-638l-103-44q21-64 77-111t141-47q105 0 161.5 58.5T698-641q0 50-21.5 85.5T609-475q-49 47-59.5 71.5T539-320H424Zm56 240q-33 0-56.5-23.5T400-160q0-33 23.5-56.5T480-240q33 0 56.5 23.5T560-160q0 33-23.5 56.5T480-80Z"/></svg>
                    <span>Manage FAQ</span>
                </a>
            </div>


            <h1 class="section-title">System Summary</h1>
            <hr>
            <div class="activity-summary">

                <!-- ------------------ System Summary ------------------ -->
                <h2 class="summary-group-title">SYSTEM SUMMARY</h2><br>
                <div class="summary-grid">
                    <div class="summary-card">
                        <h3>Total Users</h3>
                        <span>{{ $totalUsers }}</span>
                    </div>
                    <div class="summary-card">
                        <h3>Total Lost Item Reports</h3>
                        <span>{{ $totalLostReports }}</span>
                    </div>
                    <div class="summary-card">
                        <h3>Total Found Item Reports</h3>
                        <span>{{ $totalFoundReports }}</span>
                    </div>
                    <div class="summary-card">
                        <h3>Pending Lost Item Reports</h3>
                        <span>{{ $pendingLostReports }}</span>
                    </div>
                    <div class="summary-card">
                        <h3>Pending Found Item Reports</h3>
                        <span>{{ $pendingFoundReports }}</span>
                    </div>
                    <div class="summary-card">
                        <h3>Published Lost Item Reports</h3>
                        <span>{{ $publishedLostReports }}</span>
                    </div>
                    <div class="summary-card">
                        <h3>Published Found Item Reports</h3>
                        <span>{{ $publishedFoundReports }}</span>
                    </div>
                    <div class="summary-card">
                        <h3>Unresolved Cases</h3>
                        <span>{{ $unresolvedCases }}</span>
                    </div>
                    <div class="summary-card">
                        <h3>Completed Cases</h3>
                        <span>{{ $completedCases }}</span>
                    </div>
                </div><br><br>

                <!-- ------------------ Monthly Report ------------------ -->
                <h2 class="summary-group-title">MONTHLY REPORT</h2><br>
                <div class="summary-grid">
                    <div class="summary-card">
                        <h3>Total Item Reports This Month</h3>
                        <span>{{ $totalReportsThisMonth }}</span>
                    </div>
                    <div class="summary-card">
                        <h3>Total Lost Reports This Month</h3>
                        <span>{{ $lostReportsThisMonth }}</span>
                    </div>
                    <div class="summary-card">
                        <h3>Total Found Reports This Month</h3>
                        <span>{{ $foundReportsThisMonth }}</span>
                    </div>
                    <div class="summary-card">
                        <h3>Unresolved Cases This Month</h3>
                        <span>{{ $unresolvedCasesThisMonth }}</span>
                    </div>
                    <div class="summary-card">
                        <h3>Completed Cases This Month</h3>
                        <span>{{ $completedCasesThisMonth }}</span>
                    </div>
                </div><br><br>

                <!-- ------------------ Top Reports Charts ------------------ -->
                <!-- <h2 class="summary-group-title">TOP REPORTS</h2>

                <div class="chart-card">
                    <h3>Top Lost Categories</h3>
                    <canvas id="topLostCategoriesChart"></canvas>
                </div>

                <div class="chart-card">
                    <h3>Top Found Categories</h3>
                    <canvas id="topFoundCategoriesChart"></canvas>
                </div>

                <div class="chart-card">
                    <h3>Top Lost Locations</h3>
                    <canvas id="topLostLocationsChart"></canvas>
                </div>

                <div class="chart-card">
                    <h3>Top Found Locations</h3>
                    <canvas id="topFoundLocationsChart"></canvas>
                </div> -->

                <h2 class="summary-group-title">TOP REPORTS</h2>

                <div class="charts-grid">
                <div class="chart-card">
                    <h3>Top Lost Categories</h3>
                    <canvas id="topLostCategoriesChart"></canvas>
                </div>
                <div class="chart-card">
                    <h3>Top Found Categories</h3>
                    <canvas id="topFoundCategoriesChart"></canvas>
                </div>
                <div class="chart-card">
                    <h3>Top Lost Locations</h3>
                    <canvas id="topLostLocationsChart"></canvas>
                </div>
                <div class="chart-card">
                    <h3>Top Found Locations</h3>
                    <canvas id="topFoundLocationsChart"></canvas>
                </div>
            </div>
            </div>

        </div>
    </div>
@endsection


<!-- FOOTER SECTION -->
@section('footer')
    
@endsection
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- PAGE SPECIFIC JS -->
@section('page-js')
    <!-- FIXME: Fix Sidebar Collapse Behavior -->
    <script src="{{ asset('js/admin_sidebar.js') }}"></script>


    <script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
function renderBarChart(canvasId, labels, data, labelText, bgColor, borderColor) {
    new Chart(document.getElementById(canvasId).getContext('2d'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: labelText,
                data: data,
                backgroundColor: bgColor,
                borderColor: borderColor,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
}

// Top Lost Categories
renderBarChart(
    'topLostCategoriesChart',
    {!! json_encode($topLostCategories->pluck('categoryName')) !!},
    {!! json_encode($topLostCategories->pluck('lost_reports_count')) !!},
    'Lost Reports',
    'rgba(220, 53, 69, 0.7)',
    'rgba(220, 53, 69, 1)'
);

// Top Found Categories
renderBarChart(
    'topFoundCategoriesChart',
    {!! json_encode($topFoundCategories->pluck('categoryName')) !!},
    {!! json_encode($topFoundCategories->pluck('found_reports_count')) !!},
    'Found Reports',
    'rgba(40, 167, 69, 0.7)',
    'rgba(40, 167, 69, 1)'
);

// Top Lost Locations
renderBarChart(
    'topLostLocationsChart',
    {!! json_encode($topLostLocations->pluck('locationName')) !!},
    {!! json_encode($topLostLocations->pluck('lost_reports_count')) !!},
    'Lost Reports',
    'rgba(220, 53, 69, 0.7)',
    'rgba(220, 53, 69, 1)'
);

// Top Found Locations
renderBarChart(
    'topFoundLocationsChart',
    {!! json_encode($topFoundLocations->pluck('locationName')) !!},
    {!! json_encode($topFoundLocations->pluck('found_reports_count')) !!},
    'Found Reports',
    'rgba(40, 167, 69, 0.7)',
    'rgba(40, 167, 69, 1)'
);
</script>


    </script>

@endsection
