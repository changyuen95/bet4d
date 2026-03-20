{{-- 4D Results Section --}}
<section id="results" x-data="{ activeTab: 'classic' }" class="py-20" style="background-color: #0a0a15;">
    <div class="max-w-7xl mx-auto px-8">
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full mb-4" style="background: rgba(2,138,54,0.1); border: 1px solid rgba(2,138,54,0.2); color: #03c050; font-size: 0.75rem; font-weight: 600; letter-spacing: 2px; text-transform: uppercase;">📊 Latest Results</div>
            <h2 class="text-[clamp(2rem,4vw,3rem)] font-black text-white mb-3" style="font-family: 'Rubik', sans-serif;">
                4D Draw <span class="bg-clip-text text-transparent" style="background: linear-gradient(135deg,#03c050,#028a36); -webkit-background-clip: text;">Results</span>
            </h2>
            @if($latestDraw)
                <p class="text-gray-400 text-base">Draw No. {{ $latestDraw->full_draw_no }} — {{ \Carbon\Carbon::parse($latestDraw->expired_at)->format('l, d F Y') }}</p>
            @else
                <p class="text-gray-400 text-base">No results available yet</p>
            @endif
        </div>

        {{-- Tab Buttons --}}
        <div class="flex gap-2 mb-7 overflow-x-auto pb-1">
            <button @click="activeTab = 'classic'"
                :class="activeTab === 'classic' ? 'text-white border-none' : 'text-gray-400 border border-white/10'"
                :style="activeTab === 'classic' ? 'background: linear-gradient(135deg,#028a36,#03c050)' : 'background: rgba(255,255,255,0.04)'"
                class="px-[22px] py-2.5 rounded-full text-[0.85rem] font-bold cursor-pointer whitespace-nowrap transition-all">
                4D Classic
            </button>
            <button @click="activeTab = 'jackpot'"
                :class="activeTab === 'jackpot' ? 'text-white border-none' : 'text-gray-400 border border-white/10'"
                :style="activeTab === 'jackpot' ? 'background: linear-gradient(135deg,#028a36,#03c050)' : 'background: rgba(255,255,255,0.04)'"
                class="px-[22px] py-2.5 rounded-full text-[0.85rem] font-bold cursor-pointer whitespace-nowrap transition-all">
                4D Jackpot
            </button>
        </div>

        {{-- 4D Classic Result --}}
        <div x-show="activeTab === 'classic'" x-transition>
            <div class="rounded-[20px] overflow-hidden border border-[#028a36]/15" style="background: rgba(255,255,255,0.02);">
                {{-- Card Header --}}
                <div class="px-7 py-5 flex items-center justify-between" style="background: linear-gradient(135deg,#028a36,#03c050);">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-[10px] flex items-center justify-center overflow-hidden" style="background: rgba(255,255,255,0.2);">
                            <img src="{{ asset('images/website/logo.png') }}" alt="STC Logo" class="w-[38px] h-[38px] object-contain rounded-md" onerror="this.style.display='none'">
                        </div>
                        <div>
                            <div class="text-white font-black text-xl" style="font-family: 'Rubik', sans-serif;">4D Classic</div>
                            <div class="text-white/75 text-[0.8rem]">Tap a number to see its meaning.</div>
                        </div>
                    </div>
                    @if($latestDraw)
                        <div class="text-white/80 text-[0.8rem] font-semibold">Draw #{{ $latestDraw->full_draw_no }}</div>
                    @endif
                </div>

                {{-- Top 3 Prizes --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 border-b border-[#028a36]/12">
                    <div class="px-6 py-7 text-center sm:border-r border-b sm:border-b-0 border-[#028a36]/10">
                        <div class="text-gray-400 text-[0.8rem] font-medium mb-2.5">1st Prize</div>
                        <div class="text-[2.8rem] font-black text-white tracking-[6px] leading-none" style="font-family: 'Rubik', sans-serif;">{{ $results['first_prize'] }}</div>
                        <div class="mt-2.5 inline-flex items-center gap-1 px-3 py-1 rounded-full" style="background: rgba(2,138,54,0.15); border: 1px solid rgba(2,138,54,0.3);">
                            <span class="text-[#03c050] text-xs font-bold">RM {{ number_format($prizeList->big1st ?? 2500) }}</span>
                        </div>
                    </div>
                    <div class="px-6 py-7 text-center sm:border-r border-b sm:border-b-0 border-[#028a36]/10">
                        <div class="text-gray-400 text-[0.8rem] font-medium mb-2.5">2nd Prize</div>
                        <div class="text-[2.8rem] font-black text-white tracking-[6px] leading-none" style="font-family: 'Rubik', sans-serif;">{{ $results['second_prize'] }}</div>
                        <div class="mt-2.5 inline-flex items-center gap-1 px-3 py-1 rounded-full" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                            <span class="text-gray-300 text-xs font-bold">RM {{ number_format($prizeList->big2nd ?? 1000) }}</span>
                        </div>
                    </div>
                    <div class="px-6 py-7 text-center">
                        <div class="text-gray-400 text-[0.8rem] font-medium mb-2.5">3rd Prize</div>
                        <div class="text-[2.8rem] font-black text-white tracking-[6px] leading-none" style="font-family: 'Rubik', sans-serif;">{{ $results['third_prize'] }}</div>
                        <div class="mt-2.5 inline-flex items-center gap-1 px-3 py-1 rounded-full" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                            <span class="text-gray-300 text-xs font-bold">RM {{ number_format($prizeList->big3rd ?? 500) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Special & Consolation --}}
                <div class="grid grid-cols-1 md:grid-cols-2">
                    {{-- Special --}}
                    <div class="p-6 md:border-r border-b md:border-b-0 border-[#028a36]/10">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-gray-400 text-[0.8rem] font-semibold uppercase tracking-[1px]">Special</div>
                            <div class="text-gray-500 text-xs">RM {{ number_format($prizeList->big_special ?? 180) }} each</div>
                        </div>
                        <div class="grid grid-cols-4 gap-2">
                            @forelse($results['special'] as $number)
                                <div class="special-num">{{ $number }}</div>
                            @empty
                                @for($i = 0; $i < 20; $i++)
                                    <div class="special-num">-</div>
                                @endfor
                            @endforelse
                        </div>
                    </div>
                    {{-- Consolation --}}
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-gray-400 text-[0.8rem] font-semibold uppercase tracking-[1px]">Consolation</div>
                            <div class="text-gray-500 text-xs">RM {{ number_format($prizeList->big_consolation ?? 60) }} each</div>
                        </div>
                        <div class="grid grid-cols-4 gap-2">
                            @forelse($results['consolation'] as $number)
                                <div class="consolation-num">{{ $number }}</div>
                            @empty
                                @for($i = 0; $i < 20; $i++)
                                    <div class="consolation-num">-</div>
                                @endfor
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4D Jackpot Result --}}
        <div x-show="activeTab === 'jackpot'" x-cloak x-transition>
            <div class="rounded-[20px] overflow-hidden border border-[#0284c7]/20" style="background: rgba(255,255,255,0.02);">
                {{-- Header --}}
                <div class="px-7 py-5 flex items-center justify-between" style="background: linear-gradient(135deg,#0284c7,#028a36);">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-[10px] flex items-center justify-center overflow-hidden" style="background: rgba(255,255,255,0.2);">
                            <img src="{{ asset('images/website/logo.png') }}" alt="STC Logo" class="w-[38px] h-[38px] object-contain rounded-md" onerror="this.style.display='none'">
                        </div>
                        <div>
                            <div class="text-white font-black text-xl" style="font-family: 'Rubik', sans-serif;">4D Jackpot</div>
                            <div class="text-white/75 text-[0.8rem]">Match numbers to win jackpot prizes.</div>
                        </div>
                    </div>
                </div>

                {{-- Jackpot Winning Combinations --}}
                @if($results['first_prize'] !== '-' && $results['second_prize'] !== '-' && $results['third_prize'] !== '-')
                <div class="p-7 border-b border-[#0284c7]/12">
                    <div class="text-gray-400 text-[0.8rem] font-semibold text-center mb-5 uppercase tracking-[1px]">4D Jackpot 1 Winning Numbers</div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                        <div class="jackpot-combo">{{ $results['first_prize'] }} + {{ $results['second_prize'] }}</div>
                        <div class="jackpot-combo">{{ $results['first_prize'] }} + {{ $results['third_prize'] }}</div>
                        <div class="jackpot-combo">{{ $results['second_prize'] }} + {{ $results['first_prize'] }}</div>
                        <div class="jackpot-combo">{{ $results['second_prize'] }} + {{ $results['third_prize'] }}</div>
                        <div class="jackpot-combo">{{ $results['third_prize'] }} + {{ $results['first_prize'] }}</div>
                        <div class="jackpot-combo">{{ $results['third_prize'] }} + {{ $results['second_prize'] }}</div>
                    </div>
                </div>
                @endif

                {{-- Jackpot Prize Amounts --}}
                <div class="grid grid-cols-1 sm:grid-cols-2">
                    <div class="px-6 py-7 text-center sm:border-r border-b sm:border-b-0 border-[#0284c7]/10">
                        <div class="text-gray-400 text-[0.85rem] font-medium mb-3">Jackpot 1 Prize</div>
                        <div class="text-3xl font-black text-[#03c050] mb-3" style="font-family: 'Rubik', sans-serif;">
                            RM {{ number_format($jackpot->jackpot1 ?? 0, 2) }}
                        </div>
                    </div>
                    <div class="px-6 py-7 text-center">
                        <div class="text-gray-400 text-[0.85rem] font-medium mb-3">Jackpot 2 Prize</div>
                        <div class="text-3xl font-black text-[#03c050] mb-3" style="font-family: 'Rubik', sans-serif;">
                            RM {{ number_format($jackpot->jackpot2 ?? 0, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-7">
            <a href="{{ route('results.index') }}" class="inline-block px-8 py-3 rounded-xl font-bold text-white text-sm no-underline transition-all hover:opacity-90" style="background: linear-gradient(135deg,#028a36,#03c050);">
                View All Past Results →
            </a>
        </div>
    </div>
</section>
