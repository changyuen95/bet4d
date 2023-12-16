
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-600 leading-tight bg-gradient">
            {{ __('Create New Special Draw') }}
        </h2>
    </x-slot>

    <form class="w-full mb-8" action="{{route('admin.draws.store')}}" class="form-horizontal" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-full md:w-1/2 px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-scan_limit">
                    Special Draw Date <strong class="text-danger">*</strong>
                </label>
                <div class="input-group date">
                    <input type="text" class="form-control datepicker appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" placeholder="Pick a date" name="draw_date" autocomplete="off"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                </div>
                @error('draw_date')
                <span class="invalid-feedback d-block text-red-500 text-xs italic" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-qrname">
                    Platform
                </label>
                <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="platform" name="platform" required>
                    @foreach($platforms as $platform)
                        <option value="{{ $platform->id}}" {{old('platform') == $platform->id ? 'selected' : ''}}>{{ $platform->name }}</option>
                    @endforeach
                </select>
                @error('platform')
                    <span class="invalid-feedback d-block text-red-500 text-xs italic" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            {{-- <div class="w-full md:w-1/2 px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-qrcreadit">
                    Draw expired date <strong class="text-danger">*</strong>
                </label>
                <div class="input-group date">
                    <input type="text" class="form-control datepicker appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" placeholder="Pick a date" name="draw_expired_date" autocomplete="off"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                </div>
                @error('credit_amount')
                <span class="invalid-feedback d-block text-red-500 text-xs italic" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div> --}}
        </div>

        <a class="btn btn-danger px-4 mt-3 mr-2" href="{{route('admin.qrcodes.index')}}" role="button">Back</a>
        <button type="submit" class="btn btn-success px-4 mt-3 bg-success" role="button">Save</button>
      </form>


      {{-- Datepicker CDN --}}
      <link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">
      <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/js/bootstrap-datepicker.min.js"></script>
      
      <script>

        $(document).ready(function() {

            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(today.getDate() + 1);

            $('.datepicker').datepicker({
                format: 'dd-M-yyyy',
                orientation: "bottom",
                autoclose: true,
                startDate: tomorrow
            });
        });
        


      </script>


</x-app-layout>