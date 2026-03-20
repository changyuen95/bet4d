<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>STC 4D Ticket</title>
<style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 16px;
            margin: 0;
            padding: 10px;
            -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        }

        .ticket {
            /*background-color:blue;*/
            width: 82mm;
            margin: 0 auto;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .text-center {
            text-align: center;
        }

        .logo {
            width: 130px;
            margin-bottom: 6px;
            padding-right: 20px;
        }

        .line {
            display: grid;
            grid-template-columns: 22px 55px 32px 76px 71px;
            align-items: baseline;
            margin-bottom: 5px;
            padding-left:10px;
            column-gap: 5px;
        }

        .no {
            width: 18px;
            text-align: right;
            position: relative;
            top: -15px;
            padding-left : 7px;
            font-size:13px;
        }

        .number {
            font-size:21px;
            /*font-weight: bold;*/
            font-family : math;
            letter-spacing : 0.6px;
            margin-left:2px;
        }

        .amount, .amount2 {
            font-size : 17px;
            font-family:fangsong;
            text-align: right;
            padding-left: 0;
        }

        .amount2 {
            padding-left: 0;
        }

        .type-badge {
            display: inline-block;
            width: 32px;
            text-align: left;
            font-size: 11px;
            padding-left: 0;
        }

        .header-line {
            display: flex;
            align-items: baseline;
            margin-bottom: 4px;
            padding-left: 134px;
            font-size:13px;
            margin-top:-2px;
        }

        .header-line .big {
            margin-left: 20px;
        }

        .header-line .sml {
            margin-left: 0px;
        }

        .header-line span {
            width: 55px;
            text-align: right;
            /*font-weight: bold;*/
            padding-right:22px;
        }

        .type-line {
            display: flex;
            align-items: baseline;
            padding-left: 16px;
            margin-top: -2px;
            margin-bottom: 6px;
            font-size: 15px;
        }

        .type-line .label {
            min-width: 80px;
            font-weight: normal;
        }

        .subtotal{
            white-space: pre;
            font-family: fangsong;
            font-size : 15px;
            line-height:6px;
        }

        .preformatted {
            white-space: pre-line;
            font-family: fangsong;
            font-size : 15px;
            margin: 0;
            line-height:7px;
            margin-left:10px;
        }

        .preformatted.summary-section {
            margin-top: -12px;
        }
        
         #barcode {
            width: 100%;
            image-rendering: pixelated;
        }
        
        .barcode-wrapper {
            width: 165px;
            text-align: center;
        }

        .barcode-text {
            display: block;
            width: 100%;
            text-align: center;
            margin-top: -4px;
            letter-spacing: 0px;
            font-size: 15px;
            white-space: nowrap;
            overflow: visible;
            word-break: keep-all;
            text-transform: uppercase;
        }

        .summary-line {
            display: flex;
            justify-content: space-between;
            font-family: fangsong;
            font-size: 15px;
            margin: 0;
            line-height:7px;
            padding-right: 26px;
            
        }

        .summary-block {
            margin: 0;
        }

        .summary-amount {
            width: 60px;
            text-align: right;
        }

        .draw-line {
            display: flex;
            align-items: baseline;
            gap: 18px;
            margin: 0;
            line-height: 7px;
        }

        .draw-line .segment {
            display: inline-block;
        }

        @media print {
            @page {
                size: auto;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="ticket" id="ticketArea">
          <div class="text-center" style="font-weight: bold; font-size: 16px;margin-top:-12px;margin-bottom:-10px">
              <img src="https://fortknox.group/images/stclogo.jpg" alt="STC 4D" class="logo">


        </div>
        <div class="" style="padding-left:80px">
            <div class="barcode-wrapper">
                <canvas id="barcodeCanvas"></canvas>
                <div class="barcode-text">{{ $ticket->serial_number }}</div>
            </div>
        </div>

        <div style="height:2px;"></div>

        {{-- E-BOX + BIG / SML Header --}}
        <div class="type-line">
            <div class="label">
                @if(strtoupper($ticket->ticket_type ?? '') === 'E-BOX')
                    E-BOX
                @else
                    &nbsp;
                @endif
            </div>
            <div class="header-line" style="margin-bottom:0; margin-top:0; padding-left:38px;">
                <span class="big">BIG</span>
                <span class="sml">SML</span>
            </div>
        </div>

        {{-- Ticket Rows --}}
        @foreach($ticket->numbers as $index => $number)
        
            @if($ticket->ticket_type == 'Straight')
        
                <div class="line">
                    <div class="no">{{ $index + 1 }}.</div>
                    <div class="number">{{ $number->number  }}</div>
                    <span class="type-badge"></span>
                    <div class="amount">RM{{ number_format($number->amount_big, 0, '.', '') }}</div>
                    <div class="amount2">RM{{ number_format($number->amount_small, 0, '.', '') }}</div>
                </div>    
            
            @else
            
            <div class="line">
                <div class="no">{{ $index + 1 }}.</div>
                <div class="number">{{ $number->number  }}</div>
                @if(strtoupper($ticket->ticket_type ?? '') === 'E-BOX')
                    <span class="type-badge">E24</span>
                @else
                    <span class="type-badge">Box</span>
                @endif
                <div class="amount">RM{{ number_format($number->amount_big, 0, '.', '') }}</div>
                <div class="amount2">RM{{ number_format($number->amount_small, 0, '.', '') }}</div>
            </div>
            
            @endif
        
        
        @endforeach

        {{-- Draw & Print Info --}}
        <div class="preformatted">
            <div class="draw-line">
                <span class="segment">DRAW #{{ ($ticket->draw && $ticket->draw->draw_no) ? str_pad($ticket->draw->draw_no, 4, '0', STR_PAD_LEFT) : '----' }}/25</span>
                <span class="segment">{{ strtoupper(optional(\Carbon\Carbon::parse($ticket->draw->expired_at))->format('D') ?? '--') }}</span>
                <span class="segment">{{ strtoupper(optional(\Carbon\Carbon::parse($ticket->draw->expired_at))->format('d-M-y') ?? '--') }}</span>
            </div>
            <div class="draw-line">
                <span class="segment">{{ $ticket->outlet_number }}</span>
                <span class="segment">{{ strtoupper($ticket->printed_at->format('D')) }}</span>
                <span class="segment">{{ strtoupper($ticket->printed_at->format('d-M-y')) }}</span>
                <span class="segment">{{ $ticket->printed_at->format('H:i:s') }}</span>
            </div>
        </div>

        {{-- Footer Summary --}}
        @php
            if($ticket->subtotal && $ticket->subtotal > 0){
            $subtotal = $ticket->subtotal;
            }else{
                $subtotal = $ticket->numbers->sum('amount_big') + $ticket->numbers->sum('amount_small');
            }
            
            $tax = $subtotal * 0.15;
            $total = $subtotal + $tax;
        @endphp

        <div class="preformatted summary-section">
            <div class="summary-block">
                <div class="summary-line">
                    <span style="padding-left:102px">SUBTOTAL</span>
                    <span class="summary-amount">RM{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="summary-line">
                    <span style="padding-left:7px">STATE SALES TAX(15.00%)</span>
                    <span class="summary-amount">RM{{ number_format($tax, 2) }}</span>
                </div>
                <div class="summary-line">
                    <span style="padding-left:122px">TOTAL</span>
                    <span class="summary-amount">RM{{ number_format($total, 2) }}</span>
                </div>
            </div>
        </div>
</div>
<div style="text-align: center; margin-top: 10px;">
    <button onclick="printTicket()" style="padding:6px 14px; font-size:14px;">🖨️ Print Ticket</button>
    <button onclick="window.location.href='/ticket_printing/create'" style="padding:6px 14px; font-size:14px; margin-left:10px;">➕ Create new</button>
    <button onclick="closeWindow()" style="padding:6px 14px; font-size:14px; margin-left:10px;">❌ Close</button>
</div>

<script>
function closeWindow() {
    // Try to close tab safely — browser may block if not user-initiated
    window.open('', '_self');
    window.close();
}
</script>

    

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qz-tray/qz-tray.js"></script>

    <script>
        // Init barcode
        window.addEventListener('DOMContentLoaded', () => {
            JsBarcode("#barcodeCanvas", {!! json_encode($ticket->serial_number) !!}, {
            format: "CODE128",
            displayValue: false,
            width: 2,
            height: 105,
            margin: 0
        });

        // Convert canvas to image and insert
        const canvas = document.getElementById("barcodeCanvas");
        const img = document.createElement("img");
        img.src = canvas.toDataURL("image/png");
        img.style.width = "165px";

        const target = canvas.parentNode;
        target.insertBefore(img, canvas);
        canvas.remove();
        });

        async function printTicket() {
    try {
        // Scroll to top to ensure consistent capture position
        window.scrollTo(0, 0);

        const ticket = document.getElementById("ticketArea");

        // Temporarily remove auto margin and fix position for consistent capture
        const origMargin = ticket.style.margin;
        const origPosition = ticket.style.position;
        ticket.style.margin = '0';
        ticket.style.position = 'relative';

        // Small delay to let browser reflow
        await new Promise(resolve => setTimeout(resolve, 100));

        const canvas = await html2canvas(ticket, {
            scale: 4,
            x: 0,
            y: 0,
            width: ticket.scrollWidth,
            height: ticket.scrollHeight,
            windowWidth: ticket.scrollWidth,
            scrollX: 0,
            scrollY: 0,
            useCORS: true,
        });

        // Restore original styles
        ticket.style.margin = origMargin;
        ticket.style.position = origPosition;

        const ctx = canvas.getContext("2d");
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);

        // ðŸ”¥ Threshold: force black/white only
        for (let i = 0; i < imageData.data.length; i += 4) {
            const avg = (imageData.data[i] + imageData.data[i + 1] + imageData.data[i + 2]) / 3;
            const val = avg < 180 ? 0 : 255; // tweak threshold (160â€“200) for darker/lighter
            imageData.data[i] = imageData.data[i + 1] = imageData.data[i + 2] = val;
        }
        ctx.putImageData(imageData, 0, 0);

        const imgData = canvas.toDataURL("image/png");

        if (!qz.websocket.isActive()) {
            await qz.websocket.connect();
        }
        const printer = await qz.printers.find("CITIZEN CT-S651II");
        const config = qz.configs.create(printer);

        const data = [
            { type: 'html', format: 'plain', data: `<img src="${imgData}" style="width:80mm;" />` }
            ];

        await qz.print(config, data);
        console.log("Printed successfully");
    } catch (err) {
        console.error("Printing Error:", err);
    }
}

    </script>

</body>
</html>
