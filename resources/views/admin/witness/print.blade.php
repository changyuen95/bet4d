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
                padding: 20px;
            }
            .no-print {
                display: none;
            }
            @page {
                margin: 1cm;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 28px;
            text-transform: uppercase;
        }
        
        .header h2 {
            margin: 5px 0;
            font-size: 20px;
            color: #666;
        }
        
        .draw-info {
            background-color: #f5f5f5;
            padding: 15px;
            margin-bottom: 30px;
            border-left: 4px solid #333;
        }
        
        .draw-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        
        .draw-info strong {
            display: inline-block;
            width: 120px;
        }
        
        .witness-section {
            margin-bottom: 40px;
        }
        
        .witness-section h3 {
            background-color: #333;
            color: white;
            padding: 10px;
            margin-bottom: 20px;
        }
        
        .witness-item {
            border: 2px solid #333;
            padding: 20px;
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .witness-item h4 {
            margin: 0 0 15px 0;
            font-size: 18px;
            color: #333;
        }
        
        .witness-details {
            margin-bottom: 20px;
        }
        
        .witness-details p {
            margin: 5px 0;
            font-size: 14px;
        }
        
        .witness-details strong {
            display: inline-block;
            width: 150px;
        }
        
        .signature-section {
            margin-top: 30px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }
        
        .signature-line {
            margin-top: 50px;
            border-top: 2px solid #333;
            width: 300px;
        }
        
        .signature-label {
            margin-top: 5px;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }
        
        .print-button {
            margin: 20px 0;
            text-align: center;
        }
        
        .print-button button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }
        
        .print-button button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="print-button no-print">
        <button onclick="window.print()">🖨️ Print This Form</button>
        <button onclick="window.close()" style="background-color: #6c757d; margin-left: 10px;">❌ Close</button>
    </div>

    <div class="header">
        <h1>Live Draw Witness Verification Form</h1>
        <h2>Official Record</h2>
    </div>

    <div class="draw-info">
        <p><strong>Draw Number:</strong> {{ $currentDraw->full_draw_no }}</p>
        <p><strong>Draw Date:</strong> {{ \Carbon\Carbon::parse($currentDraw->expired_at)->format('l, d F Y') }}</p>
        <p><strong>Draw Time:</strong> {{ \Carbon\Carbon::parse($currentDraw->expired_at)->format('H:i') }}</p>
        <p><strong>Total Witnesses:</strong> {{ $witnesses->count() }}</p>
        <p><strong>Printed On:</strong> {{ \Carbon\Carbon::now()->format('d F Y H:i:s') }}</p>
    </div>

    <div class="witness-section">
        <h3>Witness Details and Signatures</h3>
        
        @foreach($witnesses as $index => $witness)
        <div class="witness-item">
            <h4>Witness #{{ $index + 1 }}</h4>
            
            <div class="witness-details">
                <p><strong>Full Name:</strong> {{ $witness->name }}</p>
                <p><strong>IC Number:</strong> {{ $witness->formatted_ic }}</p>
                <p><strong>Phone Number:</strong> {{ $witness->phone ?? 'N/A' }}</p>
                @if($witness->address)
                <p><strong>Address:</strong> {{ $witness->address }}</p>
                @endif
            </div>
            
            <div class="signature-section">
                <p><strong>Declaration:</strong></p>
                <p style="font-size: 13px; margin: 10px 0;">
                    I, <strong>{{ $witness->name }}</strong>, hereby declare that I have witnessed the live draw process for Draw No. <strong>{{ $currentDraw->full_draw_no }}</strong> on <strong>{{ \Carbon\Carbon::parse($currentDraw->expired_at)->format('d F Y') }}</strong>. I confirm that the draw was conducted fairly and transparently.
                </p>
                
                <div class="signature-line"></div>
                <div class="signature-label">Witness Signature</div>
                
                <p style="margin-top: 30px; font-size: 13px;">
                    <strong>Date:</strong> _____________________  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <strong>Time:</strong> _____________________
                </p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="footer">
        <p><strong>Important Notice:</strong></p>
        <p>This document serves as an official record of witnesses present during the live draw.</p>
        <p>All information provided must be accurate and signatures must be authentic.</p>
        <p>For verification purposes, please contact the draw management office.</p>
        <p style="margin-top: 20px;">
            <small>Document ID: WF-{{ $currentDraw->full_draw_no }}-{{ \Carbon\Carbon::now()->format('YmdHis') }}</small>
        </p>
    </div>

    <script>
        // Auto-print on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
