{{-- Countdown Timer --}}
<section
    x-data="countdown('{{ $nextDraw ? \Carbon\Carbon::parse($nextDraw->expired_at)->toIso8601String() : '' }}')"
    class="py-7 max-h-[160px]"
    style="background: linear-gradient(135deg,#028a36 0%,#03c050 100%);"
>
    <div class="max-w-7xl mx-auto px-8">
        <div class="flex flex-wrap items-center justify-between gap-5">
            <div>
                <div class="text-white font-black text-xl" style="font-family: 'Rubik', sans-serif;">Next Draw Countdown</div>
                <div class="text-white/75 text-sm">
                    @if($nextDraw)
                        {{ \Carbon\Carbon::parse($nextDraw->expired_at)->format('l, d F Y') }} at {{ \Carbon\Carbon::parse($nextDraw->expired_at)->format('g:i A') }}
                    @else
                        No upcoming draw scheduled
                    @endif
                </div>
            </div>

            @if($nextDraw)
            <div class="flex items-center gap-3">
                {{-- Days --}}
                <div class="text-center">
                    <div class="rounded-xl px-4 py-2.5 min-w-[60px]" style="background: rgba(0,0,0,0.2);">
                        <div class="text-white font-black text-3xl leading-none" style="font-family: 'Rubik', sans-serif;" x-text="days">00</div>
                        <div class="text-white/70 text-[0.65rem] font-semibold uppercase tracking-[1px]">Days</div>
                    </div>
                </div>
                <div class="text-white font-black text-2xl" style="font-family: 'Rubik', sans-serif;">:</div>
                {{-- Hours --}}
                <div class="text-center">
                    <div class="rounded-xl px-4 py-2.5 min-w-[60px]" style="background: rgba(0,0,0,0.2);">
                        <div class="text-white font-black text-3xl leading-none" style="font-family: 'Rubik', sans-serif;" x-text="hours">00</div>
                        <div class="text-white/70 text-[0.65rem] font-semibold uppercase tracking-[1px]">Hours</div>
                    </div>
                </div>
                <div class="text-white font-black text-2xl" style="font-family: 'Rubik', sans-serif;">:</div>
                {{-- Minutes --}}
                <div class="text-center">
                    <div class="rounded-xl px-4 py-2.5 min-w-[60px]" style="background: rgba(0,0,0,0.2);">
                        <div class="text-white font-black text-3xl leading-none" style="font-family: 'Rubik', sans-serif;" x-text="minutes">00</div>
                        <div class="text-white/70 text-[0.65rem] font-semibold uppercase tracking-[1px]">Mins</div>
                    </div>
                </div>
                <div class="text-white font-black text-2xl" style="font-family: 'Rubik', sans-serif;">:</div>
                {{-- Seconds --}}
                <div class="text-center">
                    <div class="rounded-xl px-4 py-2.5 min-w-[60px]" style="background: rgba(0,0,0,0.2);">
                        <div class="text-white font-black text-3xl leading-none" style="font-family: 'Rubik', sans-serif;" x-text="seconds">00</div>
                        <div class="text-white/70 text-[0.65rem] font-semibold uppercase tracking-[1px]">Secs</div>
                    </div>
                </div>
            </div>
            @endif

            <a href="#how-to-buy" class="px-6 py-2.5 rounded-[10px] font-bold text-white text-sm no-underline transition-all hover:bg-black/40" style="background: rgba(0,0,0,0.25); border: 2px solid rgba(255,255,255,0.3);">
                Buy Ticket →
            </a>
        </div>
    </div>
</section>

@push('scripts')
<script>
function countdown(targetDate) {
    return {
        days: '00',
        hours: '00',
        minutes: '00',
        seconds: '00',
        interval: null,
        init() {
            if (!targetDate) return;
            this.update();
            this.interval = setInterval(() => this.update(), 1000);
        },
        update() {
            const now = new Date();
            const target = new Date(targetDate);
            const diff = target - now;
            if (diff <= 0) {
                this.days = '00';
                this.hours = '00';
                this.minutes = '00';
                this.seconds = '00';
                if (this.interval) clearInterval(this.interval);
                return;
            }
            this.days = String(Math.floor(diff / 86400000)).padStart(2, '0');
            this.hours = String(Math.floor((diff % 86400000) / 3600000)).padStart(2, '0');
            this.minutes = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
            this.seconds = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
        },
        destroy() {
            if (this.interval) clearInterval(this.interval);
        }
    };
}
</script>
@endpush
