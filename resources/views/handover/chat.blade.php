@extends('layouts.default')

@section('title', 'Chat - ' . $handover->report->itemName)

@section('page-css')
<link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
<link rel="stylesheet" href="{{ asset('css/handover/chat.css') }}">
@endsection

@section('content')
<div class="layout">
    @include('layouts.partials.sidebar')

    <div class="main-content">
        <!-- Chat Header -->
        <div class="chat-header">
            <a href="{{ route('handover.chat.index') }}" class="back-btn">
                <img src="{{ asset('images/icons/Arrow Down.svg') }}" alt="Back" style="transform: rotate(90deg);">
            </a>
            <div class="chat-user-info">
                <img src="{{ $otherUser->profileImg ? asset('storage/' . $otherUser->profileImg) : asset('images/profiles/user_default.png') }}" 
                     alt="{{ $otherUser->userName }}" 
                     class="user-avatar">
                <div>
                    <h2>{{ $otherUser->userName }}</h2>
                    <p class="item-name">{{ $handover->report->itemName }}</p>
                </div>
            </div>
            <div class="handover-status">
                <span class="status-badge {{ strtolower($handover->requestStatus) }}">
                    {{ $handover->requestStatus }}
                </span>
            </div>
        </div>

        <!-- Messages Container -->
        <div class="messages-container" id="messagesContainer">
            @forelse($messages as $message)
                <div class="message {{ $message->senderID === auth()->id() ? 'own' : 'other' }}">
                    <div class="message-avatar">
                        <img src="{{ $message->sender->profileImg ? asset('storage/' . $message->sender->profileImg) : asset('images/profiles/user_default.png') }}" 
                             alt="{{ $message->sender->userName }}">
                    </div>
                    <div class="message-content">
                        <div class="message-header">
                            <span class="sender-name">{{ $message->sender->userName }}</span>
                            <span class="message-time">{{ $message->created_at->format('h:i A') }}</span>
                        </div>
                        
                        @if($message->messageText)
                            <div class="message-text">{{ $message->messageText }}</div>
                        @endif
                        
                        @if($message->messageImg)
                            <div class="message-image">
                                <img src="{{ asset('storage/' . $message->messageImg) }}" 
                                     alt="Shared image"
                                     onclick="openImageModal(this.src)">
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="no-messages">
                    <p>No messages yet. Start the conversation!</p>
                </div>
            @endforelse
        </div>

        <!-- Message Input Form -->
        <div class="message-input-container">
            <form action="{{ route('handover.chat.store', $handover->requestID) }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  id="messageForm">
                @csrf
                
                <div class="input-wrapper">
                    <!-- Image Preview -->
                    <div id="imagePreview" style="display: none;">
                        <img id="previewImg" src="" alt="Preview">
                        <button type="button" onclick="removeImage()" class="remove-preview">&times;</button>
                    </div>

                    <!-- File Input (Hidden) -->
                    <input type="file" 
                           name="messageImg" 
                           id="messageImg" 
                           accept="image/*"
                           style="display: none;"
                           onchange="previewImage(this)">
                    
                    <!-- Image Upload Button -->
                    <button type="button" class="attach-btn" onclick="document.getElementById('messageImg').click()">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/>
                        </svg>
                    </button>

                    <!-- Text Input -->
                    <textarea name="messageText" 
                              id="messageText"
                              placeholder="Type a message..."
                              rows="1"
                              maxlength="1000"></textarea>
                    
                    <!-- Send Button -->
                    <button type="submit" class="send-btn">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Mark as Completed Section -->
        @if($handover->requestStatus === 'Approved')
            <div class="complete-handover-section">
                <form action="{{ route('handover.update', $handover->requestID) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="handoverStatus" value="Completed">
                    <button type="submit" class="btn complete-btn">Mark Handover as Completed</button>
                </form>
            </div>
        @endif
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="image-modal" onclick="closeImageModal()">
    <span class="close">&times;</span>
    <img class="modal-content" id="modalImage">
</div>
@endsection

@section('page-js')
<script src="{{ asset('js/sidebar.js') }}"></script>
<script>
// Auto-resize textarea
const textarea = document.getElementById('messageText');
textarea.addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
});

// Preview image before upload
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Remove image preview
function removeImage() {
    document.getElementById('messageImg').value = '';
    document.getElementById('imagePreview').style.display = 'none';
}

// Scroll to bottom of messages
function scrollToBottom() {
    const container = document.getElementById('messagesContainer');
    container.scrollTop = container.scrollHeight;
}

// Scroll to bottom on page load
window.addEventListener('load', scrollToBottom);

// Open image in modal
function openImageModal(src) {
    document.getElementById('imageModal').style.display = 'flex';
    document.getElementById('modalImage').src = src;
}

// Close image modal
function closeImageModal() {
    document.getElementById('imageModal').style.display = 'none';
}

// Auto-refresh messages every 5 seconds
setInterval(function() {
    fetch('{{ route("handover.chat.fetch", $handover->requestID) }}')
        .then(response => response.json())
        .then(messages => {
            updateMessages(messages);
        })
        .catch(error => console.error('Error fetching messages:', error));
}, 5000);

function updateMessages(messages) {
    const container = document.getElementById('messagesContainer');
    const currentScrollPos = container.scrollTop;
    const isScrolledToBottom = container.scrollHeight - container.clientHeight <= currentScrollPos + 50;
    
    // Only update if there are new messages
    const currentMessageCount = container.querySelectorAll('.message').length;
    if (messages.length > currentMessageCount) {
        container.innerHTML = messages.map(msg => `
            <div class="message ${msg.isOwn ? 'own' : 'other'}">
                <div class="message-avatar">
                    <img src="${msg.senderImg}" alt="${msg.senderName}">
                </div>
                <div class="message-content">
                    <div class="message-header">
                        <span class="sender-name">${msg.senderName}</span>
                        <span class="message-time">${msg.created_at}</span>
                    </div>
                    ${msg.messageText ? `<div class="message-text">${msg.messageText}</div>` : ''}
                    ${msg.messageImg ? `<div class="message-image"><img src="${msg.messageImg}" alt="Shared image" onclick="openImageModal('${msg.messageImg}')"></div>` : ''}
                </div>
            </div>
        `).join('');
        
        if (isScrolledToBottom) {
            scrollToBottom();
        }
    }
}
</script>
@endsection