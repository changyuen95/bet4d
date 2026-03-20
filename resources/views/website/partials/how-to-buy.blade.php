{{-- How to Buy Section --}}
<section id="how-to-buy" class="py-20" style="background: linear-gradient(180deg,#0a0a15 0%,#0a1a12 50%,#0a0a15 100%);">
    <div class="max-w-7xl mx-auto px-8">
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full mb-4" style="background: rgba(2,138,54,0.1); border: 1px solid rgba(2,138,54,0.2); color: #03c050; font-size: 0.75rem; font-weight: 600; letter-spacing: 2px; text-transform: uppercase;">🎯 Easy Steps</div>
            <h2 class="text-[clamp(2rem,4vw,3rem)] font-black text-white mb-3" style="font-family: 'Rubik', sans-serif;">
                How to <span class="bg-clip-text text-transparent" style="background: linear-gradient(135deg,#03c050,#028a36); -webkit-background-clip: text;">Buy</span>
            </h2>
            <p class="text-gray-400 text-base">Start playing in just 3 simple steps</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            {{-- Step 1 --}}
            <div class="rounded-[20px] p-7 border border-[#028a36]/15 transition-all hover:scale-[1.02]" style="background: rgba(255,255,255,0.02);">
                <div class="flex items-center gap-4 mb-5">
                    <div class="w-[52px] h-[52px] rounded-[14px] flex items-center justify-center font-black text-xl text-white flex-shrink-0" style="background: linear-gradient(135deg,#028a36,#03c050);">1</div>
                    <div class="h-px flex-1" style="background: linear-gradient(to right,rgba(2,138,54,0.4),transparent);"></div>
                </div>
                <div class="text-[2.5rem] mb-3">🏪</div>
                <h3 class="text-white font-bold text-lg mb-2.5" style="font-family: 'Rubik', sans-serif;">Visit an Outlet</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Find your nearest STC 4D authorized outlet. We have outlets nationwide across Malaysia.</p>
                <div class="flex flex-wrap gap-2 mt-4">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold text-[#03c050] border border-[#028a36]/20" style="background: rgba(2,138,54,0.1);">{{ $outletCount ?? 0 }} Outlets</span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold text-[#03c050] border border-[#028a36]/20" style="background: rgba(2,138,54,0.1);">Nationwide</span>
                </div>
            </div>

            {{-- Step 2 --}}
            <div class="rounded-[20px] p-7 border border-[#028a36]/15 transition-all hover:scale-[1.02]" style="background: rgba(255,255,255,0.02);">
                <div class="flex items-center gap-4 mb-5">
                    <div class="w-[52px] h-[52px] rounded-[14px] flex items-center justify-center font-black text-xl text-white flex-shrink-0" style="background: linear-gradient(135deg,#028a36,#03c050);">2</div>
                    <div class="h-px flex-1" style="background: linear-gradient(to right,rgba(2,138,54,0.4),transparent);"></div>
                </div>
                <div class="text-[2.5rem] mb-3">🔢</div>
                <h3 class="text-white font-bold text-lg mb-2.5" style="font-family: 'Rubik', sans-serif;">Pick Your Numbers</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Choose any 4-digit number from 0000 to 9999. You can also use Quick Pick for random numbers.</p>
                <div class="flex gap-2 mt-4 flex-wrap">
                    <div class="w-10 h-10 rounded-[10px] flex items-center justify-center font-black text-sm text-white" style="background: linear-gradient(135deg,#028a36,#03c050);">3</div>
                    <div class="w-10 h-10 rounded-[10px] flex items-center justify-center font-black text-sm text-white" style="background: linear-gradient(135deg,#028a36,#03c050);">8</div>
                    <div class="w-10 h-10 rounded-[10px] flex items-center justify-center font-black text-sm text-white" style="background: linear-gradient(135deg,#028a36,#03c050);">5</div>
                    <div class="w-10 h-10 rounded-[10px] flex items-center justify-center font-black text-sm text-white" style="background: linear-gradient(135deg,#028a36,#03c050);">2</div>
                </div>
            </div>

            {{-- Step 3 --}}
            <div class="rounded-[20px] p-7 border border-[#028a36]/15 transition-all hover:scale-[1.02]" style="background: rgba(255,255,255,0.02);">
                <div class="flex items-center gap-4 mb-5">
                    <div class="w-[52px] h-[52px] rounded-[14px] flex items-center justify-center font-black text-xl text-white flex-shrink-0" style="background: linear-gradient(135deg,#028a36,#03c050);">3</div>
                    <div class="h-px flex-1" style="background: linear-gradient(to right,rgba(2,138,54,0.4),transparent);"></div>
                </div>
                <div class="text-[2.5rem] mb-3">🎟️</div>
                <h3 class="text-white font-bold text-lg mb-2.5" style="font-family: 'Rubik', sans-serif;">Pay & Get Ticket</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Pay as little as RM 1 per number. Get your official ticket and wait for the draw results!</p>
                <div class="flex flex-wrap gap-2 mt-4">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold text-[#03c050] border border-[#028a36]/20" style="background: rgba(2,138,54,0.1);">Min RM 1</span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold text-[#03c050] border border-[#028a36]/20" style="background: rgba(2,138,54,0.1);">Official Receipt</span>
                </div>
            </div>
        </div>
    </div>
</section>
