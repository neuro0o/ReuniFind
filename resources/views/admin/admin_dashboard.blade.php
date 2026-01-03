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
            <!-- Page Header with Export Button -->
            <div class="page-header">
                <div class="page-title-section">
                    <h1>Welcome Admin!</h1>
                </div>
            </div>

            <!-- Quick Actions Section (Hidden in Print) -->
            <div class="print-hide">
                <h1 class="section-title">Quick Actions</h1>
                <hr>
                <div class="quick-actions">
                    <a href="{{ route('admin.users.index') }}" class="action-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="24px" fill="#e3e3e3"><path d="M320 312C386.3 312 440 258.3 440 192C440 125.7 386.3 72 320 72C253.7 72 200 125.7 200 192C200 258.3 253.7 312 320 312zM290.3 368C191.8 368 112 447.8 112 546.3C112 562.7 125.3 576 141.7 576L498.3 576C514.7 576 528 562.7 528 546.3C528 447.8 448.2 368 349.7 368L290.3 368z"/></svg>
                        <span>Manage Users</span>
                    </a>

                    <a href="{{ route('categories.index') }}" class="action-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="24px" fill="#e3e3e3"><path d="M104 112C90.7 112 80 122.7 80 136L80 184C80 197.3 90.7 208 104 208L152 208C165.3 208 176 197.3 176 184L176 136C176 122.7 165.3 112 152 112L104 112zM256 128C238.3 128 224 142.3 224 160C224 177.7 238.3 192 256 192L544 192C561.7 192 576 177.7 576 160C576 142.3 561.7 128 544 128L256 128zM256 288C238.3 288 224 302.3 224 320C224 337.7 238.3 352 256 352L544 352C561.7 352 576 337.7 576 320C576 302.3 561.7 288 544 288L256 288zM256 448C238.3 448 224 462.3 224 480C224 497.7 238.3 512 256 512L544 512C561.7 512 576 497.7 576 480C576 462.3 561.7 448 544 448L256 448zM80 296L80 344C80 357.3 90.7 368 104 368L152 368C165.3 368 176 357.3 176 344L176 296C176 282.7 165.3 272 152 272L104 272C90.7 272 80 282.7 80 296zM104 432C90.7 432 80 442.7 80 456L80 504C80 517.3 90.7 528 104 528L152 528C165.3 528 176 517.3 176 504L176 456C176 442.7 165.3 432 152 432L104 432z"/></svg>
                        <span>Manage Item Categories</span>
                    </a>

                    <a href="{{ route('locations.index') }}" class="action-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="24px" fill="#e3e3e3"><path d="M128 252.6C128 148.4 214 64 320 64C426 64 512 148.4 512 252.6C512 371.9 391.8 514.9 341.6 569.4C329.8 582.2 310.1 582.2 298.3 569.4C248.1 514.9 127.9 371.9 127.9 252.6zM320 320C355.3 320 384 291.3 384 256C384 220.7 355.3 192 320 192C284.7 192 256 220.7 256 256C256 291.3 284.7 320 320 320z"/></svg>
                        <span>Manage Item Locations</span>
                    </a>
                    
                    <a href="{{ route('admin.manage_report_lost') }}" class="action-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M440-756q11-2 20-3t20-1q11 0 20 1t20 3v-4q0-17-11.5-28.5T480-800q-17 0-28.5 11.5T440-760v4ZM280-80q-33 0-56.5-23.5T200-160v-320q0-85 44.5-152T360-732v-28q0-50 34.5-85t85.5-35q51 0 85.5 35t34.5 85v28q63 29 105 85.5T758-518q-18-2-40-2t-40 3q-14-69-69-116t-129-47q-83 0-141.5 58.5T280-480v320h172q6 20 16.5 41.5T490-80H280Zm40-320h170q14-21 37.5-43t48.5-37H320v80Zm400-40q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440Zm0 320q11 0 18.5-7.5T746-146q0-11-7.5-18.5T720-172q-11 0-18.5 7.5T694-146q0 11 7.5 18.5T720-120Zm-18-76h36v-10q0-11 6-19.5t14-16.5q14-12 22-23t8-31q0-29-19-46.5T720-360q-23 0-41.5 13.5T652-310l32 14q3-12 12.5-21t23.5-9q15 0 23.5 7.5T752-296q0 11-6 18.5T732-262q-6 6-12.5 12T708-236q-3 6-4.5 12t-1.5 14v14ZM490-400Z"/></svg>
                        <span>Manage Lost Item Reports</span>
                    </a>
                    
                    <a href="{{ route('admin.manage_report_found') }}" class="action-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M720-120q8 0 14-6t6-14q0-8-6-14t-14-6q-8 0-14 6t-6 14q0 8 6 14t14 6Zm-20-80h40v-160h-40v160Zm-540 0v-15 15-440 440Zm0 80q-33 0-56.5-23.5T80-200v-440q0-33 23.5-56.5T160-720h160v-80q0-33 23.5-56.5T400-880h160q33 0 56.5 23.5T640-800v80h160q33 0 56.5 23.5T880-640v171q-18-13-38-22.5T800-508v-132H160v440h283q3 21 9 41t15 39H160Zm240-600h160v-80H400v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40Z"/></svg>
                        <span>Manage Found Item Reports</span>
                    </a>
                    
                    <a href="{{ route('item_report.view') }}" class="action-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>
                        <span>Search Items</span>
                    </a>

                    <a href="{{ route('admin.feedbacks') }}" class="action-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-360q17 0 28.5-11.5T520-400q0-17-11.5-28.5T480-440q-17 0-28.5 11.5T440-400q0 17 11.5 28.5T480-360Zm-40-160h80v-240h-80v240ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z"/></svg>
                        <span>Manage User Feedback</span>
                    </a>

                    <a href="{{ route('admin.faqs') }}" class="action-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M424-320q0-81 14.5-116.5T500-514q41-36 62.5-62.5T584-637q0-41-27.5-68T480-732q-51 0-77.5 31T365-638l-103-44q21-64 77-111t141-47q105 0 161.5 58.5T698-641q0 50-21.5 85.5T609-475q-49 47-59.5 71.5T539-320H424Zm56 240q-33 0-56.5-23.5T400-160q0-33 23.5-56.5T480-240q33 0 56.5 23.5T560-160q0 33-23.5 56.5T480-80Z"/></svg>
                        <span>Manage FAQ</span>
                    </a>

                    <a href="{{ route('admin.forum.posts') }}" class="action-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M0-240v-63q0-43 44-70t116-27q13 0 25 .5t23 2.5q-14 21-21 44t-7 48v65H0Zm240 0v-65q0-32 17.5-58.5T307-410q32-20 76.5-30t96.5-10q53 0 97.5 10t76.5 30q32 20 49 46.5t17 58.5v65H240Zm540 0v-65q0-26-6.5-49T754-397q11-2 22.5-2.5t23.5-.5q72 0 116 26.5t44 70.5v63H780Zm-455-80h311q-10-20-55.5-35T480-370q-55 0-100.5 15T325-320ZM160-440q-33 0-56.5-23.5T80-520q0-34 23.5-57t56.5-23q34 0 57 23t23 57q0 33-23 56.5T160-440Zm640 0q-33 0-56.5-23.5T720-520q0-34 23.5-57t56.5-23q34 0 57 23t23 57q0 33-23 56.5T800-440Zm-320-40q-50 0-85-35t-35-85q0-51 35-85.5t85-34.5q51 0 85.5 34.5T600-600q0 50-34.5 85T480-480Zm0-80q17 0 28.5-11.5T520-600q0-17-11.5-28.5T480-640q-17 0-28.5 11.5T440-600q0 17 11.5 28.5T480-560Zm1 240Zm-1-280Z"/></svg>
                        <span>Manage Forum</span>
                    </a>
                </div>
            </div>

            <!-- Analytics Section -->
            <div class="analytics-section">
                <!-- SECTION 1: OVERALL SYSTEM SUMMARY -->
                <h1 class="section-title">Overall System Summary</h1>
                <hr>
                <div class="summary-grid">
                    <div class="summary-card highlight">
                        <div class="card-content">
                            <h3>Total Registered Users</h3>
                            <span class="stat-number">{{ $totalUsers }}</span>
                        </div>
                    </div>

                    <div class="summary-card">
                        <div class="card-content">
                            <h3>Total Lost Item Reports</h3>
                            <span class="stat-number">{{ $totalLostReports }}</span>
                        </div>
                    </div>

                    <div class="summary-card">
                        <div class="card-content">
                            <h3>Total Found Item Reports</h3>
                            <span class="stat-number">{{ $totalFoundReports }}</span>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: REPORT STATUS BREAKDOWN -->
                <h1 class="section-title">Report Status Overview</h1>
                <hr>
                <div class="summary-grid">
                    <div class="summary-card status-pending">
                        <h3>Pending Lost Reports</h3>
                        <span class="stat-number">{{ $pendingLostReports }}</span>
                        <p class="card-description">Awaiting admin review</p>
                    </div>

                    <div class="summary-card status-pending">
                        <h3>Pending Found Reports</h3>
                        <span class="stat-number">{{ $pendingFoundReports }}</span>
                        <p class="card-description">Awaiting admin review</p>
                    </div>

                    <div class="summary-card status-published">
                        <h3>Published Lost Reports</h3>
                        <span class="stat-number">{{ $publishedLostReports }}</span>
                        <p class="card-description">Waiting & ready for handover process</p>
                    </div>

                    <div class="summary-card status-published">
                        <h3>Published Found Reports</h3>
                        <span class="stat-number">{{ $publishedFoundReports }}</span>
                        <p class="card-description">Waiting & ready for handover process</p>
                    </div>

                    <div class="summary-card status-unresolved">
                        <h3>Unresolved Cases</h3>
                        <span class="stat-number">{{ $unresolvedCases }}</span>
                        <p class="card-description">Waiting for handover completion</p>
                    </div>

                    <div class="summary-card status-completed">
                        <h3>Completed Cases</h3>
                        <span class="stat-number">{{ $completedCases }}</span>
                        <p class="card-description">Items successfully reunited</p>
                    </div>
                </div>

                <!-- Success Rate Indicator -->
                <div class="success-rate-card">
                    <div class="rate-content">
                        <h3>Handover Success Rate</h3>
                        <div class="rate-display">
                            <span class="rate-number">{{ $successRate }}%</span>
                            <p>{{ $completedCases }} out of {{ $completedCases + \App\Models\HandoverRequest::whereIn('requestStatus', ['Pending', 'Approved', 'Rejected'])->count() }} handover attempts succeeded</p>
                        </div>
                    </div>
                    <div class="rate-bar">
                        <div class="rate-fill" style="width: {{ $successRate }}%"></div>
                    </div>
                </div>

                <!-- SECTION 3: MONTHLY STATISTICS -->
                <h1 class="section-title">Monthly Report - {{ now()->format('F Y') }}</h1>
                <hr>
                <div class="summary-grid">
                    <div class="summary-card monthly">
                        <h3>Total Reports This Month</h3>
                        <span class="stat-number">{{ $totalReportsThisMonth }}</span>
                    </div>

                    <div class="summary-card monthly">
                        <h3>Lost Reports This Month</h3>
                        <span class="stat-number">{{ $lostReportsThisMonth }}</span>
                    </div>

                    <div class="summary-card monthly">
                        <h3>Found Reports This Month</h3>
                        <span class="stat-number">{{ $foundReportsThisMonth }}</span>
                    </div>

                    <div class="summary-card monthly">
                        <h3>Unresolved This Month</h3>
                        <span class="stat-number">{{ $unresolvedCasesThisMonth }}</span>
                    </div>

                    <div class="summary-card monthly">
                        <h3>Completed This Month</h3>
                        <span class="stat-number">{{ $completedCasesThisMonth }}</span>
                    </div>

                    <div class="summary-card monthly highlight">
                        <h3>Monthly Success Rate</h3>
                        <span class="stat-number">{{ $monthlySuccessRate }}%</span>
                        <p class="card-description">Handover completion rate</p>
                    </div>
                </div>

                <!-- SECTION 4: TOP CATEGORIES & HOTSPOTS -->
                <h1 class="section-title">Category & Location Analysis</h1>
                <hr>
                
                <div class="charts-grid">
                    <!-- Top Lost Categories Chart -->
                    <div class="chart-card">
                        <h3>Top Lost Item Categories</h3>
                        <canvas id="topLostCategoriesChart"></canvas>
                        <div class="chart-legend">
                            <p class="legend-note">Most frequently reported lost items</p>
                        </div>
                    </div>

                    <!-- Top Found Categories Chart -->
                    <div class="chart-card">
                        <h3>Top Found Item Categories</h3>
                        <canvas id="topFoundCategoriesChart"></canvas>
                        <div class="chart-legend">
                            <p class="legend-note">Most frequently found items</p>
                        </div>
                    </div>

                    <!-- Top Lost Locations Chart -->
                    <div class="chart-card">
                        <h3>Hotspot Locations - Lost Items</h3>
                        <canvas id="topLostLocationsChart"></canvas>
                        <div class="chart-legend">
                            <p class="legend-note">Locations with most lost item reports</p>
                        </div>
                    </div>

                    <!-- Top Found Locations Chart -->
                    <div class="chart-card">
                        <h3>Hotspot Locations - Found Items</h3>
                        <canvas id="topFoundLocationsChart"></canvas>
                        <div class="chart-legend">
                            <p class="legend-note">Locations with most found item reports</p>
                        </div>
                    </div>
                </div>

                <!-- Key Insights Section -->
                <div class="insights-section">
                    <h3>Key Insights</h3>
                    <ul class="insights-list">
                        @if($topLostCategories->isNotEmpty())
                            @php
                                $maxLostCount = $topLostCategories->first()->lost_reports_count;
                                $topLostCats = $topLostCategories->filter(fn($cat) => $cat->lost_reports_count === $maxLostCount);
                            @endphp
                            <li>
                                <strong>Most Lost {{ $topLostCats->count() > 1 ? 'Categories' : 'Category' }}:</strong>
                                @if($topLostCats->count() === 1)
                                    {{ $topLostCats->first()->categoryName }} with {{ $maxLostCount }} reports
                                @else
                                    {{ $topLostCats->pluck('categoryName')->join(', ', ' and ') }} (tied with {{ $maxLostCount }} reports each)
                                @endif
                            </li>
                        @endif
                        
                        @if($topFoundCategories->isNotEmpty())
                            @php
                                $maxFoundCount = $topFoundCategories->first()->found_reports_count;
                                $topFoundCats = $topFoundCategories->filter(fn($cat) => $cat->found_reports_count === $maxFoundCount);
                            @endphp
                            <li>
                                <strong>Most Found {{ $topFoundCats->count() > 1 ? 'Categories' : 'Category' }}:</strong>
                                @if($topFoundCats->count() === 1)
                                    {{ $topFoundCats->first()->categoryName }} with {{ $maxFoundCount }} reports
                                @else
                                    {{ $topFoundCats->pluck('categoryName')->join(', ', ' and ') }} (tied with {{ $maxFoundCount }} reports each)
                                @endif
                            </li>
                        @endif
                        
                        @if($topLostLocations->isNotEmpty())
                            @php
                                $maxLostLocCount = $topLostLocations->first()->lost_reports_count;
                                $topLostLocs = $topLostLocations->filter(fn($loc) => $loc->lost_reports_count === $maxLostLocCount);
                            @endphp
                            <li>
                                <strong>Highest Loss {{ $topLostLocs->count() > 1 ? 'Hotspots' : 'Hotspot' }}:</strong>
                                @if($topLostLocs->count() === 1)
                                    {{ $topLostLocs->first()->locationName }} with {{ $maxLostLocCount }} lost items
                                @else
                                    {{ $topLostLocs->pluck('locationName')->join(', ', ' and ') }} (tied with {{ $maxLostLocCount }} lost items each)
                                @endif
                            </li>
                        @endif
                        
                        @if($topFoundLocations->isNotEmpty())
                            @php
                                $maxFoundLocCount = $topFoundLocations->first()->found_reports_count;
                                $topFoundLocs = $topFoundLocations->filter(fn($loc) => $loc->found_reports_count === $maxFoundLocCount);
                            @endphp
                            <li>
                                <strong>Highest Recovery {{ $topFoundLocs->count() > 1 ? 'Locations' : 'Location' }}:</strong>
                                @if($topFoundLocs->count() === 1)
                                    {{ $topFoundLocs->first()->locationName }} with {{ $maxFoundLocCount }} found items
                                @else
                                    {{ $topFoundLocs->pluck('locationName')->join(', ', ' and ') }} (tied with {{ $maxFoundLocCount }} found items each)
                                @endif
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="header-actions print-hide">
                <a href="{{ route('admin.dashboard.export-pdf') }}" class="export-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor">
                        <path d="M480-320 280-520l56-58 104 104v-326h80v326l104-104 56 58-200 200ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z"/>
                    </svg>
                    <span>Download PDF</span>
                </a>
            </div>

        </div>
    </div>
@endsection

<!-- FOOTER SECTION -->
@section('footer')
    
@endsection

<!-- PAGE SPECIFIC JS -->
@section('page-js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/admin_sidebar.js') }}"></script>

    <script>
        // Render Bar Chart Function
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
                        borderWidth: 2,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true,
                            ticks: { 
                                stepSize: 1,
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                display: false
                            }
                        }
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
@endsection
