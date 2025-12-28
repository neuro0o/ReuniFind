@extends('layouts.default')

@section('title', 'Handover Request Details')

@section('page-css')
  <link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/handover/show.css') }}">
@endsection

@section('content')
  <div class="layout">
    @include('layouts.partials.sidebar')

    <div class="content">
      @php
          $isSender = auth()->id() === $handover->senderID;
          $isRecipient = auth()->id() === $handover->recipientID;

          // Determine items and headers dynamically
          if ($isSender) {
              $ownItem = $handover->senderReport ?? null;
              $otherItem = $handover->report;
              $header = '';
          } elseif ($isRecipient) {
              $ownItem = $handover->report;
              $otherItem = $handover->senderReport ?? null;
              $header = '';
          }
      @endphp

      <h1 class="page-title">Handover Request Details</h1>

      {{-- Items Comparison Card --}}
      @if(isset($ownItem))
      <div class="card">
        <h2>{{ $header }}</h2>
        <div class="item-info">

          {{-- Own Item --}}
          <div class="item-block">
            <h3>
              @if($isSender)
                {{ $ownItem->reportType === 'Lost' ? 'Item you lost' : 'Item you found' }}
              @else
                {{ $ownItem->reportType === 'Lost' ? 'Item you lost' : 'Item you found' }}
              @endif
            </h3>
            <img src="{{ $ownItem->itemImg ? asset('storage/' . $ownItem->itemImg) : asset('images/placeholder.png') }}" alt="Item image">
            <p><strong>Name:</strong> {{ $ownItem->itemName }}</p>
            <p><strong>Description:</strong> {{ $ownItem->itemDescription }}</p>
            <p><strong>Location:</strong> {{ $ownItem->location?->locationName ?? '-' }}</p>
            <p><strong>Type:</strong> {{ $ownItem->reportType }}</p>
          </div>

          {{-- Other User's Item --}}
          @if($otherItem)
          <div class="item-block">
            <h3>
              @if($isSender)
                  {{ $otherItem->reportType === 'Lost' ? 'Item they lost' : 'Item they found' }}
              @else
                  {{ $ownItem->reportType === 'Lost' ? 'They want to return' : 'They want to claim' }}
              @endif
            </h3>
            <img src="{{ $otherItem->itemImg ? asset('storage/' . $otherItem->itemImg) : asset('images/placeholder.png') }}" alt="Item image">
            <p><strong>Name:</strong> {{ $otherItem->itemName }}</p>
            <p><strong>Description:</strong> {{ $otherItem->itemDescription }}</p>
            <p><strong>Location:</strong> {{ $otherItem->location?->locationName ?? '-' }}</p>
            <p><strong>Type:</strong> {{ $otherItem->reportType }}</p>
          </div>
          @endif

        </div>
      </div>
      @endif

      {{-- Handover Details --}}
      <div class="card">
        <h2>Handover Details</h2>
        <p><strong>Handover Type:</strong> {{ $handover->requestType }}</p>
        <p><strong>Status:</strong> <span class="status {{ strtolower($handover->requestStatus) }}">{{ $handover->requestStatus }}</span></p>
        <p><strong>Initiated by:</strong> {{ $handover->sender->userName }}</p>
        <p><strong>Recipient:</strong> {{ $handover->recipient->userName }}</p>
        <p><strong>Sent on:</strong> {{ $handover->created_at->format('d M Y, h:i A') }}</p>

        <hr>

        <p><strong>Proof Text:</strong></p>
        <p class="proof-text">{{ $handover->proofText ?? '—' }}</p>

        @if($handover->proofImg)
        <div class="proof-image">
          <img src="{{ asset('storage/' . $handover->proofImg) }}" alt="Proof image">
        </div>
        @endif

        @if($handover->rejectionNote)
        <div class="rejection-box">
          <h4>Rejection Reason</h4>
          <p>{{ $handover->rejectionNote }}</p>
        </div>
        @endif
      </div>

      {{-- Actions (Bottom) --}}
      <div class="card action-card">
        @if($isRecipient && $handover->requestStatus === 'Pending')
          <div class="action-buttons">
            <form action="{{ route('handover.update', $handover->requestID) }}" method="POST" class="inline-form">
              @csrf
              @method('PATCH')
              <input type="hidden" name="handoverStatus" value="Approved">
              <button type="submit" class="btn-accept">Accept Request</button>
            </form>

            <button type="button" class="btn-reject-toggle" id="showRejectForm">Reject</button>
          </div>

          {{-- Rejection Form (Hidden Initially) --}}
          <form action="{{ route('handover.update', $handover->requestID) }}" method="POST" class="reject-form" id="rejectForm" style="display:none;">
            @csrf
            @method('PATCH')
            <input type="hidden" name="handoverStatus" value="Rejected">
            <div class="reject-input-group">
              <label for="rejectionNote">Why are you rejecting this request?</label>
              <textarea name="rejectionNote" id="rejectionNote" placeholder="Please provide a reason for rejection..." required></textarea>
              <div class="reject-actions">
                <button type="submit" class="btn-reject-submit">Submit Rejection</button>
                <button type="button" class="btn-cancel" id="cancelReject">Cancel</button>
              </div>
            </div>
          </form>
        @endif

        {{-- Chat Button for Approved/Completed Requests --}}
        @if($handover->requestStatus === 'Approved' || $handover->requestStatus === 'Completed')
          <div class="chat-button-wrapper">
            <a href="{{ route('handover.chat.show', $handover->requestID) }}" class="btn-chat-action">
              💬 Open Chat
            </a>
          </div>
        @endif

        {{-- Back Button (Always at Bottom) --}}
        <div class="back-button-wrapper">
          <a href="{{ route('handover.index') }}" class="btn-back">Back to Handovers</a>
        </div>
      </div>

    </div>
  </div>
@endsection

@section('page-js')
  <script src="{{ asset('js/sidebar.js') }}"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const showRejectBtn = document.getElementById('showRejectForm');
      const rejectForm = document.getElementById('rejectForm');
      const cancelRejectBtn = document.getElementById('cancelReject');
      const actionButtons = document.querySelector('.action-buttons');

      if (showRejectBtn && rejectForm) {
        // Show rejection form
        showRejectBtn.addEventListener('click', function() {
          actionButtons.style.display = 'none';
          rejectForm.style.display = 'block';
          document.getElementById('rejectionNote').focus();
        });

        // Cancel rejection
        if (cancelRejectBtn) {
          cancelRejectBtn.addEventListener('click', function() {
            rejectForm.style.display = 'none';
            actionButtons.style.display = 'flex';
            document.getElementById('rejectionNote').value = '';
          });
        }
      }
    });
  </script>
@endsection
