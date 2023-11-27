<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        {{-- Bootstrap CDN --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

        <!-- Scripts -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        {{-- Bootstrap CDN --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        
        {{-- Sweet Alert CDN --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    {{-- <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-slate-100">
            @include('admin.components.topbar')



            @yield('content')

          <!-- Control Sidebar -->
          <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            @include('admin.components.sidebar')
          </aside>
            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl ml-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="ml-auto">
                {{ $slot }}
            </main>
        </div>
    </body> --}}
    <body class="font-sans antialiased">
        
        <div class="min-h-screen bg-gray-100 dark:bg-slate-100">
            @include('admin.components.topbar')
    
            <div class="grid grid-cols-12 gap-4">
                <!-- Sidebar -->
                <aside class="control-sidebar control-sidebar-dark col-span-2">
                    <!-- Control sidebar content goes here -->
                    @include('admin.components.sidebar')
                </aside>
    
                <!-- Content -->
                <main class="col-span-9">
                    
                    <!-- Page Heading -->
                    @if (isset($header))
                        <header class="bg-white dark:bg-gray-800 shadow">
                            <div class="max-w-7xl ml-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endif
    
                    <!-- Page Content -->
                    <div class="ml-4 mt-4">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>


        {{-- Sweet Alert Session Message when has session--}}
        @if(session()->has('success'))
            <script>
                $(document).ready(function(){
                    swal.fire("Success!", "{{session()->get('success')}}", "success")
                });
            </script>
        @endif

        @if(session()->has('warning'))
            <script>
                $(document).ready(function(){
                    swal.fire("Warning!", "{{session()->get('warning')}}", "info")
                });
            </script>
        @endif

        @if(session()->has('fail'))
            <script>
                $(document).ready(function(){
                    swal.fire("Error!", "{{session()->get('fail')}}", "error")
                });
            </script>
        @endif
    </body>
</html>

