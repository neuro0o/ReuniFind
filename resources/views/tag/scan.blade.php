@extends('layouts.default')

@section('title', 'Scan QR Tag')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tag/scan.css') }}">
@endsection

@section('content')
    <div class="layout">
        @include('layouts.partials.sidebar')
        
        <div class="content">
            <div class="scan-container">
                <div class="scan-header">
                    <h1>Scan Found Item</h1>
                    <p>Use your camera to scan the QR Tag attached to the item.</p>
                </div>

                <!-- Scan Method Tabs -->
                <div class="scan-tabs">
                    <button class="tab-btn active" data-tab="camera">
                        <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor">
                            <path d="M480-260q75 0 127.5-52.5T660-440q0-75-52.5-127.5T480-620q-75 0-127.5 52.5T300-440q0 75 52.5 127.5T480-260Zm0-80q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29ZM160-120q-33 0-56.5-23.5T80-200v-480q0-33 23.5-56.5T160-760h126l74-80h240l74 80h126q33 0 56.5 23.5T880-680v480q0 33-23.5 56.5T800-120H160Z"/>
                        </svg>
                        <span>Camera Scan</span>
                    </button>
                    <button class="tab-btn" data-tab="upload">
                        <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor">
                            <path d="M260-160q-91 0-155.5-63T40-377q0-78 47-139t123-78q25-92 100-149t170-57q117 0 198.5 81.5T760-520q69 8 114.5 59.5T920-340q0 75-52.5 127.5T740-160H520q-33 0-56.5-23.5T440-240v-206l-64 62-56-56 160-160 160 160-56 56-64-62v206h220q42 0 71-29t29-71q0-42-29-71t-71-29h-60v-80q0-83-58.5-141.5T480-720q-83 0-141.5 58.5T280-520h-20q-58 0-99 41t-41 99q0 58 41 99t99 41h100v80H260Z"/>
                        </svg>
                        <span>Upload Image</span>
                    </button>
                </div>

                <!-- Camera Scanner Tab -->
                <div class="tab-content active" id="camera-tab">
                    <div class="qr-scanner-box">
                        <!-- Scanner will be injected here -->
                        <div id="qr-reader" style="width: 100%;"></div>
                        
                        <!-- Scanning status messages -->
                        <div id="scan-status" class="scan-status" style="display: none;">
                            <p id="status-message"></p>
                        </div>
                    </div>

                    <!-- Control Buttons -->
                    <div class="scanner-controls">
                        <button class="scan-btn" id="startScanBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor">
                                <path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Z"/>
                            </svg>
                            <span>Start Scanning</span>
                        </button>

                        <button class="stop-btn" id="stopScanBtn" style="display: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor">
                                <path d="M320-320h320v-320H320v320ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/>
                            </svg>
                            <span>Stop Scanning</span>
                        </button>
                    </div>

                    <div class="scan-info">
                        <p><strong>Tip:</strong> Position the QR code within the frame for scanning</p>
                    </div>
                </div>

                <!-- Upload Image Tab -->
                <div class="tab-content" id="upload-tab">
                    <div class="upload-scanner-box">
                        <div class="upload-area" id="uploadArea">
                            <input type="file" id="qrImageInput" accept="image/*" hidden>
                            <div class="upload-placeholder" id="uploadPlaceholder">
                                <svg xmlns="http://www.w3.org/2000/svg" height="80px" viewBox="0 -960 960 960" width="80px" fill="currentColor">
                                    <path d="M260-160q-91 0-155.5-63T40-377q0-78 47-139t123-78q25-92 100-149t170-57q117 0 198.5 81.5T760-520q69 8 114.5 59.5T920-340q0 75-52.5 127.5T740-160H520q-33 0-56.5-23.5T440-240v-206l-64 62-56-56 160-160 160 160-56 56-64-62v206h220q42 0 71-29t29-71q0-42-29-71t-71-29h-60v-80q0-83-58.5-141.5T480-720q-83 0-141.5 58.5T280-520h-20q-58 0-99 41t-41 99q0 58 41 99t99 41h100v80H260Z"/>
                                </svg>
                                <h3>Upload QR Code Image</h3>
                                <p>Click to upload or drag and drop</p>
                                <span>Supports: JPG, PNG, WEBP</span>
                            </div>
                            <div class="image-preview" id="imagePreviewContainer" style="display: none;">
                                <img id="uploadedImage" src="" alt="Uploaded QR Code">
                                <button class="btn-remove" id="removeImageBtn">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor">
                                        <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/>
                                    </svg>
                                    Remove
                                </button>
                            </div>
                        </div>

                        <button class="scan-btn" id="scanImageBtn" style="display: none; margin: 2rem auto;">
                            <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor">
                                <path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Z"/>
                            </svg>
                            <span>Scan Uploaded Image</span>
                        </button>

                        <div id="upload-status" class="scan-status" style="display: none;">
                            <p id="upload-status-message"></p>
                        </div>
                    </div>

                    <div class="scan-info">
                        <p><strong>Tip:</strong> Make sure the QR code is clear and well-lit in the image</p>
                    </div>
                </div>
            </div>
            <br><br><br><br>
        </div>
    </div>
@endsection

@section('page-js')
    <script src="{{ asset('js/sidebar.js') }}"></script>
    
    <!-- HTML5 QR Code Scanner Library for Camera -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    
    <!-- jsQR Library for Image Upload Scanning -->
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    
    <script>
        let html5QrCode = null;
        let isScanning = false;

        const startBtn = document.getElementById('startScanBtn');
        const stopBtn = document.getElementById('stopScanBtn');
        const scanStatus = document.getElementById('scan-status');
        const statusMessage = document.getElementById('status-message');

        // Tab switching
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Stop camera if switching away from camera tab
                if (isScanning && btn.dataset.tab !== 'camera') {
                    stopScanning();
                }

                // Update active states
                tabBtns.forEach(b => b.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                
                btn.classList.add('active');
                document.getElementById(`${btn.dataset.tab}-tab`).classList.add('active');
            });
        });

        // Initialize QR Code Scanner
        function initScanner() {
            html5QrCode = new Html5Qrcode("qr-reader");
        }

        // Start camera scanning
        function startScanning() {
            if (isScanning) return;

            const config = {
                fps: 60,
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0
            };

            html5QrCode.start(
                { facingMode: "environment" },
                config,
                onScanSuccess,
                onScanFailure
            ).then(() => {
                isScanning = true;
                startBtn.style.display = 'none';
                stopBtn.style.display = 'flex';
                showStatus('Scanning... Point camera at QR code', 'info');
            }).catch(err => {
                console.error("Camera error:", err);
                showStatus('Camera access denied or not available. Please check permissions.', 'error');
            });
        }

        // Stop camera scanning
        function stopScanning() {
            if (!isScanning) return;

            html5QrCode.stop().then(() => {
                isScanning = false;
                startBtn.style.display = 'flex';
                stopBtn.style.display = 'none';
                hideStatus();
            }).catch(err => {
                console.error("Error stopping scanner:", err);
            });
        }

        // Handle successful scan
        function onScanSuccess(decodedText, decodedResult) {
            console.log(`QR Code detected: ${decodedText}`);
            
            stopScanning();
            showStatus('QR Code detected! Redirecting...', 'success');
            
            redirectToItemInfo(decodedText);
        }

        function onScanFailure(error) {
            // Silent - called frequently when no QR detected
        }

        // Redirect based on decoded QR text
        function redirectToItemInfo(decodedText) {
            if (decodedText.includes('/tag/info/')) {
                const tagID = decodedText.split('/tag/info/').pop();
                setTimeout(() => {
                    window.location.href = `/tag/info/${tagID}`;
                }, 1000);
            } 
            else if (!isNaN(decodedText)) {
                setTimeout(() => {
                    window.location.href = `/tag/info/${decodedText}`;
                }, 1000);
            }
            else if (decodedText.startsWith('http')) {
                setTimeout(() => {
                    window.location.href = decodedText;
                }, 1000);
            }
            else {
                showStatus('Invalid QR code. Please scan a valid ReuniFind tag.', 'error');
                setTimeout(() => {
                    startScanning();
                }, 2000);
            }
        }

        // Show/Hide status
        function showStatus(message, type) {
            statusMessage.textContent = message;
            scanStatus.className = `scan-status ${type}`;
            scanStatus.style.display = 'block';
        }

        function hideStatus() {
            scanStatus.style.display = 'none';
        }

        // === IMAGE UPLOAD FUNCTIONALITY ===
        const uploadArea = document.getElementById('uploadArea');
        const qrImageInput = document.getElementById('qrImageInput');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        const uploadedImage = document.getElementById('uploadedImage');
        const scanImageBtn = document.getElementById('scanImageBtn');
        const removeImageBtn = document.getElementById('removeImageBtn');
        const uploadStatus = document.getElementById('upload-status');
        const uploadStatusMessage = document.getElementById('upload-status-message');

        // Click to upload
        uploadArea.addEventListener('click', (e) => {
            if (!e.target.closest('.btn-remove')) {
                qrImageInput.click();
            }
        });

        // File selection
        qrImageInput.addEventListener('change', handleImageUpload);

        function handleImageUpload(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    uploadedImage.src = e.target.result;
                    uploadPlaceholder.style.display = 'none';
                    imagePreviewContainer.style.display = 'block';
                    scanImageBtn.style.display = 'flex';
                };
                reader.readAsDataURL(file);
            }
        }

        // Remove image
        removeImageBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            qrImageInput.value = '';
            uploadedImage.src = '';
            uploadPlaceholder.style.display = 'block';
            imagePreviewContainer.style.display = 'none';
            scanImageBtn.style.display = 'none';
            uploadStatus.style.display = 'none';
        });

        // Drag and drop
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('drag-over');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('drag-over');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('drag-over');
            
            const files = e.dataTransfer.files;
            if (files.length > 0 && files[0].type.startsWith('image/')) {
                qrImageInput.files = files;
                handleImageUpload({ target: { files } });
            }
        });

        // Scan uploaded image
        scanImageBtn.addEventListener('click', () => {
            if (!qrImageInput.files[0]) return;

            showUploadStatus('Scanning image...', 'info');
            scanImageBtn.disabled = true;

            const imageFile = qrImageInput.files[0];
            
            // Create image element to read the file
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const img = new Image();
                
                img.onload = function() {
                    // Create canvas to process image
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    
                    canvas.width = img.width;
                    canvas.height = img.height;
                    
                    // Draw image on canvas
                    context.drawImage(img, 0, 0);
                    
                    // Get image data
                    const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                    
                    // Scan for QR code using jsQR
                    const code = jsQR(imageData.data, imageData.width, imageData.height, {
                        inversionAttempts: "dontInvert",
                    });
                    
                    if (code) {
                        console.log('QR decoded from image:', code.data);
                        showUploadStatus('QR Code detected! Redirecting...', 'success');
                        
                        // Redirect after short delay
                        setTimeout(() => {
                            redirectToItemInfo(code.data);
                        }, 500);
                    } else {
                        console.error('No QR code found in image');
                        showUploadStatus('No QR code found in image. Please try another image.', 'error');
                        scanImageBtn.disabled = false;
                    }
                };
                
                img.onerror = function() {
                    console.error('Failed to load image');
                    showUploadStatus('Failed to load image. Please try another file.', 'error');
                    scanImageBtn.disabled = false;
                };
                
                img.src = e.target.result;
            };
            
            reader.onerror = function() {
                console.error('Failed to read file');
                showUploadStatus('Failed to read file. Please try again.', 'error');
                scanImageBtn.disabled = false;
            };
            
            reader.readAsDataURL(imageFile);
        });

        function showUploadStatus(message, type) {
            uploadStatusMessage.textContent = message;
            uploadStatus.className = `scan-status ${type}`;
            uploadStatus.style.display = 'block';
        }

        // Event Listeners
        startBtn.addEventListener('click', startScanning);
        stopBtn.addEventListener('click', stopScanning);

        // Initialize scanner on page load
        document.addEventListener('DOMContentLoaded', initScanner);

        // Clean up on page unload
        window.addEventListener('beforeunload', () => {
            if (isScanning) {
                html5QrCode.stop();
            }
        });
    </script>
@endsection