
<x-app-layout>
    <x-slot name="header">
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-600 leading-tight bg-gradient">
            {{ __('QR Code Information') }}
        </h2>

        @if(Auth::user()->role == 'super_admin')
          <div class="inline-flex">
            <button class="font-large btn btn-warning text-light print_qr mr-2" data-qr_id="{{$qrCode->id}}">Print QR Code</button>
            <a href="{{route('admin.qrcodes.edit', $qrCode->id)}}" class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-md px-4 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 mr-2">Edit Detail</a>
            <button class="font-large btn btn-danger delete_qr" data-qr_id="{{$qrCode->id}}">Delete</button>
          </div>
        @endif
      </div>
    </x-slot>

    <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
          <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="qrname">
              QR Code Name
          </label>
          <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight" id="qrname" type="text" placeholder="" value="{{ $qrCode->name ?? '-'}}" readonly>
          {{-- <p class="text-red-500 text-xs italic">Please fill out this field.</p> --}}
        </div>
        <div class="w-full md:w-1/2 px-3">
          <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="qrcredit">
              Credit
          </label>
          <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight " id="qrcredit" type="text" placeholder="" value="{{ $qrCode->credit ?? '-'}}" readonly>
        </div>
    </div>

    <div class="flex flex-wrap -mx-3 mb-6">
      <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="qr_scanlimit">
            Scan Limit
        </label>
        <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight" id="qr_scanlimit" type="text" placeholder="" value="{{ $qrCode->scan_limit ?? '-'}}" readonly>
      </div>
      <div class="w-full md:w-1/2 px-3">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="qr_status">
            Status
        </label>
        <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight " id="qr_status" type="text" placeholder="" value="{{ $qrCode->string_status ?? '-'}}" readonly>
      </div>
    </div>

    <div class="flex flex-wrap -mx-3 mb-6">
      <div class="w-full px-3">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="qr_remark">
            Remark
        </label>
        <textarea class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight " id="qr_remark" type="text" placeholder="" readonly>{{ $qrCode->remark ?? '-'}}</textarea>
      </div>
    </div>

    <div class="form-group">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="qr_status">
        QR Code
      </label>
      <p>
        <a class="trigger-modal" url="http://api.qrserver.com/v1/create-qr-code/?data={{ $qrCode->id }}&size=1000x1000" title="QR Code">
          <img src="http://api.qrserver.com/v1/create-qr-code/?data={{ $qrCode->id }}&size=100x100" width="200px" alt="">
        </a>
      </p>

    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.2/jQuery.print.js" integrity="sha512-BaXrDZSVGt+DvByw0xuYdsGJgzhIXNgES0E9B+Pgfe13XlZQvmiCkQ9GXpjVeLWEGLxqHzhPjNSBs4osiuNZyg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
      $( document ).ready(function() {

          $(document).on('click','.delete_qr', function(){
            let qRId = $(this).data('qr_id')
            let url = "{{ route('admin.qrcodes.destroy', ['qrcode' => 'placeholder']) }}"
            url = url.replace('placeholder', qRId);

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: url,
                        method: "post",
                        data: {
                            _method: "delete",
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response){
                            if(response.success){

                                Swal.fire(
                                'Deleted!',
                                'The QR Code has been deleted.',
                                'success'
                                ).then(function(){
                                    window.location.href = '{{ route("admin.qrcodes.index") }}';
                                })
                            }
                        }
                    });
                }
            });
          });


          $('.print_qr').on('click', function() {

              let id = $(this).data('qr_id');
              let url = "{{ route('admin.qrcodes.qr_print', ['id' => 'placeholder']) }}";
              url = url.replace('placeholder', id);

              $.ajaxSetup({
                url: url,
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                },
                beforeSend: function() {
                console.log('printing ...');
                },
                complete: function() {
                console.log('printed!');
                }
              });

                $.ajax({
                success: function(viewContent) {
                  $.print(viewContent); // This is where the script calls the printer to print the viwe's content.
                }
              });
          });

      })



    </script>


</x-app-layout>