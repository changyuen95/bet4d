<x-app-layout>

    @php
        $user = Auth::user();
        $adminCount = App\Models\Admin::where('outlet_id', $user->outlet_id)->count();
        $activeAdminCount = App\Models\Admin::where('outlet_id', $user->outlet_id)->where('status', 'active')->count();
        $today_date = Carbon\Carbon::now()->format('Y-m-d');
        $nextDraw = App\Models\Draw::where('platform_id', $user->outlet->platform->id)->whereDate('expired_at', '>', $today_date)->orderBy('expired_at', 'asc')->first();

    @endphp

    <div class="py-8">
        <div class="max-w-7xl ml-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 font-semibold text-xl text-gray-800 dark:text-gray-600 leading-tight bg-gradient">
                    {{ "Welcome Back! " .Auth::user()->name }}
                </div>
            </div>
        </div>
    </div>
    
    <div class="max-w-7xl ml-auto sm:px-6 lg:px-8">
        <div class="row">
            <div class="col-4">
                <div class="d-block bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 font-semibold text-md text-gray-800 dark:text-gray-600 leading-tight bg-gradient">
                        <u>Outlet Information</u>
                    </div>

                    <div class="mb-3">
                        <div class="font-semibold text-md px-6">
                            Platform
                        </div>
                        <span class="px-6">{{ Auth::user()->outlet->platform->name }}</span>
                    </div>

                    <div class="mb-3">
                        <div class="font-semibold text-md px-6">
                            Name
                        </div>
                        <span class="px-6">{{ Auth::user()->outlet->name }}</span>
                    </div>

                    <div class="mb-3">
                        <div class="font-semibold text-md px-6">
                            Address
                        </div>
                        <span class="px-6">{{ Auth::user()->outlet->address }}</span>
                    </div>

                    <div class="mb-3">
                        <div class="font-semibold text-md px-6">
                            Operation Hour
                        </div>
                        <span class="px-6">9:00 AM to 6:00 PM</span>
                    </div>

                    {{-- <div class="mb-3">
                        <img src="asset">
                    </div> --}}

                </div>
            </div>
            <div class="col-4">
                <div class="d-block bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4">
                    <div class="d-flex p-6 font-semibold text-md text-gray-800 dark:text-gray-600 leading-tight bg-gradient">
                        <i class="fa fa-users" style="font-size: 60px" aria-hidden="true"></i>
                        <div class="pl-8 font-semibold text-md text-gray-800 dark:text-gray-600 leading-tight bg-gradient">
                            <u>Number of Admin</u> <br/>
                            <span class="h1">{{ $adminCount }}</span>
                        </div>
                    </div>
                </div>
                <div class="d-block bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="d-flex p-6 font-semibold text-md text-gray-800 dark:text-gray-600 leading-tight bg-gradient">
                        <i class="fa fa-user-circle" style="font-size: 60px" aria-hidden="true"></i>
                        <div class="pl-8 font-semibold text-md text-gray-800 dark:text-gray-600 leading-tight bg-gradient">
                            <u>Number of Active Admin</u> <br/>
                            <span class="h1">{{ $activeAdminCount }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-4">
                {{-- Column --}}
                <div class="d-block bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4">
                    <div class="d-flex p-6 font-semibold text-md text-gray-800 dark:text-gray-600 leading-tight bg-gradient">
                        <i class="fa fa-star" style="font-size: 60px" aria-hidden="true"></i>
                        <div class="pl-8 font-semibold text-md text-gray-800 dark:text-gray-600 leading-tight bg-gradient">
                            <u>Number</u> <br/>
                            <span class="h1">5</span>
                        </div>
                    </div>
                </div>
                <div class="d-block bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4">
                    <div class="d-flex p-6 font-semibold text-md text-gray-800 dark:text-gray-600 leading-tight bg-gradient">
                        <i class="fa fa-calendar" style="font-size: 60px" aria-hidden="true"></i>
                        <div class="pl-8 font-semibold text-md text-gray-800 dark:text-gray-600 leading-tight bg-gradient">
                            <u>Next Draw Date</u> <br/>
                            <span class="h5">{{ $nextDraw->expired_at }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
          </div>
        </div>
    </div>
    

</x-app-layout>
