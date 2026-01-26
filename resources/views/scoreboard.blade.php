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
        max-width: 2400px;
        height: auto;
        display: flex;
        flex-direction: column;
        transform: scale(var(--ui-scale));
        transform-origin: center center;
    }

    /* SCORE BOARD TOP */
    .score-board-top {
        background: linear-gradient(to bottom, #a0a0a0, #808080);
        border-radius: 1vw 1vw 0 0;
        padding: 0.5vh 2vw;
        text-align: center;
        border: 2px solid #666;
    }

    .score-board-top h2 {
        font-size: 1.2vw;
        margin: 0;
        font-weight: bold;
        color: #1a1a1a;
        letter-spacing: 0.3vw;
    }

    /* MARQUEE */
    .marquee-container {
        background: linear-gradient(to right, #ffa500, #ffcc00);
        padding: 0.5vh 0;
        overflow: hidden;
        border-left: 2px solid #555;
        border-right: 2px solid #555;
        border-radius: 0 0 0.8vw 0.8vw;
    }

    .marquee {
        display: inline-block;
        white-space: nowrap;
        animation: scroll-left 20s linear infinite;
        font-size: 1vw;
        font-weight: bold;
        color: #000;
    }

    @keyframes scroll-left {
        0% {
            transform: translateX(100%);
        }
        100% {
            transform: translateX(-100%);
        }
    }

    /* HEADER */
    .header {
        background: linear-gradient(to bottom, #e00000, #b50000);
        border-radius: 1vw;
        padding: 0.5vh 1.5vw;
        text-align: center;
        margin-bottom: 0.5vh;
        margin-top: 1vh;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header h1 {
        font-size: 1.6848vw;
        letter-spacing: 0.081vw;
        margin: 0;
        font-weight: bold;
        line-height: 1.2;
        flex: 1;
        text-align: center;
        white-space: nowrap;
    }

    .draw-info {
        font-size: 0.95vw;
        font-weight: bold;
        color: #ffe600;
        white-space: nowrap;
    }

    .draw-info-left {
        text-align: left;
        flex: 1;
    }

    .draw-info-right {
        text-align: right;
        flex: 1;
    }

    /* PRIZE ROWS */
    .prize-row {
        background: rgba(0,0,0,0.18);
        border-radius: 1vw;
        padding: 0.5vh 1.5vw;
        margin-bottom: 0.4vh;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .prize-label {
        font-size: 1.2312vw;
        font-weight: bold;
        width: 20%;
        padding-left: 1vw;
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
        padding: 0.6vh 1.5vw;
        height: 100%;
    }

    .box-title {
        text-align: center;
        font-size: 1.0368vw;
        font-weight: bold;
        margin-bottom: 0.6vh;
    }

    .number-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.3vh 2vw;
        font-size: 1.4vw;
        font-weight: bold;
        text-align: center;
    }

    .number-item {
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 0.5vw;
        align-items: center;
    }

    .number-index {
        font-size: 0.8vw;
        color: rgba(255,255,255,0.6);
        text-align: right;
        padding-left: 1.5vw;
    }

    /* JACKPOT */
    .jackpot-table {
        margin-top: 0.5vh;
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
        padding: 0.5vh 1vw;
        font-size: 1.0368vw;
        font-weight: bold;
        display: flex;
        align-items: center;
        border-right: 2px solid #d4a000;
    }

    .jp-amount {
        width: 70%;
        padding: 0.5vh 1vw;
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
        padding: 0.5vh 0.8vw;
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
        padding: 0.5vh 1vw;
        font-size: 0.8424vw;
        text-align: center;
        justify-content: center;
        font-style: italic;
    }

    .consolation-full {
        margin-top: 0.5vh;
    }

    .consolation-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.3vh 0.8vw;
        justify-items: center;
    }

    .consolation-grid > div {
        text-align: center;
        width: 100%;
        font-size: 1.4vw;
        font-weight: bold;
    }

    .consolation-grid > div:nth-child(9) {
        grid-column: 2;
    }

    .consolation-grid > div:nth-child(10) {
        grid-column: 3;
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

        <!-- SCORE BOARD TOP -->
        <div class="score-board-top">
            <h2>SCORE BOARD</h2>
        </div>

        <!-- MARQUEE -->
        <div class="marquee-container">
            <div class="marquee" id="sb-marquee">
                {{ $result['marquee'] ?? 'Welcome to STC 4D Lottery • Good Luck • Check your numbers regularly' }}
            </div>
        </div>

        <!-- HEADER -->
        <div class="header">
            <div class="draw-info draw-info-left">
                Draw No: <span id="sb-draw">{{ $result['draw_no'] ?? '-' }}</span>
            </div>
            <h1 id="sb-title">{{ $result['title'] ?? 'WINNING RESULTS' }}</h1>
            <div class="draw-info draw-info-right">
                Draw Date: <span id="sb-date">{{ $result['date'] ?? '-' }}</span>
            </div>
        </div>

        <!-- PRIZES - 3 COLUMNS -->
        <div class="row" style="margin-top: 0.5vh;">
            <div class="col-4">
                <div class="prize-row">
                    <div class="prize-label">1ST</div>
                    <div class="prize-number" id="sb-first">{{ $result['first'] ?? '-' }}</div>
                </div>
            </div>
            <div class="col-4">
                <div class="prize-row">
                    <div class="prize-label">2ND</div>
                    <div class="prize-number" id="sb-second">{{ $result['second'] ?? '-' }}</div>
                </div>
            </div>
            <div class="col-4">
                <div class="prize-row">
                    <div class="prize-label">3RD</div>
                    <div class="prize-number" id="sb-third">{{ $result['third'] ?? '-' }}</div>
                </div>
            </div>
        </div>

        <!-- SPECIAL NUMBERS - 2 COLUMNS IN ONE BOX -->
        <div style="margin-top: 0.8vh;">
            <div class="box">
                <div class="box-title">SPECIAL PRIZE</div>
                <div class="row">
                    <div class="col-6">
                        <div id="sb-special-left" class="number-list" style="padding-left:0; display: block;">
                            @for ($i = 0; $i < 7; $i++)
                                <div class="number-item" style="margin-bottom: 0.3vh;">
                                    <span class="number-index">{{ $i + 1 }}</span>
                                    <span style="font-size: 1.4vw; font-weight: bold;">{{ $result['special'][$i] ?? '-' }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>
                    <div class="col-6">
                        <div id="sb-special-right" class="number-list" style="padding-left:0; display: block;">
                            @for ($i = 7; $i < 13; $i++)
                                <div class="number-item" style="margin-bottom: 0.3vh;">
                                    <span class="number-index">{{ $i + 1 }}</span>
                                    <span style="font-size: 1.4vw; font-weight: bold;">{{ $result['special'][$i] ?? '-' }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONSOLATION PRIZES FULL WIDTH -->
        <div class="consolation-full">
            <div class="box">
                <div class="box-title">CONSOLATION PRIZES</div>
                <div class="consolation-grid" id="sb-consolation">
                    @for ($i = 0; $i < 10; $i++)
                        <div>{{ $result['consolation'][$i] ?? '-' }}</div>
                    @endfor
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

@vite(['resources/js/scoreboard.js'])
</body>
</html>
