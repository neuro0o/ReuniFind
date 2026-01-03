<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Tag - {{ $itemTag->itemName }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Arial', sans-serif;
            background: white;
            padding: {{ $size === 'small' ? '8px' : ($size === 'large' ? '15px' : '10px') }};
        }

        .qr-tag-container {
            width: 100%;
            height: 100%;
            border: {{ $size === 'small' ? '2px' : '3px' }} dashed #3A5987;
            border-radius: {{ $size === 'small' ? '10px' : ($size === 'large' ? '25px' : '15px') }};
            padding: {{ $size === 'small' ? '10px' : ($size === 'large' ? '25px' : '15px') }};
            text-align: center;
            background: #F1F4FB;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .tag-header {
            margin-bottom: {{ $size === 'small' ? '8px' : ($size === 'large' ? '15px' : '10px') }};
        }

        .logo {
            width: {{ $size === 'small' ? '30px' : ($size === 'large' ? '60px' : '45px') }};
            margin-bottom: {{ $size === 'small' ? '4px' : '8px' }};
        }

        .tag-header h1 {
            font-family: 'Quicksand', sans-serif;
            color: #3A5987;
            font-size: {{ $size === 'small' ? '10px' : ($size === 'large' ? '20px' : '14px') }};
            font-weight: 700;
            margin-bottom: {{ $size === 'small' ? '2px' : '4px' }};
        }

        .tag-header p {
            color: #666;
            font-size: {{ $size === 'small' ? '6px' : ($size === 'large' ? '10px' : '8px') }};
            font-weight: 500;
        }

        .divider {
            height: {{ $size === 'small' ? '1px' : '2px' }};
            background: #3A5987;
            margin: {{ $size === 'small' ? '8px 0' : '12px 0' }};
            border-radius: 2px;
        }

        .qr-section {
            background: white;
            padding: {{ $size === 'small' ? '8px' : ($size === 'large' ? '18px' : '12px') }};
            border-radius: {{ $size === 'small' ? '8px' : '12px' }};
            margin: {{ $size === 'small' ? '8px 0' : '12px 0' }};
            box-shadow: 0 2px 8px rgba(58, 89, 135, 0.1);
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .scan-text {
            color: #3A5987;
            font-weight: 600;
            font-size: {{ $size === 'small' ? '7px' : ($size === 'large' ? '12px' : '9px') }};
            margin-bottom: {{ $size === 'small' ? '6px' : ($size === 'large' ? '12px' : '8px') }};
        }

        .qr-code {
            width: 100%;
            margin: 0 auto;
            padding: {{ $size === 'small' ? '4px' : ($size === 'large' ? '10px' : '6px') }};
            background: white;
            border: {{ $size === 'small' ? '1px' : '2px' }} solid #D5DEEF;
            border-radius: {{ $size === 'small' ? '6px' : '8px' }};
        }

        .qr-code svg {
            width: 100%;
            height: auto;
            display: block;
        }

        .reunite-text {
            margin-top: {{ $size === 'small' ? '6px' : ($size === 'large' ? '12px' : '8px') }};
            color: #666;
            font-size: {{ $size === 'small' ? '6px' : ($size === 'large' ? '10px' : '8px') }};
            line-height: 1.4;
        }

        .reunite-text strong {
            color: #3A5987;
            font-weight: 600;
        }

        @if($size !== 'small')
        .item-info {
            background: white;
            padding: {{ $size === 'large' ? '12px' : '8px' }};
            border-radius: {{ $size === 'large' ? '10px' : '8px' }};
            text-align: left;
            margin-top: {{ $size === 'large' ? '15px' : '10px' }};
        }

        .item-info h2 {
            font-family: 'Quicksand', sans-serif;
            color: #3A5987;
            font-size: {{ $size === 'large' ? '14px' : '10px' }};
            font-weight: 600;
            margin-bottom: {{ $size === 'large' ? '8px' : '6px' }};
            border-bottom: {{ $size === 'large' ? '2px' : '1px' }} solid #D5DEEF;
            padding-bottom: {{ $size === 'large' ? '6px' : '4px' }};
        }

        .info-row {
            margin: {{ $size === 'large' ? '6px 0' : '4px 0' }};
            font-size: {{ $size === 'large' ? '9px' : '7px' }};
        }

        .info-row strong {
            color: #3A5987;
            font-weight: 600;
            display: inline-block;
            width: {{ $size === 'large' ? '60px' : '45px' }};
        }

        .info-row span {
            color: #333;
        }
        @endif

        .tag-footer {
            margin-top: {{ $size === 'small' ? '8px' : ($size === 'large' ? '15px' : '10px') }};
            padding-top: {{ $size === 'small' ? '6px' : ($size === 'large' ? '12px' : '8px') }};
            border-top: {{ $size === 'small' ? '1px' : '2px' }} solid #D5DEEF;
        }

        .tag-id {
            font-size: {{ $size === 'small' ? '5px' : ($size === 'large' ? '9px' : '7px') }};
            color: #999;
            font-weight: 500;
        }

        .website {
            font-size: {{ $size === 'small' ? '6px' : ($size === 'large' ? '11px' : '8px') }};
            color: #3A5987;
            font-weight: 600;
            margin-top: {{ $size === 'small' ? '2px' : '4px' }};
        }

        /* Print-specific styles */
        @media print {
            body {
                padding: 0;
            }

            .qr-tag-container {
                border-color: #3A5987;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="qr-tag-container">
        <!-- Header -->
        <div class="tag-header">
            @if($size !== 'small')
            <img src="{{ public_path('images/ReuniFind_Logo.png') }}" alt="ReuniFind Logo" class="logo">
            @endif
            <h1>ReuniFind</h1>
            <p>Item QR Tag</p>
        </div>

        <div class="divider"></div>

        <!-- QR Code Section -->
        <div class="qr-section">
            <p class="scan-text">🔍 SCAN & REUNITE!</p>
            
            <div class="qr-code">
                {!! file_get_contents(storage_path('app/public/' . $itemTag->tagImg)) !!}
            </div>

            <div class="reunite-text">
                <p>Scan the QR Code to</p>
                <p><strong>reunite with the owner!</strong></p>
            </div>
        </div>

        @if($size !== 'small')
        <!-- Item Information (Medium and Large only) -->
        <div class="item-info">
            <h2>Item Details</h2>
            
            <div class="info-row">
                <strong>Item:</strong>
                <span>{{ Str::limit($itemTag->itemName, $size === 'large' ? 30 : 20) }}</span>
            </div>

            <div class="info-row">
                <strong>Category:</strong>
                <span>{{ $itemTag->category->categoryName }}</span>
            </div>

            <div class="info-row">
                <strong>Owner:</strong>
                <span>{{ Str::limit($itemTag->user->userName, $size === 'large' ? 25 : 15) }}</span>
            </div>

            @if($itemTag->user->contactInfo && $size === 'large')
            <div class="info-row">
                <strong>Contact:</strong>
                <span>{{ Str::limit($itemTag->user->contactInfo, 20) }}</span>
            </div>
            @endif
        </div>
        @endif

        <!-- Footer -->
        <div class="tag-footer">
            <p class="tag-id">Tag ID: #{{ str_pad($itemTag->tagID, 5, '0', STR_PAD_LEFT) }}</p>
            <p class="website">reunifind.com</p>
        </div>
    </div>
</body>
</html>