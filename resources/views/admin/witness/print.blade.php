<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Witness Form - Draw {{ $currentDraw->full_draw_no }}</title>
    <style>
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            .no-print {
                display: none;
            }
            @page {
                margin: 0.5cm;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            line-height: 1.4;
            color: #333;
            font-size: 12px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
        }
        
        .draw-info {
            background-color: #f5f5f5;
            padding: 8px;
            margin-bottom: 15px;
            font-size: 11px;
        }
        
        .draw-info p {
            margin: 3px 0;
            display: inline-block;
            margin-right: 20px;
        }
        
        .witnesses-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .witness-card {
            border: 2px solid #333;
            padding: 12px;
            width: calc(33.333% - 7px);
            box-sizing: border-box;
            page-break-inside: avoid;
        }
        
        .witness-number {
            background-color: #333;
            color: white;
            padding: 3px 10px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 11px;
            display: inline-block;
        }
        
        .witness-info p {
            margin: 5px 0;
            font-size: 11px;
        }
        
        .witness-info strong {
            display: inline-block;
            width: 80px;
            font-size: 10px;
        }
        
        .signature-area {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
        
        .signature-line {
            border-bottom: 1px solid #333;
            margin-top: 30px;
            margin-bottom: 3px;
        }
        
        .signature-label {
            font-size: 9px;
            color: #666;
        }
        
        .print-button {
            position: fixed;
            top: 10px;
            right: 10px;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
        }
        
        .print-button:hover {
            background-color: #45a049;
        }
        
        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">🖨️ Print</button>
    
    <div class="header">
        <h1>Live Draw Witness Form</h1>
    </div>
    
    <div class="draw-info">
        <p><strong>Draw:</strong> {{ $currentDraw->full_draw_no }}</p>
        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($currentDraw->expired_at)->format('d/m/Y H:i') }}</p>
        <p><strong>Witnesses:</strong> {{ $witnesses->count() }}</p>
        <p><strong>Printed:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>
    
    <div class="witnesses-grid">
        @foreach($witnesses as $index => $witness)
        <div class="witness-card">
            <div class="witness-number">WITNESS #{{ $index + 1 }}</div>
            
            <div class="witness-info">
                <p><strong>Name:</strong> {{ $witness->name }}</p>
                <p><strong>IC:</strong> {{ $witness->formatted_ic }}</p>
                <p><strong>Phone:</strong> {{ $witness->phone ?? 'N/A' }}</p>
            </div>
            
            <div class="signature-area">
                <div class="signature-line"></div>
                <div class="signature-label">Signature & Date</div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="footer">
        <p>This is an official witness verification document for Draw {{ $currentDraw->full_draw_no }}</p>
    </div>
</body>
</html>
