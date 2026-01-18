<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>STC 4D Winning Results</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    :root {
        --ui-scale: 1;
    }
    * {
        box-sizing: border-box;
    }
    
    body {
        margin: 0;
        height: 100vh;
        width: 100vw;
        background: linear-gradient(to right, #0b7a38, #14a94a);
        color: #fff;
        font-family: Arial, Helvetica, sans-serif;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .tv-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }

    .tv-container {
        width: 75%;
        max-width: 1200px;
        height: auto;
        display: flex;
        flex-direction: column;
        transform: scale(var(--ui-scale));
        transform-origin: center center;
    }

    /* HEADER */
    .header {
        background: linear-gradient(to bottom, #e00000, #b50000);
        border-radius: 1vw;
        padding: 1vh 2vw;
        text-align: center;
        margin-bottom: 0.8vh;
    }

    .header h1 {
        font-size: 1.6848vw;
        letter-spacing: 0.081vw;
        margin: 0;
        font-weight: bold;
        line-height: 1.2;
    }

    .draw-info {
        font-size: 0.8424vw;
        font-weight: bold;
        color: #ffe600;
        margin-top: 1vh;
    }

    /* PRIZE ROWS */
    .prize-row {
        background: rgba(0,0,0,0.18);
        border-radius: 1vw;
        padding: 0.8vh 2vw;
        margin-bottom: 0.6vh;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .prize-label {
        font-size: 1.2312vw;
        font-weight: bold;
        width: 35%;
        padding-left: 6vw;
    }

    .prize-number {
        font-size: 2.1384vw;
        font-weight: bold;
        text-align: center;
        letter-spacing: 0.162vw;
        flex: 1;
    }

    /* BOXES */
    .box {
        background: rgba(0,0,0,0.18);
        border-radius: 1vw;
        padding: 1vh 2vw;
        height: 100%;
    }

    .box-title {
        text-align: center;
        font-size: 1.0368vw;
        font-weight: bold;
        margin-bottom: 1vh;
    }

    .number-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5vh 3vw;
        font-size: 1.0368vw;
        font-weight: bold;
        text-align: center;
    }

    /* JACKPOT */
    .jackpot-table {
        margin-top: 0.8vh;
        border: 2px solid #d4a000;
        background: linear-gradient(to right, #ffe066, #ffd633);
        overflow: hidden;
        border-radius: 1vw;
        color: #000;
    }

    .jackpot-row {
        display: flex;
        border-bottom: 2px solid #d4a000;
    }

    .jackpot-row:last-child {
        border-bottom: none;
    }

    .jp-header {
        background: rgba(0,0,0,0.2);
        color: #000;
    }

    .jp-label {
        width: 30%;
        padding: 0.8vh 1.5vw;
        font-size: 1.0368vw;
        font-weight: bold;
        display: flex;
        align-items: center;
        border-right: 2px solid #d4a000;
    }

    .jp-amount {
        width: 70%;
        padding: 0.8vh 1.5vw;
        font-size: 1.35vw;
        font-weight: bold;
        display: flex;
        align-items: center;
    }

    .jp-amount-red {
        color: #000;
    }

    .jp-combinations {
        background: rgba(255,255,255,0.3);
        color: #000;
    }

    .jp-combo {
        flex: 1;
        padding: 0.8vh 1vw;
        font-size: 1.0368vw;
        font-weight: bold;
        text-align: center;
        border-right: 2px solid #d4a000;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .jp-combo:last-child {
        border-right: none;
    }

    .jp-description {
        background: rgba(0,0,0,0.1);
        color: #000;
        padding: 0.8vh 1.5vw;
        font-size: 0.8424vw;
        text-align: center;
        justify-content: center;
        font-style: italic;
    }
    
    /* Responsive adjustments for very large screens */
    @media (min-width: 1920px) {
        .header h1 {
            font-size: 3.5vw;
        }
        .draw-info {
            font-size: 1.8vw;
        }
        .prize-label {
            font-size: 2.5vw;
        }
        .prize-number {
            font-size: 4.5vw;
        }
        .box-title {
            font-size: 2.2vw;
        }
        .number-list {
            font-size: 2.2vw;
        }
        .jackpot-title {
            font-size: 2vw;
        }
        .jackpot-amount {
            font-size: 3.5vw;
        }
    }
</style>
</head>
<body>

<div class="tv-wrapper">

    <div class="tv-container">

        <!-- HEADER -->
        <div class="header">
            <h1 id="sb-title">{{ $result['title'] ?? 'WINNING RESULTS' }}</h1>
            <div class="draw-info">
                Draw No: <span id="sb-draw">{{ $result['draw_no'] ?? '-' }}</span>
                | <span id="sb-date">{{ $result['date'] ?? '-' }}</span>
            </div>
        </div>

        <!-- PRIZES -->
        <div>

            <div class="prize-row">
                <div class="prize-label">1ST PRIZE</div>
                <div class="prize-number" id="sb-first">{{ $result['first'] ?? '-' }}</div>
            </div>

            <div class="prize-row">
                <div class="prize-label">2ND PRIZE</div>
                <div class="prize-number" id="sb-second">{{ $result['second'] ?? '-' }}</div>
            </div>

            <div class="prize-row">
                <div class="prize-label">3RD PRIZE</div>
                <div class="prize-number" id="sb-third">{{ $result['third'] ?? '-' }}</div>
            </div>

        </div>

        <!-- SPECIAL & CONSOLATION -->
        <div class="row" style="margin-top: 0.8vh;">

            <div class="col-6">
                <div class="box">
                    <div class="box-title">SPECIAL PRIZE</div>
                    <ul class="number-list" id="sb-special" style="list-style:none;padding-left:0;">
                        @for ($i = 0; $i < 10; $i++)
                            <li>{{ $result['special'][$i] ?? '-' }}</li>
                        @endfor
                    </ul>
                </div>
            </div>

            <div class="col-6">
                <div class="box">
                    <div class="box-title">CONSOLATION PRIZE</div>
                    <ul class="number-list" id="sb-consolation" style="list-style:none;padding-left:0;">
                        @for ($i = 0; $i < 10; $i++)
                            <li>{{ $result['consolation'][$i] ?? '-' }}</li>
                        @endfor
                    </ul>
                </div>
            </div>

        </div>

        <!-- JACKPOT -->
        <div class="jackpot-table">
            <div class="jackpot-row jp-header">
                <div class="jp-label">Jackpot 1</div>
                <div class="jp-amount" id="sb-jackpot">RM {{ isset($result['jackpot1']) ? number_format($result['jackpot1'], 2) : '-' }}</div>
            </div>
            
            <div class="jackpot-row jp-combinations">
                <div class="jp-combo" id="sb-combo-1">{{ $result['first'] ?? '-' }} + {{ $result['second'] ?? '-' }}</div>
                <div class="jp-combo" id="sb-combo-2">{{ $result['first'] ?? '-' }} + {{ $result['third'] ?? '-' }}</div>
                <div class="jp-combo" id="sb-combo-3">{{ $result['second'] ?? '-' }} + {{ $result['third'] ?? '-' }}</div>
            </div>
            
            <div class="jackpot-row jp-combinations">
                <div class="jp-combo" id="sb-combo-4">{{ $result['second'] ?? '-' }} + {{ $result['first'] ?? '-' }}</div>
                <div class="jp-combo" id="sb-combo-5">{{ $result['third'] ?? '-' }} + {{ $result['first'] ?? '-' }}</div>
                <div class="jp-combo" id="sb-combo-6">{{ $result['third'] ?? '-' }} + {{ $result['second'] ?? '-' }}</div>
            </div>
            
            <div class="jackpot-row jp-header">
                <div class="jp-label">Jackpot 2</div>
                <div class="jp-amount jp-amount-red" id="sb-jackpot2">RM {{ isset($result['jackpot2']) ? number_format($result['jackpot2'], 2) : '-' }}</div>
            </div>
            
            <div class="jackpot-row jp-description">
                Matches any 1 of top 3 and any 1 of special winning numbers
            </div>
        </div>

    </div>

</div>

</body>
@vite(['resources/js/scoreboard.js'])
</html>
