<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-500">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('admin.verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('admin.profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')
        <div>
            <x-input-label for="name" :value="__('Name')" class="mt-1 text-sm text-gray-600 dark:text-gray-500"/>
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-body-tertiary text-gray-900 dark:text-gray-900 " :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="mt-1 text-sm text-gray-600 dark:text-gray-500"/>
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full bg-body-tertiary text-gray-900 dark:text-gray-900" :value="old('email', $user->email)" required autocomplete="email" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="phone" :value="__('Phone Number')" class="mt-1 text-sm text-gray-600 dark:text-gray-500"/>
            <x-text-input id="phone" name="phone_num" type="text" class="mt-1 block w-full bg-body-tertiary text-gray-900 dark:text-gray-900 " :value="old('phone_num', $user->phone_e164)" required autofocus autocomplete="phone_num" readonly/>
            <x-input-error class="mt-2" :messages="$errors->get('phone_num')" />
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
