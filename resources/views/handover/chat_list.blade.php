@extends('layouts.default')

@section('title', 'My Chats')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/handover/chat_list.css') }}">
@endsection

@section('content')
    <div class="layout">
        @include('layouts.partials.sidebar')

        <div class="main-content">
            <div class="chat-list-header">
                <h1>Private Chat</h1>
            </div>

            <!-- Filter Tabs: All / Unread (counts CONVERSATIONS not messages) -->
            <div class="filter-tabs">
                <a href="{{ route('handover.chat.index', ['filter' => 'all']) }}" 
                    class="tab-btn {{ (!request('filter') || request('filter') === 'all') ? 'active' : '' }}">
                    All
                </a>
                <a href="{{ route('handover.chat.index', ['filter' => 'unread']) }}" 
                    class="tab-btn {{ request('filter') === 'unread' ? 'active' : '' }}">
                    Unread
                    @php
                        // Count CONVERSATIONS with unread messages (not total message count)
                        $unreadConversations = $chats->filter(function($chat) {
                            return $chat['unreadCount'] > 0;
                        })->count();
                    @endphp
                    @if($unreadConversations > 0)
                        <span class="total-unread-badge" id="totalUnreadBadge">{{ $unreadConversations }}</span>
                    @endif
                </a>
            </div>

            @if($chats->isEmpty())
                <div class="empty-state">
                    <img src="{{ asset('images/icons/Chat & Request.svg') }}" alt="No chats" class="empty-icon">
                    <h3>No {{ request('filter') === 'unread' ? 'unread' : '' }} chats</h3>
                    <p>{{ request('filter') === 'unread' ? 'All caught up! No unread messages.' : 'Accept handover requests to start chatting with other users' }}</p>
                    @if(request('filter') !== 'unread')
                        <a href="{{ route('handover.index') }}" class="btn primary">View Handover Requests</a>
                    @endif
                </div>
            @else
                <div class="chat-list" id="chatList">
                    @foreach($chats as $chat)
                        <a href="{{ route('handover.chat.show', $chat['handover']->requestID) }}" 
                            class="chat-item {{ $chat['unreadCount'] > 0 ? 'has-unread' : '' }}"
                            data-request-id="{{ $chat['handover']->requestID }}"
                            data-unread="{{ $chat['unreadCount'] }}">
                            <div class="chat-avatar">
                                <img src="{{ $chat['otherUser']->profileImg ? asset('storage/' . $chat['otherUser']->profileImg) : asset('images/profiles/user_default.png') }}" 
                                    alt="{{ $chat['otherUser']->userName }}">
                            </div>

                            <div class="chat-info">
                                <div class="chat-header-row">
                                    <h3 class="chat-name">
                                        {{ $chat['otherUser']->userName }} 
                                        <span class="item-name">({{ $chat['handover']->report->itemName }})</span>
                                    </h3>
                                    <span class="chat-time">
                                        @if($chat['lastActivity']->isToday())
                                            {{ $chat['lastActivity']->format('H:i') }}
                                        @elseif($chat['lastActivity']->isYesterday())
                                            Yesterday
                                        @else
                                            {{ $chat['lastActivity']->format('d/m/Y') }}
                                        @endif
                                    </span>
                                </div>

                                <div class="chat-preview-row">
                                    <div class="chat-message-preview">
                                        @if($chat['lastMessage'])
                                            <p class="last-message {{ $chat['unreadCount'] > 0 ? 'unread' : '' }}">
                                                @if($chat['lastMessage']->messageText)
                                                    {{ Str::limit($chat['lastMessage']->messageText, 60) }}
                                                @elseif($chat['lastMessage']->messageImg)
                                                    📷 Photo
                                                @endif
                                            </p>
                                        @else
                                            <p class="last-message empty">No messages yet</p>
                                        @endif
                                    </div>

                                    @if($chat['unreadCount'] > 0)
                                        <span class="unread-badge">{{ $chat['unreadCount'] }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

@section('page-js')
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script>
        // Live update chat list every 5 seconds
        let isUpdating = false;

        function updateChatList() {
            if (isUpdating) return;
            isUpdating = true;

            const currentFilter = new URLSearchParams(window.location.search).get('filter') || 'all';
            
            fetch(`{{ route('handover.chat.updates') }}?filter=${currentFilter}`)
                .then(response => response.json())
                .then(data => {
                    updateChats(data.chats);
                    isUpdating = false;
                })
                .catch(error => {
                    console.error('Error updating chat list:', error);
                    isUpdating = false;
                });
        }

        function updateChats(chats) {
            const chatList = document.getElementById('chatList');
            if (!chatList) return;

            // Store current scroll position
            const scrollPos = window.scrollY;

            // Count CONVERSATIONS with unread messages (not total message count)
            let unreadConversations = 0;

            // Update each chat item
            chats.forEach(chat => {
                const chatItem = document.querySelector(`[data-request-id="${chat.requestID}"]`);
                if (!chatItem) return;

                // Count conversation if it has unread
                if (chat.unreadCount > 0) {
                    unreadConversations++;
                }

                // Update data attribute
                chatItem.setAttribute('data-unread', chat.unreadCount);

                // Update unread badge
                const unreadBadge = chatItem.querySelector('.unread-badge');
                const lastMessage = chatItem.querySelector('.last-message');
                const chatPreview = chatItem.querySelector('.chat-preview-row');

                // Update unread count
                if (chat.unreadCount > 0) {
                    chatItem.classList.add('has-unread');
                    
                    if (unreadBadge) {
                        unreadBadge.textContent = chat.unreadCount;
                    } else {
                        // Create badge if it doesn't exist
                        const badge = document.createElement('span');
                        badge.className = 'unread-badge';
                        badge.textContent = chat.unreadCount;
                        chatPreview.appendChild(badge);
                    }

                    if (lastMessage) {
                        lastMessage.classList.add('unread');
                    }
                } else {
                    chatItem.classList.remove('has-unread');
                    if (unreadBadge) {
                        unreadBadge.remove();
                    }
                    if (lastMessage) {
                        lastMessage.classList.remove('unread');
                    }
                }

                // Update last message text
                if (chat.lastMessage && lastMessage && !lastMessage.classList.contains('empty')) {
                    lastMessage.textContent = chat.lastMessage;
                }

                // Update time
                const timeElement = chatItem.querySelector('.chat-time');
                if (timeElement && chat.time) {
                    timeElement.textContent = chat.time;
                }
            });

            // Update total unread badge (conversation count, not message count)
            updateTotalUnreadBadge(unreadConversations);

            // Restore scroll position
            window.scrollTo(0, scrollPos);
        }

        function updateTotalUnreadBadge(count) {
            const totalBadge = document.getElementById('totalUnreadBadge');
            const unreadTab = document.querySelector('.tab-btn[href*="unread"]');

            if (count > 0) {
                if (totalBadge) {
                    totalBadge.textContent = count;
                } else if (unreadTab) {
                    // Create badge if it doesn't exist
                    const badge = document.createElement('span');
                    badge.className = 'total-unread-badge';
                    badge.id = 'totalUnreadBadge';
                    badge.textContent = count;
                    unreadTab.appendChild(badge);
                }
            } else {
                // Remove badge if count is 0
                if (totalBadge) {
                    totalBadge.remove();
                }
            }
        }

        // Update every 5 seconds
        setInterval(updateChatList, 5000);

        // Also update when page becomes visible (user switches back to tab)
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                updateChatList();
            }
        });
    </script>
@endsection
