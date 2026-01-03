@extends('layouts.default')

@section('title', 'Item Info')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tag/info.css') }}">
@endsection

@section('content')
    <div class="layout">
        @include('layouts.partials.sidebar')
        
        <div class="content">
            <div class="item-info-container">
                <div class="info-header">
                    <h1>Item Info</h1>
                    <p>Please contact the item owner to reunite them!</p>
                </div>

                <div class="item-card">
                    <!-- Item Image -->
                    <div class="item-image">
                        @if($itemTag->itemImg)
                            <img src="{{ asset('storage/' . $itemTag->itemImg) }}" alt="{{ $itemTag->itemName }}">
                        @else
                            <div class="no-image">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Item Details -->
                    <div class="item-details">
                        <h2 class="item-name">{{ $itemTag->itemName }}</h2>
                        <p class="item-category">({{ $itemTag->category->categoryName }})</p>
                        
                        <div class="item-description">
                            <p>{{ $itemTag->itemDescription }}</p>
                        </div>

                        <!-- Owner Info -->
                        <div class="owner-section">
                            <h3>Owner Information</h3>
                            <div class="owner-details">
                                <div class="owner-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor">
                                        <path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/>
                                    </svg>
                                    <strong>Owner:</strong> {{ $itemTag->user->userName }}
                                </div>
                                <div class="owner-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor">
                                        <path d="M798-120q-125 0-247-54.5T329-329Q229-429 174.5-551T120-798q0-18 12-30t30-12h162q14 0 25 9.5t13 22.5l26 140q2 16-1 27t-11 19l-97 98q20 37 47.5 71.5T387-386q31 31 65 57.5t72 48.5l94-94q9-9 23.5-13.5T670-390l138 28q14 4 23 14.5t9 23.5v162q0 18-12 30t-30 12Z"/>
                                    </svg>
                                    <strong>Contact Info:</strong> {{ $itemTag->user->contactInfo ?? 'Not provided' }}
                                </div>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="status-badge {{ strtolower($itemTag->itemStatus) }}">
                            @if($itemTag->itemStatus === 'Lost')
                                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor">
                                    <path d="M440-756q11-2 20-3t20-1q11 0 20 1t20 3v-4q0-17-11.5-28.5T480-800q-17 0-28.5 11.5T440-760v4ZM280-80q-33 0-56.5-23.5T200-160v-320q0-85 44.5-152T360-732v-28q0-50 34.5-85t85.5-35q51 0 85.5 35t34.5 85v28q71 33 115.5 100T760-480v320q0 33-23.5 56.5T680-80H280Zm200-200q83 0 141.5-58.5T680-480q0-83-58.5-141.5T480-680q-83 0-141.5 58.5T280-480q0 83 58.5 141.5T480-280Z"/>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor">
                                    <path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z"/>
                                </svg>
                            @endif
                            <span>Status: {{ $itemTag->itemStatus }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    @auth
                        @if($itemTag->userID !== Auth::id())
                            <a href="{{ route('handover.chat.index') }}" class="btn-action primary">
                                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor">
                                    <path d="M240-400h320v-80H240v80Zm0-120h480v-80H240v80Zm0-120h480v-80H240v80ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Z"/>
                                </svg>
                                <span>Private Chat</span>
                            </a>
                        @endif
                    @endauth
                    
                    <a href="{{ route('tag.scan') }}" class="btn-action secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor">
                            <path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"/>
                        </svg>
                        <span>Re Scan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script src="{{ asset('js/sidebar.js') }}"></script>
@endsection