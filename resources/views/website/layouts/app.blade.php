<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'STC Lucky 4D Malaysia - Check latest 4D results, jackpot prizes and draw schedules.')">
    <meta name="keywords" content="STC 4D, Lucky 4D, Malaysia 4D, 4D results, jackpot">
    <meta name="theme-color" content="#028a36">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'STC Lucky 4D Malaysia')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vite Assets --}}
    @vite(['resources/css/website.css', 'resources/js/website.js'])

    @stack('head')
</head>
<body class="font-body antialiased bg-[#0a0a15] text-white" style="font-family: 'Inter', sans-serif;">

    @include('website.partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('website.partials.footer')

    @stack('scripts')
</body>
</html>
