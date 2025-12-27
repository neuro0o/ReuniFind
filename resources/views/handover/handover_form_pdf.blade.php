<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ReuniFind Handover Form</title>
    <style>
        @page {
            margin: 30px;
            size: A4;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 11px;
            line-height: 1.4;
        }
        
        .container {
            width: 100%;
            height: 100%;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 12px;
            border-bottom: 3px solid #3A5987;
        }
        
        .logo-circle {
            width: 70px;
            height: 70px;
            /* background: #3A5987; */
            border-radius: 50%;
            margin: 0 auto 8px;
            position: relative;
        }
        
        /* .logo-inner {
            width: 30px;
            height: 30px;
            background: #F1F4FB;
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        } */
        
        .title {
            color: #3A5987;
            font-size: 20px;
            font-weight: bold;
            margin: 5px 0;
        }
        
        .subtitle {
            color: #666;
            font-size: 10px;
            margin: 3px 0;
        }
        
        .content {
            margin: 12px 0;
        }
        
        .item-section {
            background: #f9fafb;
            border: 2px solid #D5DEEF;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 12px;
        }
        
        .item-layout {
            display: table;
            width: 100%;
        }
        
        .item-image-col {
            display: table-cell;
            width: 100px;
            vertical-align: top;
        }
        
        .item-image {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 6px;
            border: 2px solid #3A5987;
        }
        
        .no-image {
            width: 90px;
            height: 90px;
            background: #D5DEEF;
            border-radius: 6px;
            border: 2px solid #3A5987;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3A5987;
            font-weight: bold;
            font-size: 10px;
            text-align: center;
        }
        
        .item-details-col {
            display: table-cell;
            vertical-align: top;
            padding-left: 12px;
        }
        
        .item-row {
            margin: 4px 0;
            font-size: 11px;
        }
        
        .label {
            color: #3A5987;
            font-weight: bold;
            display: inline-block;
            width: 120px;
            vertical-align: top;
        }
        
        .value {
            color: #333;
            display: inline;
        }
        
        .info-box {
            background: #E4E9F7;
            padding: 10px;
            border-radius: 6px;
            margin: 12px 0;
            border-left: 4px solid #3A5987;
        }
        
        .signature-section {
            margin: 15px 0;
        }
        
        .signature-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        
        .signature-box {
            display: table-cell;
            width: 48%;
            padding: 10px;
            background: #f9fafb;
            border-radius: 6px;
        }
        
        .signature-box:first-child {
            margin-right: 4%;
        }
        
        .signature-label {
            color: #3A5987;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 25px;
        }
        
        .signature-line {
            border-top: 2px solid #333;
            padding-top: 4px;
            text-align: center;
            color: #666;
            font-size: 9px;
        }
        
        .footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 2px solid #D5DEEF;
            color: #666;
            font-size: 9px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo-circle">
                <img src="{{ public_path('images/ReuniFind_Logo.png') }}" alt="ReuniFind" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <div class="title">ReuniFind Handover Form</div>
            <div class="subtitle">This certifies that the following item has been claimed by/returned to its rightful owner.</div>
        </div>

        <!-- Item Details -->
        <div class="content">
            <div class="item-section">
                <div class="item-layout">
                    <div class="item-image-col">
                        @if($report->itemImg)
                            <img src="{{ public_path('storage/' . $report->itemImg) }}" alt="Item" class="item-image">
                        @else
                            <div class="no-image">No Image</div>
                        @endif
                    </div>
                    
                    <div class="item-details-col">
                        <div class="item-row">
                            <span class="label">Item Name:</span>
                            <span class="value">{{ $report->itemName }}</span>
                        </div>
                        <div class="item-row">
                            <span class="label">Category:</span>
                            <span class="value">{{ $report->category->categoryName ?? 'N/A' }}</span>
                        </div>
                        <div class="item-row">
                            <span class="label">Location Found:</span>
                            <span class="value">{{ $report->location->locationName ?? 'N/A' }}</span>
                        </div>
                        <div class="item-row">
                            <span class="label">Description:</span>
                            <span class="value">{{ $report->itemDescription }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="info-box">
                <div class="item-row">
                    <span class="label">Date Claimed:</span>
                    <span class="value">{{ $dateClaimed }}</span>
                </div>
            </div>

            <!-- Signatures Side by Side -->
            <div class="signature-section">
                <div class="signature-row">
                    <div class="signature-box">
                        <div class="signature-label">Finder Signature:</div>
                        <div class="signature-line">
                            {{ $finder->userName }}<br>{{ $finder->userEmail }}
                        </div>
                    </div>
                    
                    <div class="signature-box" style="margin-left: 4%;">
                        <div class="signature-label">Owner Signature:</div>
                        <div class="signature-line">
                            {{ $owner->userName }}<br>{{ $owner->userEmail }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            Generated by ReuniFind on {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>
</body>
</html>