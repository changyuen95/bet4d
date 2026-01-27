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
        min-height: 100vh;
        width: 100vw;
        background: linear-gradient(to bottom, #0b7a38, #14a94a);
        color: #fff;
        font-family: Arial, Helvetica, sans-serif;
        overflow-x: hidden;
        overflow-y: auto;
        display: block;
        padding: 0;
    }
    .tv-container {
        width: 96%;
        max-width: 100%;
        margin: 0 auto;
        padding: 1rem;
        display: block;
    }
    .score-board-top {
        background: linear-gradient(to bottom, #e00000, #b50000);
        border-radius: 10px 10px 0 0;
        padding: 1rem 0;
        text-align: center;
        border: 2px solid #990000;
        font-size: 2rem;
        font-weight: 900;
        color: #fff;
        letter-spacing: 4px;
    }
    .marquee-container {
        background: linear-gradient(to right, #ffa500, #ffcc00);
        padding: 1rem 0;
        border-radius: 0 0 10px 10px;
        overflow: hidden;
        font-size: 2rem;
        font-weight: bold;
        color: #000;
    }
    .marquee {
        display: inline-block;
        white-space: nowrap;
        animation: scroll-left 20s linear infinite;
    }
    @keyframes scroll-left {
        0% {
            transform: translateX(100%);
        }
        100% {
            transform: translateX(-100%);
        }
    }
    .header {
        background: linear-gradient(to bottom, #e00000, #b50000);
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
        margin: 1rem 0;
        font-size: 2.2rem;
        font-weight: bold;
        color: #fff;
        display: flex;
        justify-content: space-around;
        align-items: center;
    }
    .draw-info {
        font-size: 2.5rem;
        font-weight: bold;
        color: #ffe600;
    }
    .prize-row {
        background: rgba(0,0,0,0.18);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .prize-label {
        font-size: 2.5rem;
        font-weight: bold;
        width: 30%;
        text-align: left;
    }
    .prize-number {
        font-size: 4rem;
        font-weight: bold;
        text-align: right;
        flex: 1;
    }
    .box {
        background: rgba(0,0,0,0.18);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .box-title {
        text-align: center;
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }
    .number-list {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.5rem 0;
        font-size: 2rem;
        font-weight: bold;
        text-align: center;
    }
    .number-item {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0.2rem 0.5rem;
        gap: 0.8rem;
    }
    .number-index {
        font-size: 1.3rem;
        color: rgba(255,255,255,0.6);
        text-align: right;
        min-width: 1.5rem;
    }
    .consolation-full {
        margin-top: 1rem;
    }
    .consolation-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 0.5rem 1rem;
        justify-items: center;
    }
    .consolation-grid > div {
        text-align: center;
        width: 100%;
        font-size: 3.5rem;
        font-weight: bold;
    }
    .jackpot-table {
        margin-top: 1rem;
        border: 3px solid #d4a000;
        background: linear-gradient(to right, #ffe066, #ffd633);
        border-radius: 10px;
        color: #000;
        padding: 0.8rem;
        font-size: 1.5rem;
    }
    .jackpot-row {
        display: flex;
        flex-direction: row;
        align-items: center;
        padding: 0.3rem 0;
        gap: 1rem;
    }
    .jp-label {
        font-weight: bold;
        font-size: 2rem;
        white-space: nowrap;
    }
    .jp-amount {
        font-size: 2.5rem;
        font-weight: bold;
    }
    .jp-combinations {
        background: rgba(255,255,255,0.3);
        color: #000;
        margin-top: 0.5rem;
        padding: 0.5rem;
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 0.5rem;
    }
    .jp-combo {
        font-size: 1.8rem;
        font-weight: bold;
        text-align: center;
    }
</style>
</head>
<body>

<div class="tv-wrapper">

    <div class="tv-container">

        <!-- SCORE BOARD TOP -->
        <div class="score-board-top">
            <h1>SANDAKAN TURF CLUB</h1>
        </div>

        <!-- MARQUEE -->
        <div class="marquee-container">
            <div class="marquee" id="sb-marquee">
                {{ $result['marquee'] }}
            </div>
        </div>

        <!-- HEADER -->
        <div class="header">
            <div class="draw-info">
                Draw No: <span id="sb-draw">{{ $result['draw_no'] ?? '-' }}</span>
            </div>
            <div class="draw-info">
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

        <!-- SPECIAL NUMBERS - 3 COLUMNS IN ONE BOX -->
        <div style="margin-top: 0.8vh;">
            <div class="box">
                <div class="box-title">SPECIAL PRIZES</div>
                <div class="row">
                    <div class="col-4">
                        <div id="sb-special-left" class="number-list" style="padding-left:0; display: block;">
                            @for ($i = 0; $i < 5; $i++)
                                <div class="number-item" style="margin-bottom: 0.3vh;">
                                    <span class="number-index">{{ $i + 1 }}</span>
                                    <span style="font-size: 5vw; font-weight: bold;">{{ $result['special'][$i] ?? '-' }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>
                    <div class="col-4">
                        <div id="sb-special-middle" class="number-list" style="padding-left:0; display: block;">
                            @for ($i = 5; $i < 9; $i++)
                                <div class="number-item" style="margin-bottom: 0.3vh;">
                                    <span class="number-index">{{ $i + 1 }}</span>
                                    <span style="font-size: 5vw; font-weight: bold;">{{ $result['special'][$i] ?? '-' }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>
                    <div class="col-4">
                        <div id="sb-special-right" class="number-list" style="padding-left:0; display: block;">
                            @for ($i = 9; $i < 13; $i++)
                                <div class="number-item" style="margin-bottom: 0.3vh;">
                                    <span class="number-index">{{ $i + 1 }}</span>
                                    <span style="font-size: 5vw; font-weight: bold;">{{ $result['special'][$i] ?? '-' }}</span>
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
                <div class="jp-label">Jackpot 1:</div>
                <div class="jp-amount" id="sb-jackpot">RM {{ isset($result['jackpot1']) ? number_format($result['jackpot1'], 2) : '-' }}</div>
            </div>
            
            <div class="jp-combinations">
                <div class="jp-combo" id="sb-combo-1">{{ $result['first'] ?? '-' }} + {{ $result['second'] ?? '-' }}</div>
                <div class="jp-combo" id="sb-combo-2">{{ $result['first'] ?? '-' }} + {{ $result['third'] ?? '-' }}</div>
                <div class="jp-combo" id="sb-combo-3">{{ $result['second'] ?? '-' }} + {{ $result['third'] ?? '-' }}</div>
                <div class="jp-combo" id="sb-combo-4">{{ $result['second'] ?? '-' }} + {{ $result['first'] ?? '-' }}</div>
                <div class="jp-combo" id="sb-combo-5">{{ $result['third'] ?? '-' }} + {{ $result['first'] ?? '-' }}</div>
                <div class="jp-combo" id="sb-combo-6">{{ $result['third'] ?? '-' }} + {{ $result['second'] ?? '-' }}</div>
            </div>
            
            <div class="jackpot-row jp-header">
                <div class="jp-label">Jackpot 2:</div>
                <div class="jp-amount" id="sb-jackpot2">RM {{ isset($result['jackpot2']) ? number_format($result['jackpot2'], 2) : '-' }}</div>
            </div>
        </div>

    </div>

</div>

@vite(['resources/js/scoreboard.js'])
</body>
</html>
