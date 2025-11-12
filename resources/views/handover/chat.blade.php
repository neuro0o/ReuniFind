@extends('layouts.default')

@section('title', 'Handover Chat')

@section('page-css')
  <link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/handover/chat.css') }}">
@endsection

@section('content')
<div class="layout">
  @include('layouts.partials.sidebar')

  <div class="content">
    <h1 class="page-title">Handover Chat</h1>

    <div class="chat-header">
      <h3>{{ $handover->report->itemName }}</h3>
      <p>{{ $handover->handoverType }} Request between 
        <strong>{{ $handover->sender->userName }}</strong> and 
        <strong>{{ $handover->recipient->userName }}</strong>
      </p>
    </div>

    <div class="chat-box" id="chatBox">
      @foreach($messages as $msg)
        <div class="message {{ $msg->senderID === auth()->id() ? 'sent' : 'received' }}">
          <div class="msg-content">
            @if($msg->message)
              <p>{{ $msg->message }}</p>
            @endif

            @if($msg->messageImage)
              <div class="msg-image">
                <img src="{{ asset('storage/' . $msg->messageImage) }}" alt="Attached image">
              </div>
            @endif

            <span class="msg-time">{{ $msg->created_at->format('H:i') }}</span>
          </div>
        </div>
      @endforeach
    </div>

    <form action="{{ route('handover.chat.store', $handover->handoverID) }}" method="POST" enctype="multipart/form-data" class="chat-input">
      @csrf
      <input type="text" name="message" placeholder="Type your message..." class="text-input" autocomplete="off">
      <label for="imageInput" class="image-label">📎</label>
      <input type="file" id="imageInput" name="messageImage" accept="image/*" style="display:none;">
      <button type="submit" class="send-btn">Send</button>
    </form>

    <a href="{{ route('handover.show', $handover->handoverID) }}" class="btn-back">← Back to Request</a>
  </div>
</div>
@endsection

@section('page-js')
<script src="{{ asset('js/sidebar.js') }}"></script>
<script>
  // Auto-scroll chat box
  const chatBox = document.getElementById('chatBox');
  chatBox.scrollTop = chatBox.scrollHeight;
</script>
@endsection
