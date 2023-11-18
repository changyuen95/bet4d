<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-500">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('admin.password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="current_password" :value="__('Current Password')" class="mt-1 text-sm text-gray-600 dark:text-gray-500"/>
            <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full bg-body-tertiary text-gray-900 dark:text-gray-900" autocomplete="current-password" placeholder="************"/>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('New Password')" class="mt-1 text-sm text-gray-600 dark:text-gray-500"/>
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full bg-body-tertiary text-gray-900 dark:text-gray-900" autocomplete="new-password" placeholder="************"/>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="mt-1 text-sm text-gray-600 dark:text-gray-500"/>
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full bg-body-tertiary text-gray-900 dark:text-gray-900" autocomplete="new-password" placeholder="************"/>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
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
</section>
