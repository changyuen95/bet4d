@extends('website.layouts.app')

@section('title', 'Draw #' . $draw->full_draw_no . ' Results - STC Lucky 4D')
@section('meta_description', '4D Draw results for Draw #' . $draw->full_draw_no . '. 1st Prize: ' . $results['first_prize'] . ', 2nd Prize: ' . $results['second_prize'] . ', 3rd Prize: ' . $results['third_prize'])

@section('content')
<section class="pt-[100px] pb-20" style="background-color: #0a0a15;">
    <div class="max-w-5xl mx-auto px-8">
        {{-- Back Link --}}
        <a href="{{ route('results.index') }}" class="inline-flex items-center gap-2 text-[#03c050] text-sm font-semibold no-underline mb-8 hover:opacity-80 transition-opacity">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to All Results
        </a>

        {{-- Result Card --}}
        <div class="rounded-[20px] overflow-hidden border border-[#028a36]/15" style="background: rgba(255,255,255,0.02);">
            {{-- Header --}}
            <div class="px-7 py-5 flex items-center justify-between" style="background: linear-gradient(135deg,#028a36,#03c050);">
                <div>
                    <div class="text-white font-black text-xl" style="font-family: 'Rubik', sans-serif;">4D Classic Results</div>
                    <div class="text-white/75 text-sm">Draw #{{ $draw->full_draw_no }} — {{ \Carbon\Carbon::parse($draw->expired_at)->format('l, d F Y') }}</div>
                </div>
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

        {{-- Jackpot Info --}}
        @if($jackpot)
        <div class="mt-6 rounded-[20px] overflow-hidden border border-[#0284c7]/20" style="background: rgba(255,255,255,0.02);">
            <div class="px-7 py-4" style="background: linear-gradient(135deg,#0284c7,#028a36);">
                <div class="text-white font-black text-lg" style="font-family: 'Rubik', sans-serif;">4D Jackpot</div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2">
                <div class="px-6 py-6 text-center sm:border-r border-b sm:border-b-0 border-[#0284c7]/10">
                    <div class="text-gray-400 text-sm font-medium mb-2">Jackpot 1</div>
                    <div class="text-2xl font-black text-[#03c050]" style="font-family: 'Rubik', sans-serif;">RM {{ number_format($jackpot->jackpot1 ?? 0, 2) }}</div>
                </div>
                <div class="px-6 py-6 text-center">
                    <div class="text-gray-400 text-sm font-medium mb-2">Jackpot 2</div>
                    <div class="text-2xl font-black text-[#03c050]" style="font-family: 'Rubik', sans-serif;">RM {{ number_format($jackpot->jackpot2 ?? 0, 2) }}</div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
