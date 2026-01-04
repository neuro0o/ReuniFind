<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 0;
            size: A4 portrait;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: 'Quicksand', 'Segoe UI', sans-serif;
        }
        
        .page-wrapper {
            width: 210mm;
            height: 297mm;
            position: relative;
        }
        
        .tag {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;

            margin: auto;

            @if($size === 'small')
                width: 50mm;
                height: 50mm;
            @elseif($size === 'large')
                width: 100mm;
                height: 100mm;
            @else
                width: 70mm;
                height: 70mm;
            @endif

            border: 1mm dashed #3A5987;
            border-radius: @if($size === 'small') 3mm @elseif($size === 'large') 9mm @else 6mm @endif;
            padding: @if($size === 'small') 5mm @elseif($size === 'large') 7mm @else 6mm @endif;
            box-sizing: border-box;
            text-align: center;
            background: #F1F4FB;
        }

        
        .logo {
            width: @if($size === 'small') 6mm @elseif($size === 'large') 12mm @else 9mm @endif;
            height: auto;
            margin: @if($size === 'small') 0 auto 0.5mm @else 0 auto 1mm @endif;
            display: block;
        }
        
        .title {
            font-size: @if($size === 'small') 2.5mm @elseif($size === 'large') 4.5mm @else 3.5mm @endif;
            font-weight: bold;
            color: #3A5987;
            margin: @if($size === 'small') 1mm 0 2mm @else 1.5mm 0 3mm @endif;
        }
        
        .qr {
            width: @if($size === 'small') 24mm @elseif($size === 'large') 48mm @else 34mm @endif;
            height: @if($size === 'small') 24mm @elseif($size === 'large') 48mm @else 34mm @endif;
            padding: @if($size === 'small') 1.5mm @elseif($size === 'large') 3mm @else 2mm @endif;
            box-sizing: border-box;
            margin: 0 auto @if($size === 'small') 2mm @else 3mm @endif;
            overflow: hidden;
        }
        
        .qr img {
            width: 100%;
            height: 100%;
            display: block;
            border: 0.5mm solid #3A5987;
        }
        
        .text {
            font-size: @if($size === 'small') 2mm @elseif($size === 'large') 3.4mm @else 2.7mm @endif;
            color: #3A5987;
            line-height: 1.2;
            margin: @if($size === 'small') 2mm 0 @else 3mm 0 @endif;
        }
        
        .footer {
            font-size: @if($size === 'small') 1.5mm @elseif($size === 'large') 2.5mm @else 2mm @endif;
            color: #666;
            margin-top: @if($size === 'small') 2mm @else 3mm @endif;
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="tag">
            @if(file_exists(public_path('images/ReuniFind_Logo.png')))
                <img src="{{ public_path('images/ReuniFind_Logo.png') }}" class="logo">
            @endif
            
            <div class="title">Item QR Tag</div>
            
            <div class="qr">
                @php
                    $qrPath = storage_path('app/public/' . $itemTag->tagImg);
                @endphp
                @if(file_exists($qrPath))
                    <img src="{{ $qrPath }}" style="width: 100%; height: 100%; display: block;">
                @else
                    <div style="background: #f0f0f0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 8px;">QR Not Found</div>
                @endif
            </div>
            
            <div class="text">Scan the QR Tag to<br>Reunite the item with its owner!</div>
            
            <div class="footer">Powered by ReuniFind</div>
        </div>
    </div>
</body>
</html>
