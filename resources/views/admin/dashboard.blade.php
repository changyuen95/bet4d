<x-app-layout>

    @php
        $user = Auth::user();
        $adminCount = App\Models\Admin::where('outlet_id', $user->outlet_id)->count();
        $activeAdminCount = App\Models\Admin::where('outlet_id', $user->outlet_id)->where('status', 'active')->count();

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
              </div>
          </div>
          
          {{-- Marquee Content Update Section --}}
          <div class="row mt-4">
              <div class="col-12">
                  <div class="d-block bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                      <div class="p-6 font-semibold text-md text-gray-800 dark:text-gray-600 leading-tight bg-gradient">
                          <u>Update Marquee Content</u>
                      </div>
                      <div class="p-6">
                          @if(session('success'))
                              <div class="alert alert-success alert-dismissible fade show" role="alert">
                                  <i class="fa fa-check-circle"></i> {{ session('success') }}
                                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                              </div>
                          @endif
                          
                          @if(session('error'))
                              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                  <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                              </div>
                          @endif
                          
                          <form method="POST" action="{{ route('admin.marquee.update') }}">
                              @csrf
                              <div class="mb-3">
                                  <label for="marquee_content" class="form-label font-semibold text-gray-700">Marquee Text</label>
                                  <textarea 
                                      class="form-control @error('marquee_content') is-invalid @enderror" 
                                      id="marquee_content" 
                                      name="marquee_content" 
                                      rows="3" 
                                      placeholder="Enter marquee content here...">{{ old('marquee_content', $marqueeContent ?? '') }}</textarea>
                                  @error('marquee_content')
                                      <div class="invalid-feedback">{{ $message }}</div>
                                  @enderror
                                  <small class="form-text text-muted mt-2 d-block">
                                      <i class="fa fa-info-circle"></i> 
                                      <strong>Available placeholders:</strong> Use <code>%jackpot1%</code> and <code>%jackpot2%</code> to display current jackpot values dynamically.
                                  </small>
                              </div>
                              <button type="submit" class="btn btn-primary" style="background-color: #007bff; color: white; font-weight: 500; border: 1px solid #007bff;">
                                  <i class="fa fa-save"></i> Update Marquee
                              </button>
                          </form>
                      </div>
                  </div>
              </div>
          </div>
        </div>
    </div>
    

</x-app-layout>
