@extends('layouts.default')

@section('title', 'Suggested Matches')

@section('page-css')
<link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
<link rel="stylesheet" href="{{ asset('css/LostFoundReport/item_report_matches.css') }}">
<link rel="stylesheet" href="{{ asset('css/utils/modal.css') }}">
<link rel="stylesheet" href="{{ asset('css/LostFoundReport/item_modal.css') }}">
@endsection

@section('content')
<div class="layout">
    @include('layouts.partials.sidebar')

    <div class="main-content">
        <div class="page-header">
            <h1>Suggested Matches</h1>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-container">
            <div class="stat-card suggested">
                <div class="stat-icon"></div>
                <div class="stat-info">
                    <div class="stat-label">Suggested</div>
                    <div class="stat-value">{{ $matchesByStatus['suggested']->count() }}</div>
                </div>
            </div>
            <div class="stat-card pending">
                <div class="stat-icon"></div>
                <div class="stat-info">
                    <div class="stat-label">Pending</div>
                    <div class="stat-value">{{ $matchesByStatus['pending']->count() }}</div>
                </div>
            </div>
            <div class="stat-card accepted">
                <div class="stat-icon"></div>
                <div class="stat-info">
                    <div class="stat-label">Accepted</div>
                    <div class="stat-value">{{ $matchesByStatus['accepted']->count() }}</div>
                </div>
            </div>
            <div class="stat-card rejected">
                <div class="stat-icon"></div>
                <div class="stat-info">
                    <div class="stat-label">Rejected</div>
                    <div class="stat-value">{{ $matchesByStatus['rejected']->count() }}</div>
                </div>
            </div>
            <div class="stat-card dismissed">
                <div class="stat-icon"></div>
                <div class="stat-info">
                    <div class="stat-label">Dismissed</div>
                    <div class="stat-value">{{ $matchesByStatus['dismissed']->count() }}</div>
                </div>
            </div>
            <div class="stat-card completed">
                <div class="stat-icon"></div>
                <div class="stat-info">
                    <div class="stat-label">Completed</div>
                    <div class="stat-value">{{ $matchesByStatus['completed']->count() }}</div>
                </div>
            </div>
        </div>

        <!-- FILTERS CARD -->
        <div class="filters-card">
            <form method="GET" action="{{ route('item_report.suggested_matches') }}" class="filters-form">
                <div class="filter-group">
                    <label for="match_status">Filter by Status</label>
                    <select name="match_status" id="match_status" class="filter-select">
                        <option value="suggested" {{ request('match_status', 'suggested') == 'suggested' ? 'selected' : '' }}>
                            Suggested Matches
                        </option>
                        <option value="pending" {{ request('match_status') == 'pending' ? 'selected' : '' }}>
                            Pending Matches
                        </option>
                        <option value="accepted" {{ request('match_status') == 'accepted' ? 'selected' : '' }}>
                            Accepted Matches
                        </option>
                        <option value="rejected" {{ request('match_status') == 'rejected' ? 'selected' : '' }}>
                            Rejected Matches
                        </option>
                        <option value="dismissed" {{ request('match_status') == 'dismissed' ? 'selected' : '' }}>
                            Dismissed Matches
                        </option>
                        <option value="completed" {{ request('match_status') == 'completed' ? 'selected' : '' }}>
                            Completed Matches
                        </option>
                    </select>
                </div>

                <button type="submit" class="btn-filter">Apply Filter</button>
                <a href="{{ route('item_report.suggested_matches') }}" class="btn-reset">Reset</a>
            </form>
        </div>

        @php
            $currentStatus = request('match_status', 'suggested');
            $matches = $matchesByStatus[$currentStatus];
            $statusLabels = [
                'suggested' => 'Suggested on',
                'pending' => 'Handover requested on',
                'accepted' => 'Handover accepted on',
                'rejected' => 'Rejected on',
                'dismissed' => 'Dismissed on',
                'completed' => 'Completed on'
            ];
        @endphp

        <!-- MATCHES DISPLAY -->
        @if($matches->isEmpty())
            <div class="empty-state">
                <div class="empty-icon"></div>
                <h3>No {{ ucfirst($currentStatus) }} Matches</h3>
                <p>You don't have any {{ $currentStatus }} matches at the moment</p>
            </div>
        @else
            <div class="matches-container">
                @foreach($matches as $match)
                <div class="match-card {{ $match->matchStatus }}">
                    <!-- Your Report -->
                    <div class="report">
                        <h5>Your Item Report</h5>
                        <div class="content">
                            @if($match->report->itemImg)
                                <img src="{{ asset('storage/' . $match->report->itemImg) }}" alt="Report Image">
                            @else
                                <div class="na-text">N/A</div>
                            @endif
                            <div class="text">
                                <p><strong>{{ $match->report->itemName }}</strong></p>
                                <p>{{ $match->report->itemDescription ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Potential Match -->
                    <div class="potential">
                        <h5>Potential Item Match</h5>
                        <div class="content">
                            @if($match->matchedReport->itemImg)
                                <img src="{{ asset('storage/' . $match->matchedReport->itemImg) }}" alt="Matched Image">
                            @else
                                <div class="na-text">N/A</div>
                            @endif
                            <div class="text">
                                <p><strong>{{ $match->matchedReport->itemName }}</strong></p>
                                <p>{{ $match->matchedReport->itemDescription ?? '-' }}</p>
                                <p id="by"><small>Reported By: {{ $match->matchedReport->user->userName }}</small></p>
                                <p id="type-date"><small>{{ $statusLabels[$match->matchStatus] ?? 'Verified on' }}: {{ $match->created_at->format('d M Y, h:i A') }}</small></p>

                                <!-- Actions -->
                                <div class="actions">
                                    @if($match->matchStatus === 'suggested')
                                        {{-- SUGGESTED: View Details + Claim/Return + Dismiss --}}
                                        <a href="javascript:void(0);" 
                                            class="btn view-details-btn"
                                            data-report-id="{{ $match->matchedReport->reportID }}"
                                            data-image="{{ $match->matchedReport->itemImg ? asset('storage/' . $match->matchedReport->itemImg) : 'N/A' }}"
                                            data-name="{{ $match->matchedReport->itemName }}"
                                            data-category="{{ $match->matchedReport->category?->categoryName ?? 'N/A' }}"
                                            data-status="{{ $match->matchedReport->reportType }}"
                                            data-location="{{ $match->matchedReport->location?->locationName ?? 'N/A' }}"
                                            data-date="{{ $match->matchedReport->reportDate->format('d M Y') }}"
                                            data-description="{{ $match->matchedReport->itemDescription ?? '-' }}">
                                            View More Details
                                        </a>
                                        <a href="javascript:void(0);" 
                                           class="btn handover-btn"
                                           data-recipient-report-id="{{ $match->matchedReport->reportID }}">
                                            {{ $match->report->reportType === 'Lost' ? 'Claim' : 'Return' }}
                                        </a>
                                        <form action="{{ route('item_report.dismiss_match', $match->suggestionID) }}" method="POST" style="display:inline;" onsubmit="return confirm('Dismiss this match? You can undo this later.')">
                                            @csrf
                                            <button type="submit" class="btn dismiss-btn">Dismiss</button>
                                        </form>

                                    @elseif($match->matchStatus === 'pending')
                                        {{-- PENDING: View Handover Request + Cancel --}}
                                        @php
                                            $pendingHandover = \App\Models\HandoverRequest::where(function($query) use ($match) {
                                                $query->where('reportID', $match->reportID)
                                                      ->where('senderReportID', $match->matchedReportID);
                                            })->orWhere(function($query) use ($match) {
                                                $query->where('reportID', $match->matchedReportID)
                                                      ->where('senderReportID', $match->reportID);
                                            })
                                            ->where('requestStatus', 'Pending')
                                            ->first();
                                        @endphp

                                        @if($pendingHandover)
                                            <a href="{{ route('handover.index') }}?status={{ $pendingHandover->senderID === auth()->id() ? 'sent' : 'received' }}" 
                                               class="btn pending-btn">
                                                View Handover Request
                                            </a>

                                            {{-- Only show cancel button if user is the sender --}}
                                            @if($pendingHandover->senderID === auth()->id())
                                                <form action="{{ route('handover.cancel', $pendingHandover->requestID) }}" method="POST" style="display:inline;" onsubmit="return confirm('Cancel this handover request? The match will return to Suggested status.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn cancel-request-btn">Cancel Request</button>
                                                </form>
                                            @endif
                                        @endif

                                    @elseif($match->matchStatus === 'accepted')
                                        {{-- ACCEPTED: Go to Chat --}}
                                        @php
                                            $acceptedHandover = \App\Models\HandoverRequest::where(function($query) use ($match) {
                                                $query->where('reportID', $match->reportID)
                                                      ->where('senderReportID', $match->matchedReportID);
                                            })->orWhere(function($query) use ($match) {
                                                $query->where('reportID', $match->matchedReportID)
                                                      ->where('senderReportID', $match->reportID);
                                            })
                                            ->whereIn('requestStatus', ['Approved', 'Completed'])
                                            ->first();
                                        @endphp

                                        @if($acceptedHandover)
                                            <a href="{{ route('handover.chat.show', $acceptedHandover->requestID) }}" 
                                               class="btn chat-btn">
                                                💬 Open Chat
                                            </a>
                                        @endif

                                    @elseif($match->matchStatus === 'rejected')
                                        {{-- REJECTED: View Rejection Reason --}}
                                        @php
                                            $rejectedHandover = \App\Models\HandoverRequest::where(function($query) use ($match) {
                                                $query->where('reportID', $match->reportID)
                                                      ->where('senderReportID', $match->matchedReportID);
                                            })->orWhere(function($query) use ($match) {
                                                $query->where('reportID', $match->matchedReportID)
                                                      ->where('senderReportID', $match->reportID);
                                            })
                                            ->where('requestStatus', 'Rejected')
                                            ->first();
                                        @endphp

                                        @if($rejectedHandover && $rejectedHandover->rejectionNote)
                                            <button type="button" 
                                                    class="btn rejection-reason-btn" 
                                                    onclick="alert('Rejection Reason:\n\n{{ addslashes($rejectedHandover->rejectionNote) }}')">
                                                View Rejection Reason
                                            </button>
                                        @else
                                            <span class="btn disabled-btn">No Reason Provided</span>
                                        @endif

                                    @elseif($match->matchStatus === 'completed')
                                        {{-- COMPLETED: View Handover Form --}}
                                        @php
                                            $completedHandover = \App\Models\HandoverRequest::where(function($query) use ($match) {
                                                $query->where('reportID', $match->reportID)
                                                      ->where('senderReportID', $match->matchedReportID);
                                            })->orWhere(function($query) use ($match) {
                                                $query->where('reportID', $match->matchedReportID)
                                                      ->where('senderReportID', $match->reportID);
                                            })
                                            ->where('requestStatus', 'Completed')
                                            ->whereNotNull('handoverForm')
                                            ->first();
                                        @endphp

                                        @if($completedHandover && $completedHandover->handoverForm)
                                            <a href="{{ route('handover.form.view', $completedHandover->requestID) }}" 
                                               class="btn view-form-btn" 
                                               target="_blank">
                                                📄 View Handover Form
                                            </a>
                                        @else
                                            <span class="btn disabled-btn">No Form Available</span>
                                        @endif

                                    @elseif($match->matchStatus === 'dismissed')
                                        {{-- DISMISSED: View Details + Undo --}}
                                        <a href="javascript:void(0);" 
                                            class="btn view-details-btn"
                                            data-report-id="{{ $match->matchedReport->reportID }}"
                                            data-image="{{ $match->matchedReport->itemImg ? asset('storage/' . $match->matchedReport->itemImg) : 'N/A' }}"
                                            data-name="{{ $match->matchedReport->itemName }}"
                                            data-category="{{ $match->matchedReport->category?->categoryName ?? 'N/A' }}"
                                            data-status="{{ $match->matchedReport->reportType }}"
                                            data-location="{{ $match->matchedReport->location?->locationName ?? 'N/A' }}"
                                            data-date="{{ $match->matchedReport->reportDate->format('d M Y') }}"
                                            data-description="{{ $match->matchedReport->itemDescription ?? '-' }}">
                                            View More Details
                                        </a>
                                        <form action="{{ route('item_report.undo_dismiss', $match->suggestionID) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn undo-btn">Undo Dismiss</button>
                                        </form>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif

        @include('item_report.partials.item_modal')
        @include('handover.partials.handover_modal')
    </div>
</div>
@endsection

@section('page-js')
<script src="{{ asset('js/sidebar.js') }}"></script>
<script src="{{ asset('js/item_modal.js') }}"></script>
<script src="{{ asset('js/handover_modal.js') }}"></script>
@endsection