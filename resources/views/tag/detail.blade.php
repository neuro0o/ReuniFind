@extends('layouts.default')

@section('title', 'Item QR Tag')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tag/detail.css') }}">
    <style>
        
    </style>
@endsection

@section('content')
    <div class="layout">
        @include('layouts.partials.sidebar')
        
        <div class="content">
            <div class="detail-container">
                @if(session('success'))
                    <div class="alert success">
                        <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor">
                            <path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="detail-header">
                    <h1>Print and attach the generated Item QR Tag to keep track of your newly registered item!</h1>
                </div>

                <div class="qr-display-grid">
                    <!-- Left Side - Your Item Info -->
                    <div class="item-info-card">
                        <h2>Your Item Info</h2>
                        
                        <div class="info-image">
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

                        <div class="info-details">
                            <h3>{{ $itemTag->itemName }}</h3>
                            <p class="category">({{ $itemTag->category->categoryName }})</p>
                            <p class="description">{{ $itemTag->itemDescription }}</p>
                            
                            <div class="owner-info">
                                <p><strong>Owner:</strong> {{ $itemTag->user->userName }}</p>
                                <p><strong>Contact Info:</strong> {{ $itemTag->user->contactInfo ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Item QR Tag Preview -->
                    <div class="qr-tag-card">
                        <h2>Item QR Tag Preview</h2>
                        
                        <!-- Actual Tag Preview (matches PDF output) -->
                        <div class="tag-preview-container">
                            <div class="tag-preview size-small" id="tagPreview">
                                @if(file_exists(public_path('images/ReuniFind_Logo.png')))
                                    <img src="{{ asset('images/ReuniFind_Logo.png') }}" class="tag-logo">
                                @endif
                                
                                <div class="tag-title">Item QR Tag</div>
                                
                                <div class="tag-qr">
                                    @if($itemTag->tagImg)
                                        <img src="{{ asset('storage/' . $itemTag->tagImg) }}" alt="QR Code">
                                    @else
                                        <p style="font-size: 10px; color: #999;">QR Not Found</p>
                                    @endif
                                </div>
                                
                                <div class="tag-text">
                                    Scan the QR Tag to<br>
                                    Reunite the item with its owner!
                                </div>
                                
                                <div class="tag-footer-text">Powered by ReuniFind</div>
                            </div>
                        </div>

                        <!-- Size Selection -->
                        <div class="size-selection">
                            <h3>Select Tag Size</h3>
                            <p class="size-help">Choose the right size for your item</p>
                            
                            <div class="size-options">
                                <div class="size-option" data-size="small">
                                    <div class="size-preview small-preview">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M3 3h6v6H3V3zm2 2v2h2V5H5zm8-2h6v6h-6V3zm2 2v2h2V5h-2zM3 13h6v6H3v-6zm2 2v2h2v-2H5zm10-2h2v2h-2v-2zm0 2h2v2h-2v-2zm-2-2h2v2h-2v-2zm0 2h2v2h-2v-2zm0 2h2v2h-2v-2zm2 0h2v2h-2v-2zm2 0h2v2h-2v-2zm0-2h2v2h-2v-2zm0-2h2v2h-2v-2z"/>
                                        </svg>
                                    </div>
                                    <div class="size-info">
                                        <h4>Small</h4>
                                        <p class="size-dimensions">5cm × 5cm</p>
                                        <p class="size-use">For: Phone, Earbuds, Keys</p>
                                    </div>
                                    <input type="radio" name="tag_size" value="small" id="size_small" checked>
                                </div>

                                <div class="size-option" data-size="medium">
                                    <div class="size-preview medium-preview">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M3 3h6v6H3V3zm2 2v2h2V5H5zm8-2h6v6h-6V3zm2 2v2h2V5h-2zM3 13h6v6H3v-6zm2 2v2h2v-2H5zm10-2h2v2h-2v-2zm0 2h2v2h-2v-2zm-2-2h2v2h-2v-2zm0 2h2v2h-2v-2zm0 2h2v2h-2v-2zm2 0h2v2h-2v-2zm2 0h2v2h-2v-2zm0-2h2v2h-2v-2zm0-2h2v2h-2v-2z"/>
                                        </svg>
                                    </div>
                                    <div class="size-info">
                                        <h4>Medium</h4>
                                        <p class="size-dimensions">7cm × 7cm</p>
                                        <p class="size-use">For: Wallet, Book, Water Bottle</p>
                                    </div>
                                    <input type="radio" name="tag_size" value="medium" id="size_medium">
                                </div>

                                <div class="size-option" data-size="large">
                                    <div class="size-preview large-preview">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M3 3h6v6H3V3zm2 2v2h2V5H5zm8-2h6v6h-6V3zm2 2v2h2V5h-2zM3 13h6v6H3v-6zm2 2v2h2v-2H5zm10-2h2v2h-2v-2zm0 2h2v2h-2v-2zm-2-2h2v2h-2v-2zm0 2h2v2h-2v-2zm0 2h2v2h-2v-2zm2 0h2v2h-2v-2zm2 0h2v2h-2v-2zm0-2h2v2h-2v-2zm0-2h2v2h-2v-2z"/>
                                        </svg>
                                    </div>
                                    <div class="size-info">
                                        <h4>Large</h4>
                                        <p class="size-dimensions">10cm × 10cm</p>
                                        <p class="size-use">For: Laptop, Bag, Luggage</p>
                                    </div>
                                    <input type="radio" name="tag_size" value="large" id="size_large">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('tag.my') }}" class="btn-action secondary">
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor">
                            <path d="M280-600v-80h560v80H280Zm0 160v-80h560v80H280Zm0 160v-80h560v80H280ZM160-600q-17 0-28.5-11.5T120-640q0-17 11.5-28.5T160-680q17 0 28.5 11.5T200-640q0 17-11.5 28.5T160-600Zm0 160q-17 0-28.5-11.5T120-480q0-17 11.5-28.5T160-520q17 0 28.5 11.5T200-480q0 17-11.5 28.5T160-440Zm0 160q-17 0-28.5-11.5T120-320q0-17 11.5-28.5T160-360q17 0 28.5 11.5T200-320q0 17-11.5 28.5T160-280Z"/>
                        </svg> -->
                        <span>My Registered Item</span>
                    </a>

                    <a href="#" id="downloadBtn" class="btn-action primary">
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor">
                            <path d="M480-320 280-520l56-58 104 104v-326h80v326l104-104 56 58-200 200ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z"/>
                        </svg> -->
                        <span>Print Tag</span>
                    </a>
                </div>
            </div>
            <br><br><br><br>
        </div>
    </div>
@endsection

@section('page-js')
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script>
        // Handle size selection
        const sizeOptions = document.querySelectorAll('.size-option');
        const downloadBtn = document.getElementById('downloadBtn');
        const tagPreview = document.getElementById('tagPreview');
        
        sizeOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Remove active class from all
                sizeOptions.forEach(opt => opt.classList.remove('active'));
                
                // Add active class to clicked
                this.classList.add('active');
                
                // Check the radio button
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                
                // Update tag preview size
                const selectedSize = radio.value;
                tagPreview.className = 'tag-preview size-' + selectedSize;
            });
        });

        // Handle download with selected size
        downloadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const selectedSize = document.querySelector('input[name="tag_size"]:checked').value;
            const tagID = {{ $itemTag->tagID }};
            
            // Redirect to download with size parameter
            window.location.href = `/tag/item/${tagID}/download?size=${selectedSize}`;
        });

        // Set initial active state
        document.querySelector('.size-option[data-size="small"]').classList.add('active');
    </script>
@endsection