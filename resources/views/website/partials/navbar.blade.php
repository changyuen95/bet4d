{{-- Navbar --}}
<nav x-data="{ mobileOpen: false }" class="fixed top-0 left-0 right-0 z-50 border-b border-green-primary/20" style="background: rgba(10,10,21,0.97); backdrop-filter: blur(12px);">
    <div class="max-w-7xl mx-auto px-8">
        <div class="flex items-center justify-between h-[68px]">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 no-underline">
                <div class="w-[42px] h-[42px] rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg,#028a36,#03c050); box-shadow: 0 0 16px rgba(2,138,54,0.4);">
                    <img src="{{ asset('images/website/logo.png') }}" alt="STC 4D Logo" class="w-9 h-9 object-contain rounded-lg" onerror="this.style.display='none'">
                </div>
                <div>
                    <div class="text-white font-black text-xl tracking-tight leading-none" style="font-family: 'Rubik', sans-serif;">STC <span class="text-[#03c050]">4D</span></div>
                    <div class="text-[#028a36] text-[0.65rem] tracking-[3px] font-semibold uppercase">Malaysia</div>
                </div>
            </a>

            {{-- Desktop Nav --}}
            <div class="hidden lg:flex items-center gap-1.5" id="desktop-nav">
                <a href="{{ route('home') }}" class="nav-link text-gray-300 text-sm font-medium px-3.5 py-2 rounded-lg no-underline transition-all">Home</a>
                <div class="dropdown-parent relative">
                    <a href="{{ route('results.index') }}" class="nav-link text-gray-300 text-sm font-medium px-3.5 py-2 rounded-lg no-underline transition-all flex items-center gap-1">
                        Results
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </a>
                    <div class="dropdown-menu">
                        <a href="{{ route('results.index') }}" class="dropdown-item">Latest Results</a>
                        <a href="{{ route('results.index') }}?type=classic" class="dropdown-item">4D Classic</a>
                        <a href="{{ route('results.index') }}?type=jackpot" class="dropdown-item">4D Jackpot</a>
                    </div>
                </div>
                <a href="{{ route('live-draw') }}" class="nav-link text-sm font-medium px-3.5 py-2 rounded-lg no-underline transition-all flex items-center gap-1.5" style="color: #03c050;">
                    <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#03c050] opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-[#03c050]"></span></span>
                    Live Draw
                </a>
                <a href="{{ route('home') }}#jackpot" class="nav-link text-gray-300 text-sm font-medium px-3.5 py-2 rounded-lg no-underline transition-all">Jackpot</a>
                <a href="{{ route('home') }}#how-to-buy" class="nav-link text-gray-300 text-sm font-medium px-3.5 py-2 rounded-lg no-underline transition-all">How to Play</a>
                <a href="{{ route('home') }}#contact" class="nav-link text-gray-300 text-sm font-medium px-3.5 py-2 rounded-lg no-underline transition-all">Contact</a>
            </div>

            {{-- Right Actions --}}
            <div class="flex items-center gap-2.5">
                <div class="hidden lg:flex items-center gap-1.5" id="nav-actions-desktop">
                    <a href="#" class="px-4 py-2 rounded-lg text-[0.8rem] font-semibold text-[#03c050] border border-[#028a36]/40 no-underline transition-all hover:bg-[#028a36]/10">Login</a>
                    <a href="{{ route('home') }}#how-to-buy" class="px-[18px] py-2 rounded-lg text-[0.8rem] font-bold text-white no-underline transition-all" style="background: linear-gradient(135deg,#028a36,#03c050); box-shadow: 0 4px 15px rgba(2,138,54,0.3);">Buy Now</a>
                </div>
                {{-- Mobile Hamburger --}}
                <button @click="mobileOpen = !mobileOpen" id="hamburger-btn" class="lg:hidden rounded-lg p-2 cursor-pointer flex items-center justify-center" style="background: rgba(2,138,54,0.1); border: 1px solid rgba(2,138,54,0.25);">
                    <svg class="w-5 h-5 text-[#03c050]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileOpen" x-cloak x-transition class="lg:hidden border-t border-[#028a36]/15 px-6 py-4 pb-5" style="background: rgba(10,10,21,0.99);">
        <div class="flex flex-col gap-1">
            <a href="{{ route('home') }}" class="text-gray-300 px-3 py-2.5 rounded-lg text-[0.9rem] font-medium no-underline border-b border-white/5">Home</a>
            <a href="{{ route('results.index') }}" class="text-gray-300 px-3 py-2.5 rounded-lg text-[0.9rem] font-medium no-underline border-b border-white/5">Results</a>
            <a href="{{ route('live-draw') }}" class="px-3 py-2.5 rounded-lg text-[0.9rem] font-medium no-underline border-b border-white/5 flex items-center gap-2" style="color: #03c050;">
                <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#03c050] opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-[#03c050]"></span></span>
                Live Draw
            </a>
            <a href="{{ route('home') }}#jackpot" class="text-gray-300 px-3 py-2.5 rounded-lg text-[0.9rem] font-medium no-underline border-b border-white/5">Jackpot</a>
            <a href="{{ route('home') }}#how-to-buy" class="text-gray-300 px-3 py-2.5 rounded-lg text-[0.9rem] font-medium no-underline border-b border-white/5">How to Play</a>
            <a href="{{ route('home') }}#contact" class="text-gray-300 px-3 py-2.5 rounded-lg text-[0.9rem] font-medium no-underline border-b border-white/5">Contact</a>
            <div class="flex gap-2 mt-3">
                <a href="#" class="flex-1 text-center py-2.5 rounded-lg text-[0.85rem] font-semibold text-[#03c050] border border-[#028a36]/40 no-underline">Login</a>
                <a href="{{ route('home') }}#how-to-buy" class="flex-1 text-center py-2.5 rounded-lg text-[0.85rem] font-bold text-white no-underline" style="background: linear-gradient(135deg,#028a36,#03c050);">Buy Now</a>
            </div>
        </div>
    </div>
</nav>
