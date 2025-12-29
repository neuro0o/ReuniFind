@extends('layouts.default')

@section('title', 'Chat - ' . $handover->report->itemName)

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/handover/chat.css') }}">
@endsection

@section('content')
    <div class="layout">
        @include('layouts.partials.sidebar')

        <div class="chat-content">
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
                
                <!-- Header Actions -->
                <div class="header-actions">
                    <!-- Reject Button (only for recipient and when Approved) -->
                    @if(auth()->id() === $handover->recipientID && $handover->requestStatus === 'Approved')
                        <button class="reject-btn" onclick="openRejectModal()">
                            ❌ Reject
                        </button>
                    @endif

                    <!-- Handover Status Badge -->
                    <div class="handover-status">
                        @if($handover->requestStatus === 'Approved')
                            <button class="status-badge approved clickable" onclick="openHandoverFormModal()">
                                📋 Form Pending
                            </button>
                        @elseif($handover->requestStatus === 'Completed')
                            <button class="status-badge completed clickable" onclick="openHandoverFormModal()">
                                ✅ Completed
                            </button>
                        @elseif($handover->requestStatus === 'Rejected')
                            <button class="status-badge rejected clickable" onclick="openRejectionReasonModal()">
                                ❌ Rejected
                            </button>
                        @else
                            <span class="status-badge {{ strtolower($handover->requestStatus) }}">
                                {{ $handover->requestStatus }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Rejection Notice (only when rejected) -->
            @if($handover->requestStatus === 'Rejected')
                <div class="rejection-notice">
                    <div class="rejection-notice-content">
                        <span class="rejection-icon">❗</span>
                        <div class="rejection-text">
                            <strong>This handover request was rejected.</strong>
                            <p>Chat is now read-only. Click the status badge to view rejection reason.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Completion Notice (only when completed) -->
            @if($handover->requestStatus === 'Completed')
                <div class="completion-notice">
                    <div class="completion-notice-content">
                        <span class="completion-icon">✅</span>
                        <div class="completion-text">
                            <strong>This handover has been completed successfully!</strong>
                            <p>The signed form has been submitted. You can continue chatting if needed.</p>
                        </div>
                    </div>
                </div>
            @endif

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

            <!-- Message Input Form (disabled if rejected) -->
            <div class="message-input-container {{ $handover->requestStatus === 'Rejected' ? 'disabled' : '' }}">
                @if($handover->requestStatus === 'Rejected')
                    <div class="input-disabled-message">
                        <span>💬</span>
                        <p>Messaging is disabled for rejected handovers</p>
                    </div>
                @else
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
                                    maxlength="1000">
                            </textarea>
                            
                            <!-- Send Button -->
                            <button type="submit" class="send-btn">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <line x1="22" y1="2" x2="11" y2="13"></line>
                                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                </svg>
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="handover-modal" onclick="closeRejectModal(event)">
        <div class="handover-modal-content" onclick="event.stopPropagation()">
            <div class="handover-modal-header reject-header">
                <h2>❌ Reject Handover Request</h2>
                <button class="close-modal-btn" onclick="closeRejectModal()">&times;</button>
            </div>

            <div class="handover-modal-body">
                <p class="modal-description">Please provide a reason for rejecting this handover request. The chat will become read-only after rejection.</p>
                
                <form action="{{ route('handover.update', $handover->requestID) }}" method="POST" id="rejectForm">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="handoverStatus" value="Rejected">
                    
                    <div class="reject-input-group">
                        <label for="rejectionNote">Rejection Reason</label>
                        <textarea name="rejectionNote" 
                                  id="rejectionNote" 
                                  rows="4"
                                  placeholder="Why are you rejecting this request?"
                                  required
                                  maxlength="500"></textarea>
                        <small class="char-count">0 / 500 characters</small>
                    </div>

                    <div class="modal-actions">
                        <button type="submit" class="modal-btn reject-submit-btn">
                            Submit Rejection
                        </button>
                        <button type="button" class="modal-btn cancel-btn" onclick="closeRejectModal()">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rejection Reason Modal (view only) -->
    @if($handover->requestStatus === 'Rejected' && $handover->rejectionNote)
        <div id="rejectionReasonModal" class="handover-modal" onclick="closeRejectionReasonModal(event)">
            <div class="handover-modal-content" onclick="event.stopPropagation()">
                <div class="handover-modal-header reject-header">
                    <h2>❌ Rejection Reason</h2>
                    <button class="close-modal-btn" onclick="closeRejectionReasonModal()">&times;</button>
                </div>

                <div class="handover-modal-body">
                    <div class="rejection-reason-display">
                        <p class="rejection-reason-text">{{ $handover->rejectionNote }}</p>
                        <p class="rejection-meta">
                            Rejected by: <strong>{{ $handover->recipient->userName }}</strong>
                        </p>
                    </div>
                    
                    <div class="modal-actions">
                        <button type="button" class="modal-btn cancel-btn" onclick="closeRejectionReasonModal()">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Handover Form Modal -->
    @if($handover->requestStatus === 'Approved' || ($handover->requestStatus === 'Completed' && $handover->handoverForm))
        <div id="handoverFormModal" class="handover-modal" onclick="closeHandoverFormModal(event)">
            <div class="handover-modal-content" onclick="event.stopPropagation()">
                <div class="handover-modal-header">
                    <h2>
                        @if($handover->requestStatus === 'Approved')
                            📋 Handover Form
                        @else
                            ✅ Handover Completed
                        @endif
                    </h2>
                    <button class="close-modal-btn" onclick="closeHandoverFormModal()">&times;</button>
                </div>

                <div class="handover-modal-body">
                    @if($handover->requestStatus === 'Approved')
                        <p class="modal-description">Download the handover form, sign it, and upload the completed document to finalize the process.</p>
                        
                        <div class="modal-actions">
                            <!-- Download Form -->
                            <a href="{{ route('handover.form.download', $handover->requestID) }}" 
                                class="modal-btn download-btn" 
                                target="_blank">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                Download Handover Form
                            </a>

                            <div class="divider">
                                <span>OR</span>
                            </div>

                            <!-- Upload Form -->
                            <form action="{{ route('handover.form.upload', $handover->requestID) }}" 
                                    method="POST" 
                                    enctype="multipart/form-data"
                                    id="uploadFormModal">
                                @csrf
                                
                                <label for="handoverFormFile" class="upload-area">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="17 8 12 3 7 8"></polyline>
                                        <line x1="12" y1="3" x2="12" y2="15"></line>
                                    </svg>
                                    <span id="uploadText">Click to upload signed form (PDF)</span>
                                    <span id="fileNameDisplay" style="display: none;"></span>
                                </label>
                                <input type="file" 
                                        name="handoverForm" 
                                        id="handoverFormFile" 
                                        accept=".pdf"
                                        required
                                        style="display: none;"
                                        onchange="handleModalFileSelect(this)">
                                
                                <button type="submit" class="modal-btn submit-btn" id="modalSubmitBtn" style="display: none;">
                                    Submit Form
                                </button>
                            </form>
                        </div>
                    @else
                        <p class="modal-description">The handover has been completed successfully. You can download the signed form below.</p>
                        
                        <div class="modal-actions">
                            <a href="{{ route('handover.form.view', $handover->requestID) }}" 
                                class="modal-btn view-btn" 
                                target="_blank">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                                View Uploaded Form
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

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
        if (textarea) {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        }

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

        // Reject Modal Functions
        function openRejectModal() {
            document.getElementById('rejectModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeRejectModal(event) {
            if (!event || event.target.id === 'rejectModal' || event.target.classList.contains('close-modal-btn') || event.target.classList.contains('cancel-btn')) {
                document.getElementById('rejectModal').style.display = 'none';
                const rejectionNote = document.getElementById('rejectionNote');
                if (rejectionNote) rejectionNote.value = '';
                document.body.style.overflow = 'auto';
            }
        }

        // Rejection Reason Modal Functions
        function openRejectionReasonModal() {
            const modal = document.getElementById('rejectionReasonModal');
            if (modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        function closeRejectionReasonModal(event) {
            if (!event || event.target.id === 'rejectionReasonModal' || event.target.classList.contains('close-modal-btn') || event.target.classList.contains('cancel-btn')) {
                const modal = document.getElementById('rejectionReasonModal');
                if (modal) {
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            }
        }

        // Character count for rejection note
        const rejectionNote = document.getElementById('rejectionNote');
        if (rejectionNote) {
            rejectionNote.addEventListener('input', function() {
                const charCount = this.value.length;
                document.querySelector('.char-count').textContent = charCount + ' / 500 characters';
            });
        }

        // Handover Form Modal Functions
        function openHandoverFormModal() {
            document.getElementById('handoverFormModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeHandoverFormModal(event) {
            if (!event || event.target.id === 'handoverFormModal' || event.target.classList.contains('close-modal-btn')) {
                document.getElementById('handoverFormModal').style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        }

        // Handle file selection in modal
        function handleModalFileSelect(input) {
            const uploadText = document.getElementById('uploadText');
            const fileNameDisplay = document.getElementById('fileNameDisplay');
            const submitBtn = document.getElementById('modalSubmitBtn');
            
            if (input.files && input.files[0]) {
                uploadText.style.display = 'none';
                fileNameDisplay.style.display = 'block';
                fileNameDisplay.textContent = '📄 ' + input.files[0].name;
                submitBtn.style.display = 'block';
            } else {
                uploadText.style.display = 'block';
                fileNameDisplay.style.display = 'none';
                submitBtn.style.display = 'none';
            }
        }

        // Close modals on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeHandoverFormModal();
                closeRejectModal();
                closeRejectionReasonModal();
            }
        });

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

        // Only enable auto-refresh if handover is NOT rejected
        @if($handover->requestStatus !== 'Rejected')
        // Track last message count to detect new messages
        let lastMessageCount = document.querySelectorAll('.message').length;
        let isUpdatingMessages = false;

        // Auto-refresh messages every 3 seconds
        setInterval(function() {
            if (isUpdatingMessages) return;
            
            isUpdatingMessages = true;
            
            fetch('{{ route("handover.chat.fetch", $handover->requestID) }}')
                .then(response => response.json())
                .then(messages => {
                    updateMessages(messages);
                    isUpdatingMessages = false;
                })
                .catch(error => {
                    console.error('Error fetching messages:', error);
                    isUpdatingMessages = false;
                });
        }, 3000);

        function updateMessages(messages) {
            const container = document.getElementById('messagesContainer');
            const currentScrollPos = container.scrollTop;
            const isScrolledToBottom = container.scrollHeight - container.clientHeight <= currentScrollPos + 50;
            
            // Check if there are new messages
            const currentMessageCount = container.querySelectorAll('.message').length;
            
            if (messages.length !== currentMessageCount) {
                // Clear and rebuild
                container.innerHTML = messages.length > 0 ? messages.map(msg => `
                    <div class="message ${msg.isOwn ? 'own' : 'other'}">
                        <div class="message-avatar">
                            <img src="${msg.senderImg}" alt="${msg.senderName}">
                        </div>
                        <div class="message-content">
                            <div class="message-header">
                                <span class="sender-name">${msg.senderName}</span>
                                <span class="message-time">${msg.created_at}</span>
                            </div>
                            ${msg.messageText ? `<div class="message-text">${escapeHtml(msg.messageText)}</div>` : ''}
                            ${msg.messageImg ? `<div class="message-image"><img src="${msg.messageImg}" alt="Shared image" onclick="openImageModal('${msg.messageImg}')"></div>` : ''}
                        </div>
                    </div>
                `).join('') : '<div class="no-messages"><p>No messages yet. Start the conversation!</p></div>';
                
                // Scroll to bottom if user was already at bottom OR if it's their own message
                if (isScrolledToBottom || messages.length > lastMessageCount) {
                    setTimeout(scrollToBottom, 100);
                }
                
                lastMessageCount = messages.length;
            }
        }

        // Helper function to escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Send message via AJAX for instant feedback
        const messageForm = document.getElementById('messageForm');
        if (messageForm) {
            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const submitBtn = this.querySelector('.send-btn');
                
                // Disable send button
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.5';
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        // Clear form
                        textarea.value = '';
                        textarea.style.height = 'auto';
                        removeImage();
                        
                        // Immediately fetch new messages
                        return fetch('{{ route("handover.chat.fetch", $handover->requestID) }}')
                            .then(res => res.json())
                            .then(messages => {
                                updateMessages(messages);
                                scrollToBottom();
                            });
                    } else {
                        throw new Error('Failed to send message');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to send message. Please try again.');
                })
                .finally(() => {
                    // Re-enable send button
                    submitBtn.disabled = false;
                    submitBtn.style.opacity = '1';
                });
            });
        }

        // Update when page becomes visible
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden && !isUpdatingMessages) {
                isUpdatingMessages = true;
                fetch('{{ route("handover.chat.fetch", $handover->requestID) }}')
                    .then(response => response.json())
                    .then(messages => {
                        updateMessages(messages);
                        isUpdatingMessages = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        isUpdatingMessages = false;
                    });
            }
        });
        @endif
    </script>
@endsection