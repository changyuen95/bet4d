
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-600 leading-tight bg-gradient">
            {{ __('Create New Admin') }}
        </h2>
    </x-slot>

    <form class="w-full mb-8" action="{{route('admin.qrcodes.store')}}" class="form-horizontal" method="POST"  enctype="multipart/form-data">
        @csrf
        <div class="flex flex-wrap -mx-3 mb-6">
          <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-name">
              Name
            </label>
            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id="grid--name" type="text" placeholder="Name" name="name" required>
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
            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-email" type="text" placeholder="Email" name="email" required>
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
              Phone Number
            </label>
            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="phone_no" type="text" name="phone_number" placeholder="Phone Number">
            @error('phone_number')
            <span class="invalid-feedback d-block text-red-500 text-xs italic" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
            {{-- <div class="flex">
              <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-s-md dark:bg-gray-300 dark:text-gray-600">
                +60
              </span>
              <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="phone_no" type="text" placeholder="Phone Number">
            </div> --}}
            {{-- <p class="text-gray-600 text-xs italic">Make it as long and as crazy as you'd like</p> --}}
          </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-password">
                    Password
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-password" type="password" placeholder="*********" name="password" required>
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
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-cpassword">
                    Confirm Password
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-cpassword" type="password" placeholder="*********" name="password_confirmation" required>
                {{-- <p class="text-gray-600 text-xs italic">Make it as long and as crazy as you'd like</p> --}}
                @error('password_confirmation')
                    <span class="invalid-feedback d-block text-red-500 text-xs italic" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-10">
            <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-role">
                    Role
                </label>
                <div class="relative">
                <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-role" name="role" required>
                    <option value="super_admin" {{old('role') == 'superadmin' ? 'selected' : ''}}>Superadmin</option>
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
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-outlet">
                    Outlet
                </label>
                {{-- <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-city" type="text" placeholder="Albuquerque" name="outlet"> --}}
                <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-outlet" name="outlet" required>

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
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="user_avatar">
                    Profile Image
                </label>
                <input class="block w-full p-2 text-sm text-gray-700 border border-gray-300 rounded cursor-pointer py-3 bg-gray-50 dark:text-gray-400 focus:outline-none bg-neutral-200 dark:placeholder-gray-600" aria-describedby="user_avatar_help" id="user_avatar" name="user_avatar" type="file" accept=".jpg, .jpeg, .png" onchange="showProfileImg(this)">
                <p class="text-blue-500 text-xs italic pt-2">NOTE: Recommend Size 800 x 800 (px)</p>
                @error('user_avatar')
                <span class="invalid-feedback d-block text-red-500 text-xs italic" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
                <img id="profile_img_preview" style="width:20%" class="mt-3"/>
            </div>
        </div>
        <a class="btn btn-danger px-4 mt-3 mr-2" href="{{route('admin.admins.index')}}" role="button">Back</a>
        <button type="submit" class="btn btn-success px-4 mt-3 bg-success" role="button">Save</button>
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


</x-app-layout>