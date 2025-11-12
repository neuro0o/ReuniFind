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
            $ownItem = $handover->senderReport ?? null;      // Sender's offered item
            $otherItem = $handover->report;                 // Recipient's item
            $header = '';
        } elseif ($isRecipient) {
            $ownItem = $handover->report;                   // Recipient's item
            $otherItem = $handover->senderReport ?? null;   // Sender's offered item
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
                {{-- Sender sees the other item as "item they lost / item they found" --}}
                {{ $otherItem->reportType === 'Lost' ? 'Item they lost' : 'Item they found' }}
            @else
                {{-- Recipient sees the other item from the perspective of what the sender want from you --}}
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

    {{-- Actions for Recipient --}}
    @if($isRecipient)
    <div class="card action-card">
      @if($handover->requestStatus === 'Pending')
      <form action="{{ route('handover.update', $handover->requestID) }}" method="POST" class="inline-form">
        @csrf
        @method('PUT')
        <input type="hidden" name="handoverStatus" value="Approved">
        <button type="submit" class="btn-accept">Accept Request</button>
      </form>

      <form action="{{ route('handover.update', $handover->requestID) }}" method="POST" class="inline-form">
        @csrf
        @method('PUT')
        <input type="hidden" name="handoverStatus" value="Rejected">
        <input type="text" name="rejectionNote" placeholder="Reason (optional)" class="reject-note">
        <button type="submit" class="btn-reject">Reject</button>
      </form>
      @endif

      <a href="{{ route('handover.index') }}" class="btn-back">Back to List</a>
    </div>
    @endif

    {{-- Back Button for Sender Only --}}
    @if($isSender)
    <div class="card action-card">
      <a href="{{ route('handover.index') }}" class="btn-back">Back to List</a>
    </div>
    @endif

  </div>
</div>
@endsection

@section('page-js')
<script src="{{ asset('js/sidebar.js') }}"></script>
@endsection
