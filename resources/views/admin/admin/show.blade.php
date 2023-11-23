
<x-app-layout>
    <x-slot name="header">
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-600 leading-tight bg-gradient">
            {{ __('Admin Information') }}
        </h2>

        @if(Auth::user()->role == 'super_admin')
          <div class="inline-flex">
            @if($admin->email)
              <button class="focus:outline-none text-white bg-slate-400 hover:bg-slate-600 font-medium rounded-lg text-md px-4 py-1.5 dark:bg-slate-400 dark:hover:bg-slate-600 mr-2 resend_btn" data-admin_id="{{$admin->id}}">Resend Email</button>
            @endif
            <a href="{{route('admin.admins.edit', $admin->id)}}" class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-md px-4 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 ">Edit Admin</a>
          </div>
        @endif
      </div>
    </x-slot>

    <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
          <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-name">
            Name
          </label>
          <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight" id="grid-name" type="text" placeholder="" value="{{ $admin->name ?? '-'}}" readonly>
          {{-- <p class="text-red-500 text-xs italic">Please fill out this field.</p> --}}
        </div>
        <div class="w-full md:w-1/2 px-3">
          <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-email">
            Email
          </label>
          <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight " id="grid-email" type="text" placeholder="" value="{{ $admin->email ?? '-'}}" readonly>
        </div>
    </div>
    <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full px-3">
          <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-password">
            Phone Number
          </label>
          <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight " id="grid-password" type="text" placeholder="" value="{{ $admin->phone_e164 ?? '-'}}" readonly>
          {{-- <div class="flex">
            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
              +60
            </span>
            <input type="text" id="website-admin" class="rounded-none rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Phone Number" name="phone_number"  value="{{ $admin->phone_e164 }}" readonly>
          </div> --}}
          {{-- <p class="text-gray-600 text-xs italic">Make it as long and as crazy as you'd like</p> --}}
        </div>
    </div>
    <div class="flex flex-wrap -mx-3 mb-2">
        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
          <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-role">
            Role
          </label>
          <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight " id="grid-role" type="text" placeholder="" value="{{ $admin->StringRole ?? '-'}}" readonly>
        </div>
        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
          <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-outlet">
            Outlet
          </label>
          <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight " id="grid-outlet" type="text" placeholder=""  value="{{ $admin->outlet->name }}" readonly>
        </div>
      {{-- <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-zip">
          Zip
        </label>
        <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight " id="grid-zip" type="text" placeholder="90210">
      </div> --}}
    </div>

    <div class="flex flex-wrap my-6">
      <div class="w-full md:w-1/5 mb-6 md:mb-0">
        <img class="h-auto max-w-full rounded-lg" src="{{ ($admin->profile_image != null) ? asset($admin->profile_image) : asset('images/default_avatar2.jpg')}}" alt="" width="100%">
      </div>
    </div>

    <script>
      $( document ).ready(function() {

          $(document).on('click','.resend_btn', function(){
            let adminId = $(this).data('admin_id')
            let url = "{{ route('admin.admins.resend_email', ['id' => 'placeholder']) }}"
            url = url.replace('placeholder', adminId);

            Swal.fire({
                title: "Are you sure?",
                text: "The password will be reset and randomly generated. A new login crendential email will be sent to this account!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes!"
                }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: url,
                        method: "post",
                        data: {
                            _method: "post",
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response){
                            if(response.success){

                                Swal.fire(
                                'Sent!',
                                'The email has been sent.',
                                'success'
                                ).then(function(){
                                    window.location.reload(true);
                                })
                            }
                        }
                    });
                }
            });
          })

      })



    </script>


</x-app-layout>