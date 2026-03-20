{{-- Hero Section with Grand Jackpot --}}
<section class="relative {{ isset($marquee) && $marquee ? 'pt-[60px]' : 'pt-[100px]' }} pb-[60px] overflow-hidden" style="background: linear-gradient(180deg,#0a0a15 0%,#0a1a12 100%);">
    {{-- Background Effects --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] left-1/2 -translate-x-1/2 w-full h-[800px]" style="background: radial-gradient(circle at center, rgba(2,138,54,0.15) 0%, transparent 70%);"></div>
        <div class="absolute top-[20%] left-[10%] w-[300px] h-[300px] blur-[60px]" style="background: radial-gradient(circle, rgba(255,215,0,0.05), transparent);"></div>
        <div class="absolute bottom-[20%] right-[10%] w-[400px] h-[400px] blur-[80px]" style="background: radial-gradient(circle, rgba(2,138,54,0.1), transparent);"></div>
        <div class="absolute inset-0 opacity-30" style="background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 40px 40px;"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-8">
        <div class="text-center mb-10">
            <h1 class="text-[clamp(2rem,5vw,3.5rem)] font-black text-white mb-4" style="font-family: 'Rubik', sans-serif; text-shadow: 0 4px 20px rgba(0,0,0,0.5);">
                Win Big with <span class="text-[#03c050]">STC Lucky 4D</span>
            </h1>
            <p class="text-gray-400 text-lg max-w-[600px] mx-auto">Malaysia's most exciting 4D lottery. Pick your lucky numbers and win life-changing jackpots every week!</p>
        </div>

        {{-- Casino Jackpot Board --}}
        <div class="casino-border max-w-[900px] mx-auto rounded-3xl px-5 py-10 text-center relative overflow-hidden">
            {{-- Shine Effect --}}
            <div class="absolute top-0 left-0 w-full h-full pointer-events-none" style="background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,0.05) 45%, rgba(255,255,255,0.1) 50%, rgba(255,255,255,0.05) 55%, transparent 60%); animation: shine 4s infinite;"></div>

            {{-- Jackpot Label --}}
            <div class="inline-block relative mb-6">
                <div class="jackpot-badge px-6 py-2 rounded-full text-white font-extrabold text-[0.9rem] uppercase tracking-[3px]" style="text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                    🔥 Grand Jackpot 1
                </div>
            </div>

            {{-- The Big Number --}}
            <div class="mb-4 relative">
                <div class="gradient-gold-text text-[clamp(3.5rem,10vw,6.5rem)] font-black leading-none tracking-tight" style="font-family: 'Rubik', sans-serif;">
                    @if($jackpot)
                        RM {{ number_format($jackpot->jackpot1 ?? 0) }}
                    @else
                        RM -
                    @endif
                </div>
            </div>

            {{-- Subtext --}}
            <div class="flex items-center justify-center gap-3 text-[#DAA520] font-semibold text-lg tracking-wide">
                @if($jackpot && $jackpot->jackpot1)
                    <span>≈ USD {{ number_format(($jackpot->jackpot1 ?? 0) * 0.21) }}</span>
                @endif
                <span class="w-1.5 h-1.5 bg-[#DAA520] rounded-full"></span>
                <span class="text-[#03c050]">Rolling Over Now</span>
            </div>

            {{-- Decorative --}}
            <div class="absolute top-5 left-5 text-2xl opacity-50" style="animation: float 3s infinite;">💰</div>
            <div class="absolute bottom-5 right-5 text-2xl opacity-50" style="animation: float 4s infinite reverse;">💎</div>
        </div>
    </div>
</section>
