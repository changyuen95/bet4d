@extends('website.layouts.app')

@section('title', 'Past Draw Results - STC Lucky 4D Malaysia')
@section('meta_description', 'View all past STC Lucky 4D draw results. Check winning numbers for 4D Classic and 4D Jackpot.')

@section('content')
<section class="pt-[100px] pb-20" style="background-color: #0a0a15;">
    <div class="max-w-7xl mx-auto px-8">
        {{-- Page Header --}}
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full mb-4" style="background: rgba(2,138,54,0.1); border: 1px solid rgba(2,138,54,0.2); color: #03c050; font-size: 0.75rem; font-weight: 600; letter-spacing: 2px; text-transform: uppercase;">📊 All Results</div>
            <h1 class="text-[clamp(2rem,4vw,3rem)] font-black text-white mb-3" style="font-family: 'Rubik', sans-serif;">
                Past Draw <span class="bg-clip-text text-transparent" style="background: linear-gradient(135deg,#03c050,#028a36); -webkit-background-clip: text;">Results</span>
            </h1>
            <p class="text-gray-400 text-base">Browse all historical draw results</p>
        </div>

        {{-- Results List --}}
        <div class="space-y-6">
            @forelse($draws as $draw)
                @php
                    $first = $draw->results->where('type', '1st')->where('number', '!=', '-')->where('number', '!=', '')->first();
                    $second = $draw->results->where('type', '2nd')->where('number', '!=', '-')->where('number', '!=', '')->first();
                    $third = $draw->results->where('type', '3rd')->where('number', '!=', '-')->where('number', '!=', '')->first();
                @endphp
                <a href="{{ route('results.show', $draw) }}" class="block no-underline">
                    <div class="rounded-[20px] overflow-hidden border border-[#028a36]/15 transition-all hover:border-[#028a36]/40" style="background: rgba(255,255,255,0.02);">
                        {{-- Header --}}
                        <div class="px-7 py-4 flex items-center justify-between" style="background: linear-gradient(135deg,#028a36,#03c050);">
                            <div class="flex items-center gap-3">
                                <div class="text-white font-black text-lg" style="font-family: 'Rubik', sans-serif;">Draw #{{ $draw->full_draw_no }}</div>
                            </div>
                            <div class="text-white/80 text-sm font-medium">{{ \Carbon\Carbon::parse($draw->expired_at)->format('d M Y (D)') }}</div>
                        </div>

                        {{-- Top 3 Prizes --}}
                        <div class="grid grid-cols-3 divide-x divide-[#028a36]/10 py-5">
                            <div class="text-center">
                                <div class="text-gray-400 text-xs font-medium mb-1">1st Prize</div>
                                <div class="text-2xl font-black text-white tracking-[4px]" style="font-family: 'Rubik', sans-serif;">{{ $first ? $first->number : '-' }}</div>
                            </div>
                            <div class="text-center">
                                <div class="text-gray-400 text-xs font-medium mb-1">2nd Prize</div>
                                <div class="text-2xl font-black text-white tracking-[4px]" style="font-family: 'Rubik', sans-serif;">{{ $second ? $second->number : '-' }}</div>
                            </div>
                            <div class="text-center">
                                <div class="text-gray-400 text-xs font-medium mb-1">3rd Prize</div>
                                <div class="text-2xl font-black text-white tracking-[4px]" style="font-family: 'Rubik', sans-serif;">{{ $third ? $third->number : '-' }}</div>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center py-20">
                    <div class="text-5xl mb-4">🎰</div>
                    <div class="text-gray-400 text-lg">No draw results available yet.</div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($draws->hasPages())
        <div class="mt-10 flex justify-center">
            {{ $draws->links() }}
        </div>
        @endif
    </div>
</section>
@endsection
