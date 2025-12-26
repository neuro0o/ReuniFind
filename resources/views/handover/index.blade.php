@extends('layouts.default')

@section('title', 'My Handovers')

@section('page-css')
<link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
<link rel="stylesheet" href="{{ asset('css/handover/index.css') }}">
@endsection

@section('content')
<div class="layout">
    @include('layouts.partials.sidebar')

    <div class="main-content">
        <h1>My Handovers</h1>

        <!-- FILTER TABS -->
        <div class="filter-bar">
            @php $statuses = ['Sent', 'Received']; @endphp
            @foreach($statuses as $tabStatus)
                <a href="{{ route('handover.index', ['status' => strtolower($tabStatus)]) }}"
                    class="btn tab-btn {{ isset($status) && strtolower($status) === strtolower($tabStatus) ? 'active' : (!isset($status) && strtolower($tabStatus) === 'sent' ? 'active' : '') }}">
                    {{ $tabStatus }}
                </a>
            @endforeach
        </div>

        @if($handovers->isEmpty())
            <p class="empty-text">No handover requests found.</p>
        @else
            <div class="handover-grid">
                @foreach($handovers as $handover)
                    @php
                        $isSender = auth()->id() === $handover->senderID;
                        $isRecipient = auth()->id() === $handover->recipientID;
                    @endphp

                    <div class="handover-card">
                        <!-- Image -->
                        <div class="handover-image">
                            <img src="{{ $handover->report->itemImg ? asset('storage/' . $handover->report->itemImg) : asset('images/placeholder.png') }}" alt="{{ $handover->report->itemName }}">
                        </div>

                        <!-- Details -->
                        <div class="handover-details">
                            <h3>{{ $handover->report->itemName }}</h3>
                            <p>
                                <strong>Request Type:</strong> {{ $handover->requestType }}<br>
                                <strong>Status:</strong> <span class="status {{ strtolower($handover->requestStatus) }}">{{ $handover->requestStatus }}</span><br>
                                @if($isSender)
                                    <strong>To:</strong> {{ $handover->recipient->userName }}<br>
                                    <strong>Sent at:</strong> {{ \Carbon\Carbon::parse($handover->created_at)->format('d M Y, h:i A') }}<br>
                                @endif
                                @if($isRecipient)
                                    <strong>From:</strong> {{ $handover->sender->userName }}<br>
                                    <strong>Received at:</strong> {{ \Carbon\Carbon::parse($handover->created_at)->format('d/m/Y') }}<br>
                                @endif
                            </p>

                            @if($handover->rejectionNote)
                                <div class="rejection-box">
                                    <h4>Rejection Note</h4>
                                    <p>{{ $handover->rejectionNote }}</p>
                                </div>
                            @endif

                            <!-- Actions -->
                            <div class="btn-group">
                                <a href="{{ route('handover.show', $handover->requestID) }}" class="btn view">View</a>

                                @if($handover->requestStatus === 'Approved' || $handover->requestStatus === 'Completed')
                                    <a href="{{ route('handover.chat.show', $handover->requestID) }}" class="btn chat">Chat</a>
                                @endif

                                @if($isRecipient && $handover->requestStatus === 'Pending')
                                    <form action="{{ route('handover.update', $handover->requestID) }}" method="POST" class="inline-form">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="handoverStatus" value="Approved">
                                        <button type="submit" class="btn accept">Accept</button>
                                    </form>

                                    <!-- Reject Form -->
                                    <form action="{{ route('handover.update', $handover->requestID) }}" method="POST" class="inline-form reject-form">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="handoverStatus" value="Rejected">
                                        <div class="reject-input-container" style="display:none;">
                                            <input type="text" name="rejectionNote" placeholder="Enter rejection reason">
                                            <button type="submit" class="btn reject submit-btn">Submit</button>
                                        </div>
                                        <button type="button" class="btn reject reject-toggle">Reject</button>
                                    </form>
                                @endif

                                @if($isSender && $handover->requestStatus === 'Pending')
                                    <form action="{{ route('handover.cancel', $handover->requestID) }}" method="POST" class="inline-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn cancel">Cancel Request</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@section('page-js')
<script src="{{ asset('js/sidebar.js') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".reject-toggle").forEach(btn => {
        btn.addEventListener("click", function() {
            const form = btn.closest('.reject-form');
            const container = form.querySelector('.reject-input-container');

            container.style.display = 'block';      // show input & submit
            container.querySelector('input').focus(); // autofocus
            btn.style.display = 'none';              // hide original reject btn
        });
    });
});
</script>
@endsection