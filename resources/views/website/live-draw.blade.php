@extends('website.layouts.app')

@section('title', 'Live Draw Results - STC Lucky 4D Malaysia')
@section('meta_description', 'Watch STC Lucky 4D live draw results. Updated in real-time with WebSocket.')

@section('content')
<section class="pt-[100px] pb-20 min-h-screen" style="background-color: #0a0a15;">
    <div class="max-w-5xl mx-auto px-4 sm:px-8">

        {{-- Page Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full mb-4" style="background: rgba(2,138,54,0.1); border: 1px solid rgba(2,138,54,0.2); color: #03c050; font-size: 0.75rem; font-weight: 600; letter-spacing: 2px; text-transform: uppercase;">
                🔴 Latest Results
            </div>
            <h1 class="text-[clamp(2rem,5vw,3rem)] font-black text-white mb-2" style="font-family: 'Rubik', sans-serif;">
                Live Draw <span class="bg-clip-text text-transparent" style="background: linear-gradient(135deg,#03c050,#028a36); -webkit-background-clip: text;">Results</span>
            </h1>
        </div>

        {{-- ==================== MAIN RESULT CARD ==================== --}}
        <div class="rounded-2xl overflow-hidden border border-[#028a36]/20" style="background: rgba(0,0,0,0.3);">

            {{-- Green Header --}}
            <div class="px-6 py-5 text-center" style="background: linear-gradient(135deg, #028a36, #03c050);">
                <div class="text-white font-black text-2xl tracking-wide mb-1" style="font-family: 'Rubik', sans-serif;">WINNING RESULTS</div>
                <div class="text-white/80 text-sm">
                    Draw number: <span class="font-bold text-white" id="ld-draw-no">{{ $result['draw_no'] }}</span>
                    <span class="mx-1">|</span>
                    Draw date: <span class="font-bold text-white" id="ld-draw-date">{{ $result['date'] }}</span>
                </div>
            </div>

            {{-- ===== Top 3 Prizes Table ===== --}}
            <div class="divide-y divide-white/5">
                {{-- Table Header --}}
                <div class="grid grid-cols-4 px-6 py-3" style="background: rgba(255,255,255,0.03);">
                    <div></div>
                    <div class="text-center text-gray-400 text-xs font-semibold uppercase tracking-wider">Big</div>
                    <div class="text-center text-gray-400 text-xs font-semibold uppercase tracking-wider">Small</div>
                    <div></div>
                </div>

                {{-- 1st Prize --}}
                <div class="grid grid-cols-4 items-center px-6 py-4" style="background: rgba(0,0,0,0.2);">
                    <div class="text-[#03c050] font-bold text-sm uppercase tracking-wide">1st Prize</div>
                    <div class="text-center text-gray-300 text-sm">RM {{ number_format($prizeList->big1st ?? 2500) }}</div>
                    <div class="text-center text-gray-300 text-sm">RM {{ number_format($prizeList->small1st ?? 3500) }}</div>
                    <div class="text-right">
                        <span class="text-white font-black text-3xl sm:text-4xl tracking-[4px]" style="font-family: 'Rubik', sans-serif;" id="ld-first">{{ $result['first'] }}</span>
                    </div>
                </div>

                {{-- 2nd Prize --}}
                <div class="grid grid-cols-4 items-center px-6 py-4" style="background: rgba(0,0,0,0.15);">
                    <div class="text-[#03c050] font-bold text-sm uppercase tracking-wide">2nd Prize</div>
                    <div class="text-center text-gray-300 text-sm">RM {{ number_format($prizeList->big2nd ?? 1000) }}</div>
                    <div class="text-center text-gray-300 text-sm">RM {{ number_format($prizeList->small2nd ?? 2000) }}</div>
                    <div class="text-right">
                        <span class="text-white font-black text-3xl sm:text-4xl tracking-[4px]" style="font-family: 'Rubik', sans-serif;" id="ld-second">{{ $result['second'] }}</span>
                    </div>
                </div>

                {{-- 3rd Prize --}}
                <div class="grid grid-cols-4 items-center px-6 py-4" style="background: rgba(0,0,0,0.2);">
                    <div class="text-[#03c050] font-bold text-sm uppercase tracking-wide">3rd Prize</div>
                    <div class="text-center text-gray-300 text-sm">RM {{ number_format($prizeList->big3rd ?? 500) }}</div>
                    <div class="text-center text-gray-300 text-sm">RM {{ number_format($prizeList->small3rd ?? 1000) }}</div>
                    <div class="text-right">
                        <span class="text-white font-black text-3xl sm:text-4xl tracking-[4px]" style="font-family: 'Rubik', sans-serif;" id="ld-third">{{ $result['third'] }}</span>
                    </div>
                </div>
            </div>

            {{-- ===== Special & Consolation ===== --}}
            <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-white/5">
                {{-- Special Prizes --}}
                <div class="p-6" style="background: rgba(0,0,0,0.15);">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-gray-400 text-xs font-bold uppercase tracking-[2px]">Special Prize (RM{{ number_format($prizeList->big_special ?? 180) }})</div>
                    </div>
                    <div class="grid grid-cols-3 gap-x-4 gap-y-2" id="ld-special">
                        @for ($i = 0; $i < 13; $i++)
                            <div class="flex items-center gap-2">
                                <span class="text-[#03c050] text-xs font-bold min-w-[20px] text-right">{{ $i + 1 }}.</span>
                                <span class="text-white font-bold text-lg tracking-[2px]" style="font-family: 'Rubik', sans-serif;">{{ $result['special'][$i] ?? '-' }}</span>
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- Consolation Prizes --}}
                <div class="p-6" style="background: rgba(0,0,0,0.1);">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-gray-400 text-xs font-bold uppercase tracking-[2px]">Consolation Prize (RM{{ number_format($prizeList->big_consolation ?? 60) }})</div>
                    </div>
                    <div class="grid grid-cols-3 gap-x-4 gap-y-2" id="ld-consolation">
                        @for ($i = 0; $i < 10; $i++)
                            <div class="text-center">
                                <span class="text-white font-bold text-lg tracking-[2px]" style="font-family: 'Rubik', sans-serif;">{{ $result['consolation'][$i] ?? '-' }}</span>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            {{-- ===== Jackpot 1 ===== --}}
            <div>
                {{-- Jackpot 1 Header --}}
                <div class="flex items-center justify-between px-6 py-3" style="background: linear-gradient(135deg, #dc2626, #b91c1c);">
                    <div class="text-white font-black text-sm uppercase tracking-[2px]" style="font-family: 'Rubik', sans-serif;">Jackpot 1</div>
                    <div class="text-[#03c050] font-black text-xl" style="font-family: 'Rubik', sans-serif;" id="ld-jackpot1">
                        RM {{ number_format($result['jackpot1'], 2) }}
                    </div>
                </div>

                {{-- Jackpot 1 Combinations --}}
                @if($result['first'] !== '-' && $result['second'] !== '-' && $result['third'] !== '-')
                <div class="grid grid-cols-3 divide-x divide-white/5" id="ld-combos" style="background: rgba(0,0,0,0.25);">
                    <div class="px-4 py-3 text-center text-white font-bold text-sm tracking-wide" style="font-family: 'Rubik', sans-serif;">
                        <span class="ld-combo">{{ $result['first'] }} + {{ $result['second'] }}</span>
                    </div>
                    <div class="px-4 py-3 text-center text-white font-bold text-sm tracking-wide" style="font-family: 'Rubik', sans-serif;">
                        <span class="ld-combo">{{ $result['first'] }} + {{ $result['third'] }}</span>
                    </div>
                    <div class="px-4 py-3 text-center text-white font-bold text-sm tracking-wide" style="font-family: 'Rubik', sans-serif;">
                        <span class="ld-combo">{{ $result['second'] }} + {{ $result['third'] }}</span>
                    </div>
                    <div class="px-4 py-3 text-center text-white font-bold text-sm tracking-wide" style="font-family: 'Rubik', sans-serif;">
                        <span class="ld-combo">{{ $result['second'] }} + {{ $result['first'] }}</span>
                    </div>
                    <div class="px-4 py-3 text-center text-white font-bold text-sm tracking-wide" style="font-family: 'Rubik', sans-serif;">
                        <span class="ld-combo">{{ $result['third'] }} + {{ $result['first'] }}</span>
                    </div>
                    <div class="px-4 py-3 text-center text-white font-bold text-sm tracking-wide" style="font-family: 'Rubik', sans-serif;">
                        <span class="ld-combo">{{ $result['third'] }} + {{ $result['second'] }}</span>
                    </div>
                </div>
                @endif

                {{-- Jackpot 2 Header --}}
                <div class="flex items-center justify-between px-6 py-3" style="background: linear-gradient(135deg, #dc2626, #b91c1c);">
                    <div class="text-white font-black text-sm uppercase tracking-[2px]" style="font-family: 'Rubik', sans-serif;">Jackpot 2</div>
                    <div class="text-[#03c050] font-black text-xl" style="font-family: 'Rubik', sans-serif;" id="ld-jackpot2">
                        RM {{ number_format($result['jackpot2'], 2) }}
                    </div>
                </div>
            </div>

        </div>{{-- END MAIN CARD --}}

        {{-- Live indicator --}}
        <div class="mt-6 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold" style="background: rgba(2,138,54,0.1); border: 1px solid rgba(2,138,54,0.2); color: #03c050;">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#03c050] opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#03c050]"></span>
                </span>
                Results update automatically via live connection
            </div>
        </div>

    </div>
</section>
@endsection

@push('scripts')
<script type="module">
import Echo from '/build/assets/module.esm-19d5f498.js';

// Try connecting to WebSocket for live updates
try {
    // Use dynamic import for Pusher
    const Pusher = (await import('https://cdn.jsdelivr.net/npm/pusher-js@8/dist/web/pusher.min.js')).default || window.Pusher;
} catch(e) {
    console.log('Pusher not available, using polling fallback');
}

// Polling fallback - refresh data every 30 seconds
setInterval(() => {
    fetch('/api/draw-results?limit=1')
        .then(r => r.json())
        .then(data => {
            if (data?.data?.[0]) {
                // Check if draw number changed
                const drawEl = document.getElementById('ld-draw-no');
                if (drawEl && data.data[0].full_draw_no && drawEl.textContent.trim() !== data.data[0].full_draw_no) {
                    window.location.reload();
                }
            }
        })
        .catch(() => {});
}, 30000);

// WebSocket live updates (same channel as scoreboard)
if (window.Echo) {
    window.Echo.channel('scoreboard')
        .listen('.ScoreboardUpdated', (event) => {
            if (event?.payload?.stc4d) {
                applyLiveData(event.payload.stc4d);
            }
        });
}

function formatMoney(value) {
    return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value);
}

function applyLiveData(data) {
    const drawNo = document.getElementById('ld-draw-no');
    const drawDate = document.getElementById('ld-draw-date');
    const first = document.getElementById('ld-first');
    const second = document.getElementById('ld-second');
    const third = document.getElementById('ld-third');
    const jackpot1 = document.getElementById('ld-jackpot1');
    const jackpot2 = document.getElementById('ld-jackpot2');

    // Check if draw changed - reload for full refresh
    if (drawNo && data.draw_no && drawNo.textContent.trim() !== data.draw_no && drawNo.textContent.trim() !== '-') {
        window.location.reload();
        return;
    }

    if (drawNo && data.draw_no) drawNo.textContent = data.draw_no;
    if (drawDate && data.date) drawDate.textContent = data.date;
    if (first && data.first) first.textContent = data.first;
    if (second && data.second) second.textContent = data.second;
    if (third && data.third) third.textContent = data.third;

    if (jackpot1 && data.jackpot1) jackpot1.textContent = 'RM ' + formatMoney(data.jackpot1);
    if (jackpot2 && data.jackpot2) jackpot2.textContent = 'RM ' + formatMoney(data.jackpot2);

    // Update special prizes
    const specialContainer = document.getElementById('ld-special');
    if (specialContainer && Array.isArray(data.special)) {
        specialContainer.innerHTML = '';
        for (let i = 0; i < 13; i++) {
            const div = document.createElement('div');
            div.className = 'flex items-center gap-2';
            div.innerHTML = `<span class="text-[#03c050] text-xs font-bold min-w-[20px] text-right">${i + 1}.</span><span class="text-white font-bold text-lg tracking-[2px]" style="font-family: 'Rubik', sans-serif;">${data.special[i] || '-'}</span>`;
            specialContainer.appendChild(div);
        }
    }

    // Update consolation prizes
    const consolationContainer = document.getElementById('ld-consolation');
    if (consolationContainer && Array.isArray(data.consolation)) {
        consolationContainer.innerHTML = '';
        for (let i = 0; i < 10; i++) {
            const div = document.createElement('div');
            div.className = 'text-center';
            div.innerHTML = `<span class="text-white font-bold text-lg tracking-[2px]" style="font-family: 'Rubik', sans-serif;">${data.consolation[i] || '-'}</span>`;
            consolationContainer.appendChild(div);
        }
    }

    // Update jackpot combinations
    if (data.first && data.second && data.third) {
        const combos = [
            data.first + ' + ' + data.second,
            data.first + ' + ' + data.third,
            data.second + ' + ' + data.third,
            data.second + ' + ' + data.first,
            data.third + ' + ' + data.first,
            data.third + ' + ' + data.second,
        ];
        document.querySelectorAll('.ld-combo').forEach((el, i) => {
            if (combos[i]) el.textContent = combos[i];
        });
    }
}
</script>
@endpush
