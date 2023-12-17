<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-900">
            {{ __('Profile Image') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-500">
            {{ __("Update your account's profile image.") }}
        </p>
    </header>

    <form method="post" action="{{ route('admin.admin.update_profile_img') }}" class="mt-6 space-y-6"  enctype="multipart/form-data">
        @csrf
        {{-- @method('patch') --}}

        <div>
            <div class="flex flex-wrap -mx-3 my-6">
                <div class="w-full md:w-1/5 px-3 mb-6 md:mb-0">
                  <img class="h-auto max-w-full rounded-lg" src="{{ (Auth::user()->profile_image) ? asset(Auth::user()->profile_image) : asset('images/default_avatar2.jpg') }}" alt="Can't load" width="100%">
                </div>
                <div class="w-full md:w-4/5 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="admin_avatar">
                        Upload Profile Image
                    </label>
                    <input class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-body-tertiary dark:text-gray-400 focus:outline-none bg-neutral-200 dark:placeholder-gray-600" aria-describedby="user_avatar_help" id="admin_avatar" name="admin_avatar" type="file" accept=".jpg, .jpeg, .png" onchange="showProfileImg(this)">
                    @error('admin_avatar')
                    <span class="invalid-feedback d-block text-red-500 text-xs italic" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <p class="text-blue-500 text-xs italic pt-1">NOTE: Recommend Size 800 x 800 (px)</p>
                    <img id="profile_img_preview" style="width:20%" class="mt-3"/>
                </div>
              </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-0 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-500 dark:focus:ring-green-800 dark:focus:bg-green-400">{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>


    <script>
          function showProfileImg(fileInput) {
            var reader = new FileReader();
            reader.onload = function(){
            var output = document.getElementById('profile_img_preview');
            output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        };
    </script>
</section>
