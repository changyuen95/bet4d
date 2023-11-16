
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Admin') }}
        </h2>
    </x-slot>

    <form class="w-full" action="{{route('admin.admins.store')}}" class="form-horizontal" method="POST"  enctype="multipart/form-data">
        @csrf
        <div class="flex flex-wrap -mx-3 mb-6">
          <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-name">
              Name
            </label>
            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id="grid--name" type="text" placeholder="Name" name="name">
            @error('name')
                <span class="invalid-feedback d-block text-red-500 text-xs italic" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
          <div class="w-full md:w-1/2 px-3">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-email">
              Email
            </label>
            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-email" type="text" placeholder="Email" name="email">
            @error('email')
            <span class="invalid-feedback d-block text-red-500 text-xs italic" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
          <div class="w-full px-3">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-password">
              Password
            </label>
            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-password" type="password" placeholder="*********" name="password">
            {{-- <p class="text-gray-600 text-xs italic">Make it as long and as crazy as you'd like</p> --}}
            @error('password')
            <span class="invalid-feedback d-block text-red-500 text-xs italic" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-password">
                    Confirm Password
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-password" type="password" placeholder="*********" name="password_confirmation">
                {{-- <p class="text-gray-600 text-xs italic">Make it as long and as crazy as you'd like</p> --}}
                @error('password_confirmation')
                    <span class="invalid-feedback d-block text-red-500 text-xs italic" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
          </div>
        <div class="flex flex-wrap -mx-3 mb-2">
          <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-state">
              Role
            </label>
            <div class="relative">
              <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state" name="role">
                <option value="superadmin" {{old('role') == 'superadmin' ? 'selected' : ''}}>Superadmin</option>
                <option value="operator" {{old('role') == 'operator' ? 'selected' : ''}}>Operator</option>
              </select>
            </div>
            @error('role')
                <span class="invalid-feedback d-block text-red-500 text-xs italic" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
          <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-city">
              Outlet
            </label>
            {{-- <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-city" type="text" placeholder="Albuquerque" name="outlet"> --}}
            <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state" name="outlet">

                @forelse($outlets as $outlet)
                    <option value="{{ $outlet->id }}" {{old('outlet') == $outlet->id ? 'selected' : ''}}> {{ $outlet->name }}</option>
                @empty
                    <option value="">No outlet record</option>
                @endforelse
            </select>
            @error('outlet')
                <span class="invalid-feedback d-block text-red-500 text-xs italic" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
          {{-- <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-zip">
              Zip
            </label>
            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="text" placeholder="90210">
          </div> --}}
        </div>
        <a href="{{route('admin.admins.index')}}" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-3 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Back</a>
        <button type="submit" class="my-5 focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Save</button>
      </form>


</x-app-layout>