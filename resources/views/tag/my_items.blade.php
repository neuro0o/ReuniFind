@extends('layouts.default')

@section('title', 'My Registered Items')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tag/my_items.css') }}">
@endsection

@section('content')
    <div class="layout">
        @include('layouts.partials.sidebar')
        
        <div class="content">
            <div class="my-items-container">
                <div class="page-header">
                    <div>
                        <h1>My Registered Items</h1>
                        <p>Manage your registered items here.</p>
                    </div>
                    <a href="{{ route('tag.register') }}" class="btn-add-item">
                        <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor">
                            <path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"/>
                        </svg>
                        <span>Register New Item</span>
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert success">
                        <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor">
                            <path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if($items->isEmpty())
                    <div class="empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" height="100px" viewBox="0 -960 960 960" width="100px" fill="currentColor">
                            <path d="M280-600v-80h560v80H280Zm0 160v-80h560v80H280Zm0 160v-80h560v80H280ZM160-600q-17 0-28.5-11.5T120-640q0-17 11.5-28.5T160-680q17 0 28.5 11.5T200-640q0 17-11.5 28.5T160-600Zm0 160q-17 0-28.5-11.5T120-480q0-17 11.5-28.5T160-520q17 0 28.5 11.5T200-480q0 17-11.5 28.5T160-440Zm0 160q-17 0-28.5-11.5T120-320q0-17 11.5-28.5T160-360q17 0 28.5 11.5T200-320q0 17-11.5 28.5T160-280Z"/>
                        </svg>
                        <h3>No Items Registered Yet</h3>
                        <p>Start by registering your first item to get a QR tag!</p>
                        <a href="{{ route('tag.register') }}" class="btn-primary">Register Your First Item</a>
                    </div>
                @else
                    <div class="items-grid">
                        @foreach($items as $item)
                            <div class="item-card">
                                <!-- Item Image -->
                                <div class="item-image">
                                    @if($item->itemImg)
                                        <img src="{{ asset('storage/' . $item->itemImg) }}" alt="{{ $item->itemName }}">
                                    @else
                                        <div class="no-image">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Item Info -->
                                <div class="item-info">
                                    <h3>{{ $item->itemName }}</h3>
                                    <p class="item-category">{{ $item->category->categoryName }}</p>
                                    <p class="item-description">{{ Str::limit($item->itemDescription, 60) }}</p>
                                    
                                    <!-- Status Badge -->
                                    <span class="status-badge {{ strtolower($item->itemStatus) }}">
                                        {{ $item->itemStatus }}
                                    </span>
                                </div>

                                <!-- Action Buttons -->
                                <div class="item-actions">
                                    <!-- Status Toggle -->
                                    <form action="{{ route('tag.status', $item->tagID) }}" method="POST" class="status-form">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="itemStatus" value="{{ $item->itemStatus === 'Safe' ? 'Lost' : 'Safe' }}">
                                        <button type="submit" class="btn-status {{ $item->itemStatus === 'Safe' ? 'to-lost' : 'to-safe' }}">
                                            @if($item->itemStatus === 'Safe')
                                                <span>Tag as Lost</span>
                                            @else
                                                <span>Tag as Safe</span>
                                            @endif
                                        </button>
                                    </form>

                                    <!-- Action Buttons Row -->
                                    <div class="btn-row">
                                        <a href="{{ route('tag.edit', $item->tagID) }}" class="btn-icon" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor">
                                                <path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/>
                                            </svg>
                                        </a>

                                        <a href="{{ route('tag.detail', $item->tagID) }}" class="btn-icon" title="View QR Tag">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 448 512" fill="currentColor">
                                                <path d="M0 80C0 53.5 21.5 32 48 32h96c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V80zM64 96v64h64V96H64zM0 336c0-26.5 21.5-48 48-48h96c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V336zm64 16v64h64V352H64zM304 32h96c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H304c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48zm80 64H320v64h64V96zM256 304c0-8.8 7.2-16 16-16h64c8.8 0 16 7.2 16 16s7.2 16 16 16h32c8.8 0 16-7.2 16-16s7.2-16 16-16s16 7.2 16 16v96c0 8.8-7.2 16-16 16H368c-8.8 0-16-7.2-16-16s-7.2-16-16-16s-16 7.2-16 16v64c0 8.8-7.2 16-16 16H272c-8.8 0-16-7.2-16-16V304zM368 480a16 16 0 1 1 0 32 16 16 0 1 1 0-32zm64 0a16 16 0 1 1 0 32 16 16 0 1 1 0-32z"/>
                                            </svg>
                                        </a>

                                        <form action="{{ route('tag.destroy', $item->tagID) }}" method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon delete" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor">
                                                    <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Print QR Tag Button -->
                                    <a href="{{ route('tag.detail', $item->tagID) }}" class="btn-print-tag">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px" fill="currentColor">
                                            <path d="M640-640v-120H320v120h-80v-200h480v200h-80Zm-480 80h640-640Zm560 100q17 0 28.5-11.5T760-500q0-17-11.5-28.5T720-540q-17 0-28.5 11.5T680-500q0 17 11.5 28.5T720-460Zm-80 260v-160H320v160h320Zm80 80H240v-160H80v-240q0-51 35-85.5t85-34.5h560q51 0 85.5 34.5T880-520v240H720v160Zm80-240v-160q0-17-11.5-28.5T760-560H200q-17 0-28.5 11.5T160-520v160h80v-80h480v80h80Z"/>
                                        </svg>
                                        <span>Print Item QR Tag</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script src="{{ asset('js/sidebar.js') }}"></script>
@endsection