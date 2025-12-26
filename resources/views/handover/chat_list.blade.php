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
            <h1>Messages</h1>
            <p class="subtitle">Chat with users about handover requests</p>
        </div>

        @if($chats->isEmpty())
            <div class="empty-state">
                <img src="{{ asset('images/icons/Chat & Request.svg') }}" alt="No chats" class="empty-icon">
                <h3>No active chats</h3>
                <p>Accept handover requests to start chatting with other users</p>
                <a href="{{ route('handover.index') }}" class="btn primary">View Handover Requests</a>
            </div>
        @else
            <div class="chat-list">
                @foreach($chats as $chat)
                    <a href="{{ route('handover.chat.show', $chat['handover']->requestID) }}" class="chat-item">
                        <div class="chat-avatar">
                            <img src="{{ $chat['otherUser']->profileImg ? asset('storage/' . $chat['otherUser']->profileImg) : asset('images/profiles/user_default.png') }}" 
                                 alt="{{ $chat['otherUser']->userName }}">
                            @if($chat['handover']->requestStatus === 'Completed')
                                <span class="status-indicator completed"></span>
                            @else
                                <span class="status-indicator active"></span>
                            @endif
                        </div>

                        <div class="chat-info">
                            <div class="chat-header-row">
                                <h3 class="chat-name">{{ $chat['otherUser']->userName }}</h3>
                                <span class="chat-time">
                                    @if($chat['lastActivity']->isToday())
                                        {{ $chat['lastActivity']->format('h:i A') }}
                                    @elseif($chat['lastActivity']->isYesterday())
                                        Yesterday
                                    @else
                                        {{ $chat['lastActivity']->format('d/m/Y') }}
                                    @endif
                                </span>
                            </div>

                            <div class="chat-preview-row">
                                <div class="chat-preview">
                                    <span class="item-badge">{{ $chat['handover']->report->itemName }}</span>
                                    @if($chat['lastMessage'])
                                        <span class="last-message">
                                            @if($chat['lastMessage']->senderID === auth()->id())
                                                You: 
                                            @endif
                                            @if($chat['lastMessage']->messageText)
                                                {{ Str::limit($chat['lastMessage']->messageText, 50) }}
                                            @elseif($chat['lastMessage']->messageImg)
                                                📷 Photo
                                            @endif
                                        </span>
                                    @else
                                        <span class="last-message empty">No messages yet</span>
                                    @endif
                                </div>

                                @if($chat['unreadCount'] > 0)
                                    <span class="unread-badge">{{ $chat['unreadCount'] }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="chat-arrow">
                            <img src="{{ asset('images/icons/Arrow Down.svg') }}" alt="View" style="transform: rotate(-90deg);">
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
@endsection