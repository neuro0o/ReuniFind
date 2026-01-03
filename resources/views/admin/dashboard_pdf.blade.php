<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReuniFind Analytics Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Quicksand', 'Segoe UI', sans-serif;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 12px;
            border-bottom: 3px solid #3A5987;
        }

        .header h1 {
            color: #3A5987;
            font-size: 28px;
            margin-bottom: 8px;
        }
        
        .logo-circle {
            width: 70px;
            height: 70px;
            /* background: #3A5987; */
            border-radius: 50%;
            margin: 0 auto 8px;
            position: relative;
        }
        
        .title {
            color: #3A5987;
            font-size: 20px;
            font-weight: bold;
            margin: 5px 0;
        }
        
        .subtitle {
            color: #666;
            font-size: 10px;
            margin: 3px 0;
        }

        .date {
            color: #666;
            font-size: 12px;
            margin-top: 5px;
        }

        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .section-title {
            color: #3A5987;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #3A5987;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-box {
            background: #f8f9fa;
            border-left: 4px solid #3A5987;
            padding: 15px;
            border-radius: 5px;
        }

        .stat-box.lost {
            border-left-color: #dc3545;
        }

        .stat-box.found {
            border-left-color: #28a745;
        }

        .stat-box.pending {
            border-left-color: #ffc107;
        }

        .stat-box.published {
            border-left-color: #17a2b8;
        }

        .stat-box.completed {
            border-left-color: #28a745;
        }

        .stat-box.unresolved {
            border-left-color: #dc3545;
        }

        .stat-box h3 {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .stat-box .number {
            font-size: 32px;
            font-weight: bold;
            color: #3A5987;
        }

        .stat-box .description {
            font-size: 11px;
            color: #888;
            margin-top: 5px;
            font-style: italic;
        }

        .table-container {
            margin: 20px 0;
            page-break-inside: avoid;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th {
            background-color: #3A5987;
            color: white;
            padding: 12px;
            text-align: center;
            font-size: 13px;
        }

        table td {
            padding: 10px 12px;
            border-bottom: 1px solid #ddd;
            font-size: 12px;
            text-align: center;
        }

        table tr:hover {
            background-color: #f8f9fa;
        }

        .insights-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .insights-box h3 {
            color: #856404;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .insights-box ul {
            list-style: none;
            padding: 0;
        }

        .insights-box li {
            padding: 8px 0;
            border-bottom: 1px solid rgba(133, 100, 4, 0.1);
            color: #856404;
            font-size: 13px;
        }

        .insights-box li:last-child {
            border-bottom: none;
        }

        .insights-box strong {
            color: #664d03;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #3A5987;
            text-align: center;
            font-size: 11px;
            color: #666;
        }

        .page-break {
            page-break-after: always;
        }

        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="header">
      <div class="logo-circle">
          <img src="{{ public_path('images/ReuniFind_Logo.png') }}" alt="ReuniFind" style="width: 100%; height: 100%; object-fit: contain;">
      </div>
      <div class="title">ReuniFind Analytics Report</div>
      <div class="date">Generated on {{ now()->format('F d, Y \a\t h:i A') }}</div>          
    </div>

    <!-- SECTION 1: OVERALL SYSTEM SUMMARY -->
    <div class="section">
        <div class="section-title">Overall System Summary</div>
        <div class="stats-grid">
            <div class="stat-box">
                <h3>Total Registered Users</h3>
                <div class="number">{{ $totalUsers }}</div>
            </div>
            <div class="stat-box lost">
                <h3>Total Lost Item Reports</h3>
                <div class="number">{{ $totalLostReports }}</div>
            </div>
            <div class="stat-box found">
                <h3>Total Found Item Reports</h3>
                <div class="number">{{ $totalFoundReports }}</div>
            </div>
        </div>
    </div>

    <!-- SECTION 2: REPORT STATUS OVERVIEW -->
    <div class="section">
        <div class="section-title">Report Status Overview</div>
        <div class="stats-grid">
            <div class="stat-box pending">
                <h3>Pending Lost Reports</h3>
                <div class="number">{{ $pendingLostReports }}</div>
                <div class="description">Awaiting admin review</div>
            </div>
            <div class="stat-box pending">
                <h3>Pending Found Reports</h3>
                <div class="number">{{ $pendingFoundReports }}</div>
                <div class="description">Awaiting admin review</div>
            </div>
            <div class="stat-box published">
                <h3>Published Lost Reports</h3>
                <div class="number">{{ $publishedLostReports }}</div>
                <div class="description">Waiting & ready for handover process</div>
            </div>
            <div class="stat-box published">
                <h3>Published Found Reports</h3>
                <div class="number">{{ $publishedFoundReports }}</div>
                <div class="description">Waiting & ready for handover process</div>
            </div>
            <div class="stat-box unresolved">
                <h3>Unresolved Cases</h3>
                <div class="number">{{ $unresolvedCases }}</div>
                <div class="description">Waiting for handover completion</div>
            </div>
            <div class="stat-box">
                <h3>Completed Cases</h3>
                <div class="number">{{ $completedCases }}</div>
                <div class="description">Items successfully reunited</div>
            </div>
            <br>
            <div class="stat-box">
                <h3>Handover Success Rate</h3>
                <div class="number">{{ $successRate }}%</div>
                <div class="description">{{ $completedCases }} successful handovers out of {{ $completedCases + \App\Models\HandoverRequest::whereIn('requestStatus', ['Pending', 'Approved', 'Rejected'])->count() }} total attempts</div>
            </div>
        </div>
    </div>

    <!-- SECTION 3: MONTHLY STATISTICS -->
    <div class="section">
        <div class="section-title">Monthly Report - {{ now()->format('F Y') }}</div>
        <div class="stats-grid">
            <div class="stat-box">
                <h3>Total Reports This Month</h3>
                <div class="number">{{ $totalReportsThisMonth }}</div>
            </div>
            <div class="stat-box lost">
                <h3>Lost Reports This Month</h3>
                <div class="number">{{ $lostReportsThisMonth }}</div>
            </div>
            <div class="stat-box found">
                <h3>Found Reports This Month</h3>
                <div class="number">{{ $foundReportsThisMonth }}</div>
            </div>
            <div class="stat-box unresolved">
                <h3>Unresolved This Month</h3>
                <div class="number">{{ $unresolvedCasesThisMonth }}</div>
            </div>
            <div class="stat-box completed">
                <h3>Completed This Month</h3>
                <div class="number">{{ $completedCasesThisMonth }}</div>
            </div>
            <br>
            <div class="stat-box">
                <h3>Monthly Success Rate</h3>
                <div class="number">{{ $monthlySuccessRate }}%</div>
                <div class="description">Handover completion rate</div>
            </div>
        </div>
    </div>

    <!-- SECTION 4: CATEGORY ANALYSIS -->
    <div class="section">
        <div class="section-title">Category & Location Analysis</div>
        
        <div class="two-column">
            <div class="table-container">
                <h4 style="color: #3A5987; margin-bottom: 10px;">Top Lost Item Categories</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Category</th>
                            <th>Reports</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topLostCategories as $index => $category)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $category->categoryName }}</td>
                            <td><strong>{{ $category->lost_reports_count }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="table-container">
                <h4 style="color: #3A5987; margin-bottom: 10px;">Top Found Item Categories</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Category</th>
                            <th>Reports</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topFoundCategories as $index => $category)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $category->categoryName }}</td>
                            <td><strong>{{ $category->found_reports_count }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="two-column" style="margin-top: 20px;">
            <div class="table-container">
                <h4 style="color: #3A5987; margin-bottom: 10px;">Hotspot Locations - Lost Items</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Location</th>
                            <th>Reports</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topLostLocations as $index => $location)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $location->locationName }}</td>
                            <td><strong>{{ $location->lost_reports_count }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="table-container">
                <h4 style="color: #3A5987; margin-bottom: 10px;">Hotspot Locations - Found Items</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Location</th>
                            <th>Reports</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topFoundLocations as $index => $location)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $location->locationName }}</td>
                            <td><strong>{{ $location->found_reports_count }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- KEY INSIGHTS -->
    <div class="insights-box">
        <h3>Key Insights & Recommendations</h3>
        <ul>
            @if($topLostCategories->isNotEmpty())
                @php
                    $maxLostCount = $topLostCategories->first()->lost_reports_count;
                    $topLostCats = $topLostCategories->filter(fn($cat) => $cat->lost_reports_count === $maxLostCount);
                @endphp
                <li>
                    <strong>Most Lost {{ $topLostCats->count() > 1 ? 'Categories' : 'Category' }}:</strong>
                    @if($topLostCats->count() === 1)
                        {{ $topLostCats->first()->categoryName }} with {{ $maxLostCount }} reports - Consider awareness campaigns for this category.
                    @else
                        {{ $topLostCats->pluck('categoryName')->join(', ', ' and ') }} (tied with {{ $maxLostCount }} reports each) - Consider awareness campaigns for these categories.
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
                        {{ $topFoundCats->first()->categoryName }} with {{ $maxFoundCount }} reports - High found number indicates good reporting behavior.
                    @else
                        {{ $topFoundCats->pluck('categoryName')->join(', ', ' and ') }} (tied with {{ $maxFoundCount }} reports each) - High found numbers indicate good reporting behavior.
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
                        {{ $topLostLocs->first()->locationName }} with {{ $maxLostLocCount }} lost items - Consider installing signage reminder or lost-and-found boxes at this location.
                    @else
                        {{ $topLostLocs->pluck('locationName')->join(', ', ' and ') }} (tied with {{ $maxLostLocCount }} lost items each) - Consider installing signage reminder or lost-and-found boxes at these locations.
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
                        {{ $topFoundLocs->first()->locationName }} with {{ $maxFoundLocCount }} found items - Continue monitoring this high-traffic area.
                    @else
                        {{ $topFoundLocs->pluck('locationName')->join(', ', ' and ') }} (tied with {{ $maxFoundLocCount }} found items each) - Continue monitoring these high-traffic areas.
                    @endif
                </li>
            @endif
            
            @if($successRate >= 70)
                <li><strong>Performance:</strong> System showing excellent success rate of {{ $successRate }}% - Current processes are effective.</li>
            @elseif($successRate >= 50)
                <li><strong>Performance:</strong> Moderate success rate of {{ $successRate }}% - Consider improving matchmaking algorithm or user engagement.</li>
            @else
                <li><strong>Performance:</strong> Success rate of {{ $successRate }}% indicates room for improvement. Review matchmaking algorithm and increase user's awareness using educational forum post.</li>
            @endif
        </ul>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p><strong>ReuniFind: Web-Based UMS Lost & Found System</strong></p>
        <p>This report is automatically generated and contains confidential information.</p>
        <!-- <p>© {{ now()->format('Y') }} ReuniFind. All rights reserved.</p> -->
    </div>
</body>
</html>