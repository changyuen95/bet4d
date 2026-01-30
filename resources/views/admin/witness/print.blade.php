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
            font-size: 14px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 28px;
            text-transform: uppercase;
        }
        
        .draw-info {
            background-color: #f5f5f5;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .draw-info p {
            margin: 3px 0;
            display: inline-block;
            margin-right: 20px;
        }
        
        .results-section {
            margin-bottom: 20px;
            border: 2px solid #333;
            padding: 15px;
        }
        
        .results-section h2 {
            margin: 0 0 12px 0;
            font-size: 18px;
            background-color: #333;
            color: white;
            padding: 6px 10px;
        }
        
        .top-prizes {
            display: flex;
            justify-content: space-around;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .prize-box {
            flex: 1;
            border: 1px solid #333;
            padding: 10px;
            margin: 0 3px;
        }
        
        .prize-label {
            font-size: 13px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .prize-number {
            font-size: 28px;
            font-weight: bold;
            color: #333;
        }
        
        .other-prizes {
            display: flex;
            gap: 8px;
        }
        
        .prize-list {
            flex: 1;
        }
        
        .prize-list h3 {
            font-size: 14px;
            background-color: #666;
            color: white;
            padding: 5px 8px;
            margin: 0 0 8px 0;
        }
        
        .prize-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 3px;
        }
        
        .prize-item {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
        }
        
        .witnesses-section {
            margin-top: 20px;
        }
        
        .witnesses-section h2 {
            font-size: 18px;
            background-color: #333;
            color: white;
            padding: 6px 12px;
            margin: 0 0 12px 0;
        }
        
        .witnesses-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 10px;
        }
        
        .witness-card {
            border: 2px solid #333;
            padding: 20px;
            width: calc(33.333% - 6px);
            box-sizing: border-box;
            page-break-inside: avoid;
        }
        
        .witness-number {
            background-color: #333;
            color: white;
            padding: 4px 10px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 14px;
            display: inline-block;
        }
        
        .manager-number {
            background-color: #666;
            color: white;
            padding: 4px 10px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 14px;
            display: inline-block;
        }
        
        .witness-info p {
            margin: 6px 0;
            font-size: 14px;
        }
        
        .witness-info strong {
            display: inline-block;
            width: 40px;
            font-size: 13px;
        }
        
        .signature-area {
            margin-top: 25px;
            padding-top: 12px;
            border-top: 1px solid #ddd;
        }
        
        .signature-line {
            border-bottom: 1px solid #333;
            margin-top: 50px;
            margin-bottom: 6px;
        }
        
        .signature-label {
            font-size: 11px;
            color: #666;
        }
        
        .managers-grid {
            display: flex;
            gap: 8px;
            justify-content: center;
        }
        
        .manager-card {
            border: 2px solid #666;
            padding: 20px;
            width: calc(50% - 4px);
            box-sizing: border-box;
            page-break-inside: avoid;
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
            margin-top: 25px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 12px;
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
    
    <!-- Draw Results Section -->
    <div class="results-section">
        <h2>🏆 Draw Results - {{ $currentDraw->full_draw_no }}</h2>
        
        <!-- Top 3 Prizes -->
        <div class="top-prizes">
            <div class="prize-box">
                <div class="prize-label">1ST PRIZE</div>
                <div class="prize-number">{{ $results['first']->number ?? '----' }}</div>
            </div>
            <div class="prize-box">
                <div class="prize-label">2ND PRIZE</div>
                <div class="prize-number">{{ $results['second']->number ?? '----' }}</div>
            </div>
            <div class="prize-box">
                <div class="prize-label">3RD PRIZE</div>
                <div class="prize-number">{{ $results['third']->number ?? '----' }}</div>
            </div>
        </div>
        
        <!-- Special and Consolation -->
        <div class="other-prizes">
            <div class="prize-list">
                <h3>SPECIAL PRIZES</h3>
                <div class="prize-grid">
                    @if($results['special']->count() > 0)
                        @foreach($results['special'] as $special)
                            <div class="prize-item">{{ $special->number }}</div>
                        @endforeach
                    @else
                        <div class="prize-item" style="grid-column: 1 / -1;">No results yet</div>
                    @endif
                </div>
            </div>
            
            <div class="prize-list">
                <h3>CONSOLATION PRIZES</h3>
                <div class="prize-grid">
                    @if($results['consolation']->count() > 0)
                        @foreach($results['consolation'] as $consolation)
                            <div class="prize-item">{{ $consolation->number }}</div>
                        @endforeach
                    @else
                        <div class="prize-item" style="grid-column: 1 / -1;">No results yet</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Witnesses Section -->
    <div class="witnesses-section">

        <div class="witnesses-grid">
            @foreach($witnesses as $index => $witness)
            <div class="witness-card">
                <div class="witness-number">WITNESS #{{ $index + 1 }}</div>
                
                <div class="witness-info">
                    <p><strong>Name:</strong> {{ $witness->name }}</p>
                    <p><strong>IC:</strong> {{ $witness->formatted_ic }}</p>
                    <!--<p><strong>Phone:</strong> {{ $witness->phone ?? 'N/A' }}</p>-->
                </div>
                
                <div class="signature-area">
                    <div class="signature-line"></div>
                    <div class="signature-label">Signature & Date</div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Verify Managers Section -->

        <div class="managers-grid">
            <div class="manager-card">
                <div class="manager-number">Recorded By</div>
                
                <div class="witness-info">
                    @if($drawManager && $drawManager->recordedBy)
                        <p><strong>Name:</strong> {{ $drawManager->recordedBy->name }}</p>
                        <p><strong>IC:</strong> {{ $drawManager->recordedBy->formatted_ic }}</p>
                    @else
                        <p><strong>Name:</strong> -</p>
                        <p><strong>IC:</strong> -</p>
                    @endif
                </div>
                
                <div class="signature-area">
                    <div class="signature-line"></div>
                    <div class="signature-label">Signature & Date</div>
                </div>
            </div>
            
            <div class="manager-card">
                <div class="manager-number">Certified By</div>
                
                <div class="witness-info">
                    @if($drawManager && $drawManager->certifiedBy)
                        <p><strong>Name:</strong> {{ $drawManager->certifiedBy->name }}</p>
                        <p><strong>IC:</strong> {{ $drawManager->certifiedBy->formatted_ic }}</p>
                    @else
                        <p><strong>Name:</strong> -</p>
                        <p><strong>IC:</strong> -</p>
                    @endif
                </div>
                
                <div class="signature-area">
                    <div class="signature-line"></div>
                    <div class="signature-label">Signature & Date</div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
