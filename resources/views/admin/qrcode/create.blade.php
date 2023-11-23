
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-600 leading-tight bg-gradient">
            {{ __('Create QR Code') }}
        </h2>
    </x-slot>

    <form class="w-full mb-8" action="{{route('admin.qrcodes.store')}}" class="form-horizontal" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-qrname">
                    QR Code Name
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id="grid-qrname" type="text" placeholder="Name" name="qr_name">
                @error('qr_name')
                    <span class="invalid-feedback d-block text-red-500 text-xs italic" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="w-full md:w-1/2 px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-qrcreadit">
                    Credit Amount <strong class="text-danger">*</strong>
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-qrcreadit" type="number" placeholder="e.g: 300" name="credit_amount" required>
                @error('credit_amount')
                <span class="invalid-feedback d-block text-red-500 text-xs italic" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-scan_limit">
                    Scan Limit <strong class="text-danger">*</strong>
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-scan_limit" type="number" name="scan_limit" placeholder="e.g 8">
                @error('scan_limit')
                <span class="invalid-feedback d-block text-red-500 text-xs italic" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-remark">
                    Remark
                </label>
                <textarea rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-200 dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500" id="grid-remark" name="remark" placeholder="Place the remark here..."></textarea>
                @error('remark')
                <span class="invalid-feedback d-block text-red-500 text-xs italic" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-qrstatus">
                    Status <strong class="text-danger">*</strong>
                </label>
                <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-qrstatus" name="status" required>
                    <option value="1" {{old('status') == '1' ? 'selected' : ''}}>Active</option>
                    <option value="0" {{old('status') == '0' ? 'selected' : ''}}>Inactive</option>
                </select>
            </div>
        </div>
        <a class="btn btn-danger px-4 mt-3 mr-2" href="{{route('admin.qrcodes.index')}}" role="button">Back</a>
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