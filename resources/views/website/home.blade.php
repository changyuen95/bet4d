@extends('website.layouts.app')

@section('title', 'STC Lucky 4D Malaysia - Win Big with Lucky 4D')
@section('meta_description', 'STC Lucky 4D Malaysia - Check latest 4D results, jackpot prizes up to RM ' . number_format($jackpot->jackpot1 ?? 0) . '. Draw results updated live.')

@section('content')
    {{-- Marquee Announcement --}}
    @if($marquee && $marquee->message)
    <div class="pt-[68px]">
        <div class="overflow-hidden py-2" style="background: linear-gradient(135deg,#028a36,#03c050);">
            <div class="marquee-scroll whitespace-nowrap text-white text-sm font-semibold">
                <span class="inline-block px-12">🔔 {{ $marquee->message }}</span>
                <span class="inline-block px-12">🔔 {{ $marquee->message }}</span>
            </div>
        </div>
    </div>
    @endif

    @include('website.partials.hero')
    @include('website.partials.jackpot-pool')
    @include('website.partials.countdown')
    @include('website.partials.results')

    {{-- Recent Winners Section --}}
    @if($recentWinners->count() > 0)
    <section class="py-16" style="background: linear-gradient(180deg,#0a0a15 0%,#0a1a12 100%);">
        <div class="max-w-7xl mx-auto px-8">
            <div class="text-center mb-10">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full mb-4" style="background: rgba(2,138,54,0.1); border: 1px solid rgba(2,138,54,0.2); color: #03c050; font-size: 0.75rem; font-weight: 600; letter-spacing: 2px; text-transform: uppercase;">🏆 Winners</div>
                <h2 class="text-[clamp(2rem,4vw,3rem)] font-black text-white mb-3" style="font-family: 'Rubik', sans-serif;">
                    Recent <span class="bg-clip-text text-transparent" style="background: linear-gradient(135deg,#03c050,#028a36); -webkit-background-clip: text;">Winners</span>
                </h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($recentWinners as $winner)
                <div class="rounded-2xl p-5 border border-[#028a36]/15" style="background: rgba(255,255,255,0.02);">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-white font-bold text-lg tracking-[3px]" style="font-family: 'Rubik', sans-serif;">{{ $winner->number ?? '-' }}</span>
                        <span class="text-[#03c050] font-black text-lg" style="font-family: 'Rubik', sans-serif;">RM {{ number_format($winner->winning_amount ?? 0) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span>{{ $winner->description ?? '' }}</span>
                        <span>{{ $winner->outlet ? $winner->outlet->name : '' }}</span>
                    </div>
                    @if($winner->draw)
                    <div class="mt-2 text-xs text-gray-600">Draw #{{ $winner->draw->full_draw_no }}</div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @include('website.partials.how-to-buy')
@endsection

@push('head')
<style>
@keyframes marquee-scroll {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.marquee-scroll {
    animation: marquee-scroll 20s linear infinite;
    display: inline-flex;
}
</style>
@endpush
