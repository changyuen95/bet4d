
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-600 leading-tight bg-gradient">
            {{ __('Edit Admin Information') }}
        </h2>
    </x-slot>

<form class="mb-8" action="{{ route('admin.admins.update', $admin) }}" method="post" enctype="multipart/form-data">
  @csrf
  @method('PUT')
    <div class="flex flex-wrap -mx-3 my-6">
      <div class="w-full md:w-1/5 px-3 mb-6 md:mb-0">
        <img class="h-auto max-w-full rounded-lg" src="{{ ($admin->profile_image != null) ? asset($admin->profile_image) : asset('images/default_avatar2.jpg') }}" alt="" width="100%">
      </div>
      <div class="w-full md:w-4/5 px-3">
          <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="user_avatar">
              Upload Profile Image
          </label>
          <input class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none bg-neutral-200 dark:placeholder-gray-600" aria-describedby="user_avatar_help" id="user_avatar" name="user_avatar" type="file" accept=".jpg, .jpeg, .png" onchange="showProfileImg(this)">
          <p class="text-blue-500 text-xs italic pt-3">NOTE: Recommend Size 800 x 800 (px)</p>
          <img id="profile_img_preview" style="width:20%" class="mt-3"/>
      </div>
    </div>

    <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
          <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-name">
            Name
          </label>
          <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id="grid-name" type="text" placeholder="Name" name="name" value="{{ old('name', $admin->name)}}">
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
          <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-email" type="text" placeholder="Email" name="email" value="{{ old('email', $admin->email)}}">
          @error('email')
                <span class="invalid-feedback d-block text-red-500 text-xs italic" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
          @enderror
        </div>
    </div>

    <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full px-3">
          <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-phone">
            Phone Number
          </label>
          <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-phone" type="text" placeholder="Phone Number" name="phone_number" value="{{ old('phone_number',$admin->phone_e164)}}">
          {{-- <div class="flex">
            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
              +60
            </span>
            <input type="text" id="website-admin" class="rounded-none rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Phone Number" name="phone_number"  value="{{ old('phone_number',$admin->phone_e164)}}">
          </div> --}}
          {{-- <p class="text-gray-600 text-xs italic">Make it as long and as crazy as you'd like</p> --}}
          @error('phone_number')
                <span class="invalid-feedback d-block text-red-500 text-xs italic" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
          @enderror
        </div>
    </div>

    <div class="flex flex-wrap -mx-3 mb-2">
        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
          <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="admin-role">
            Role
          </label>
          <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" id="admin-role" type="text" placeholder="Role" name="role" value="{{ old('role',$admin->StringRole)}}" readonly>
        </div>
        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
          <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="admin-outlet">
            Outlet
          </label>
          <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" id="admin-outlet" type="text" placeholder="Klang Toto" name="outlet" value="{{ old('outlet',$admin->outlet->name)}}" readonly>
        </div>
    </div>

    <a class="btn btn-danger px-4 mt-3 mr-2" href="{{route('admin.admins.show', $admin->id )}}" role="button">Back</a>
    <button type="submit" class="btn btn-success px-4 mt-3 bg-success" role="button">Update</button>
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