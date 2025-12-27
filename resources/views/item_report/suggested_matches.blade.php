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

    <div class="content">
        <div class="page-title">
            <h1>Suggested Matches</h1>
        </div>
        <br>

        <!-- Tabs -->
        <div class="filter-tabs">
            @php
                $tabStatuses = ['suggested','pending','accepted','rejected','dismissed','completed'];
                $tabLabels = [
                    'suggested' => 'Suggested Matches',
                    'pending' => 'Pending Matches',
                    'accepted' => 'Accepted Matches',
                    'rejected' => 'Rejected Matches',
                    'dismissed' => 'Dismissed Matches',
                    'completed' => 'Completed Matches'
                ];
            @endphp

            @foreach($tabStatuses as $status)
                <button class="tab-btn" data-tab="{{ $status }}">{{ $tabLabels[$status] }}</button>
            @endforeach
        </div>

        <!-- Tab Contents -->
        <div class="tab-contents">
            @foreach($tabStatuses as $status)
                <div class="tab-content" id="{{ $status }}">
                    @php
                        $matches = $matchesByStatus[$status];
                        $statusLabels = [
                            'suggested' => 'Suggested on',
                            'pending' => 'Handover requested on',
                            'accepted' => 'Handover accepted on',
                            'rejected' => 'Rejected on',
                            'dismissed' => 'Dismissed on',
                            'completed' => 'Completed on'
                        ];
                    @endphp

                    @if($matches->isEmpty())
                        <p class="empty-text">No {{ $tabLabels[$status] }} found.</p>
                    @else
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
                                            @if($match->matchStatus === 'completed')
                                                {{-- View Handover Form Button --}}
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

                                            @elseif($match->matchStatus === 'rejected')
                                                {{-- View Rejection Reason Button ONLY --}}
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

                                            @else
                                                {{-- View More Details for other statuses --}}
                                                <a href="javascript:void(0);" 
                                                    class="btn view-details-btn {{ in_array($match->matchStatus, ['pending','accepted','dismissed']) ? 'no-action-btn' : '' }}"
                                                    data-report-id="{{ $match->matchedReport->reportID }}"
                                                    data-image="{{ $match->matchedReport->itemImg ? asset('storage/' . $match->matchedReport->itemImg) : 'N/A' }}"
                                                    data-name="{{ $match->matchedReport->itemName }}"
                                                    data-category="{{ $match->matchedReport->itemCategory }}"
                                                    data-status="{{ $match->matchedReport->reportType }}"
                                                    data-location="{{ $match->matchedReport->itemLocation }}"
                                                    data-date="{{ $match->matchedReport->reportDate->format('d M Y') }}"
                                                    data-description="{{ $match->matchedReport->itemDescription ?? '-' }}">
                                                    View More Details
                                                </a>
                                            @endif

                                            {{-- Dismiss/Undo Buttons --}}
                                            @if($match->matchStatus === 'suggested')
                                                <form action="{{ route('item_report.dismiss_match', $match->suggestionID) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn dismiss-btn" onclick="return confirm('Dismiss this match?')">Dismiss</button>
                                                </form>
                                            @elseif($match->matchStatus === 'dismissed')
                                                <form action="{{ route('item_report.undo_dismiss', $match->suggestionID) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn dismiss-btn">Undo Dismiss</button>
                                                </form>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            @endforeach
        </div>

        @include('item_report.partials.item_modal')
        @include('handover.partials.handover_modal')
    </div>
</div>
@endsection

@section('page-js')
<script src="{{ asset('js/sidebar.js') }}"></script>
<script src="{{ asset('js/item_modal.js') }}"></script>
<script src="{{ asset('js/handover_modal.js') }}"></script>

<script>
const tabs = document.querySelectorAll('.tab-btn');
const contents = document.querySelectorAll('.tab-content');

function activateTab(tabName) {
    tabs.forEach(t => t.classList.remove('active'));
    contents.forEach(c => c.classList.remove('active'));
    const targetBtn = document.querySelector(`.tab-btn[data-tab="${tabName}"]`);
    const targetContent = document.getElementById(tabName);
    if(targetBtn && targetContent){
        targetBtn.classList.add('active');
        targetContent.classList.add('active');
    }
}

// Activate tab from URL query param or default to suggested
const urlParams = new URLSearchParams(window.location.search);
const activeTab = urlParams.get('tab') || 'suggested';
activateTab(activeTab);

tabs.forEach(tab => {
    tab.addEventListener('click', () => {
        activateTab(tab.getAttribute('data-tab'));
    });
});
</script>
@endsection