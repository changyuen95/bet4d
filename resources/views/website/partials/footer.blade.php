{{-- Footer --}}
<footer class="pt-12 pb-6 border-t border-[#028a36]/12" style="background-color: #050510;">
    <div class="max-w-7xl mx-auto px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
            {{-- Brand --}}
            <div class="sm:col-span-2">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-[10px] flex items-center justify-center overflow-hidden" style="background: linear-gradient(135deg,#028a36,#03c050);">
                        <img src="{{ asset('images/website/logo.png') }}" alt="STC 4D Logo" class="w-9 h-9 object-contain rounded-md" onerror="this.style.display='none'">
                    </div>
                    <div>
                        <div class="text-white font-black text-lg" style="font-family: 'Rubik', sans-serif;">STC <span class="text-[#03c050]">4D</span></div>
                        <div class="text-[#028a36] text-[0.65rem] tracking-[3px] font-semibold uppercase">Malaysia</div>
                    </div>
                </div>
                <p class="text-gray-500 text-sm leading-relaxed max-w-xs mb-4">Malaysia's premier 4D lottery operator. Licensed and regulated. Play responsibly — lottery is for entertainment only.</p>
                <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg" style="background: rgba(2,138,54,0.08); border: 1px solid rgba(2,138,54,0.2);">
                    <span class="text-[#03c050] text-xs font-semibold">🔒 Licensed by Ministry of Finance Malaysia</span>
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <div class="text-white font-bold text-[0.8rem] uppercase tracking-[2px] mb-4">Quick Links</div>
                <div class="flex flex-col gap-2">
                    <a href="{{ route('home') }}#jackpot" class="text-gray-500 text-sm no-underline transition-colors hover:text-[#03c050]">Jackpot Pool</a>
                    <a href="{{ route('results.index') }}" class="text-gray-500 text-sm no-underline transition-colors hover:text-[#03c050]">Latest Results</a>
                    <a href="{{ route('home') }}#how-to-buy" class="text-gray-500 text-sm no-underline transition-colors hover:text-[#03c050]">How to Buy</a>
                    <a href="{{ route('home') }}#contact" class="text-gray-500 text-sm no-underline transition-colors hover:text-[#03c050]">Contact Us</a>
                </div>
            </div>

            {{-- Support --}}
            <div>
                <div class="text-white font-bold text-[0.8rem] uppercase tracking-[2px] mb-4">Support</div>
                <div class="flex flex-col gap-2">
                    <a href="#" class="text-gray-500 text-sm no-underline transition-colors hover:text-[#03c050]">FAQ</a>
                    <a href="#" class="text-gray-500 text-sm no-underline transition-colors hover:text-[#03c050]">Prize Claim Guide</a>
                    <a href="#" class="text-gray-500 text-sm no-underline transition-colors hover:text-[#03c050]">Responsible Gaming</a>
                    <a href="#" class="text-gray-500 text-sm no-underline transition-colors hover:text-[#03c050]">Terms & Conditions</a>
                    <a href="#" class="text-gray-500 text-sm no-underline transition-colors hover:text-[#03c050]">Privacy Policy</a>
                </div>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="pt-5 flex flex-wrap items-center justify-between gap-3 border-t border-white/5">
            <div class="text-gray-600 text-xs">© {{ date('Y') }} STC Lucky4D Malaysia. All rights reserved. Must be 21+ to play.</div>
            <div class="flex items-center gap-2.5">
                <div class="px-3 py-1 rounded-full text-xs font-bold text-[#03c050]" style="background: rgba(2,138,54,0.1); border: 1px solid rgba(2,138,54,0.2);">18+</div>
                <div class="text-gray-600 text-xs">Play Responsibly</div>
            </div>
        </div>
    </div>
</footer>
