{{-- Jackpot Pool Section --}}
<section id="jackpot" class="py-[60px] pb-20" style="background-color: #0a0a15;">
    <div class="max-w-7xl mx-auto px-8">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-400 uppercase tracking-[2px]" style="font-family: 'Rubik', sans-serif;">Current Prize Breakdown</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Jackpot 1 Card --}}
            <div class="rounded-3xl overflow-hidden p-[2px]" style="background: linear-gradient(135deg,#028a36,#03c050);">
                <div class="rounded-[22px] p-8 h-full" style="background: linear-gradient(135deg,#0d1f14,#0a1a12);">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-[0.8rem] text-white" style="background: linear-gradient(135deg,#028a36,#03c050);">J1</div>
                                <span class="text-gray-400 text-xs font-semibold uppercase tracking-[2px]">Jackpot 1</span>
                            </div>
                            <h3 class="text-white font-bold text-lg" style="font-family: 'Rubik', sans-serif;">Grand Jackpot Prize</h3>
                        </div>
                        <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl" style="background: rgba(2,138,54,0.15);">🏆</div>
                    </div>
                    <div class="mb-6">
                        <div class="text-gray-500 text-[0.7rem] uppercase tracking-[2px] mb-1.5">Current Pool Amount</div>
                        <div class="text-3xl font-black text-[#03c050]" style="font-family: 'Rubik', sans-serif;">
                            RM {{ number_format($jackpot->jackpot1 ?? 0) }}
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-[#028a36]/20">
                        <div class="text-gray-500 text-xs">Match 4D + 2D</div>
                        <div class="text-[#03c050] text-sm font-semibold">1 in 10,000</div>
                    </div>
                </div>
            </div>

            {{-- Jackpot 2 Card --}}
            <div class="rounded-3xl overflow-hidden p-[2px]" style="background: linear-gradient(135deg,#0284c7,#028a36);">
                <div class="rounded-[22px] p-8 h-full" style="background: linear-gradient(135deg,#0d1a1f,#0a1218);">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-[0.8rem] text-white" style="background: linear-gradient(135deg,#0284c7,#028a36);">J2</div>
                                <span class="text-gray-400 text-xs font-semibold uppercase tracking-[2px]">Jackpot 2</span>
                            </div>
                            <h3 class="text-white font-bold text-lg" style="font-family: 'Rubik', sans-serif;">Second Jackpot Prize</h3>
                        </div>
                        <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl" style="background: rgba(2,132,199,0.15);">🥈</div>
                    </div>
                    <div class="mb-6">
                        <div class="text-gray-500 text-[0.7rem] uppercase tracking-[2px] mb-1.5">Current Pool Amount</div>
                        <div class="text-3xl font-black text-[#38bdf8]" style="font-family: 'Rubik', sans-serif;">
                            RM {{ number_format($jackpot->jackpot2 ?? 0) }}
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-[#0284c7]/20">
                        <div class="text-gray-500 text-xs">Match Any 4D</div>
                        <div class="text-[#38bdf8] text-sm font-semibold">1 in 1,000</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
