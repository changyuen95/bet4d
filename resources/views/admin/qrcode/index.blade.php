
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-600 leading-tight bg-gradient">
                {{ __('QR Code List') }}
            </h2>
            @if(Auth::user()->role == "super_admin")
                <a href="{{route('admin.qrcodes.create')}}" class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-md px-4 py-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">Create QR code</a>
            @endif
        </div>
    </x-slot>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-5">
        <table id="admin_table" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-800 table-responsive pt-4">
            <thead class="text-xs text-gray-700 uppercase bg-cyan-100 dark:bg-cyan-100 dark:text-gray-900 py-10">
                <tr>
                    <th scope="col" class="text-base px-6 py-4">
                        No
                    </th>
                    <th scope="col" class="text-base px-6 py-4">
                        QR Code Name
                    </th>
                    <th scope="col" class="text-base px-6 py-4">
                        Scan Limit
                    </th>
                    <th scope="col" class="text-base px-6 py-4">
                        Credit
                    </th>
                    <th scope="col" class="text-base px-6 py-4">
                        Remark
                    </th>
                    <th scope="col" class="text-base px-6 py-4">
                        Status
                    </th>
                    <th scope="col" class="text-base px-6 py-4">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($qrCodes as $index => $qrCode)
                <tr class="odd:bg-white odd:dark:bg-gray-100 even:bg-gray-50 even:dark:bg-gray-200 border-b dark:border-gray-700 dark:hover:bg-gray-400">

                    <td class="px-6 py-4">
                        {{ $index+1 }}
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-black">
                        {{ $qrCode->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $qrCode->scan_limit ?? '-' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $qrCode->credit }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $qrCode->remark ?? '-' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $qrCode->string_status ?? '-' }}
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.qrcodes.show', $qrCode->id) }}" class="font-medium btn btn-info mr-2"><i class="fa fa-eye"></i></a>


                        {{-- @if(Auth::user()->role == 'super_admin') --}}
                            <a href="{{ route('admin.qrcodes.edit', $qrCode->id) }}" class="font-large btn btn-warning mr-2"><i class="fa fa-edit"></i></a>
                            <button class="font-large btn btn-danger delete_qrC mr-2" data-admin_id="{{$qrCode->id}}"><i class="fa fa-trash"></i></button>
                        {{-- @endif --}}

                        

                        <a class="btn btn-success trigger-modal" url="http://api.qrserver.com/v1/create-qr-code/?data={{ $qrCode->id }}&size=1000x1000" title="QR Code"><i
                            class="fa fa-qrcode text-white" aria-hidden="true"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title modal-title-library"></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="bigger-image" src="{{asset('images/sample/no_image_available.jpeg')}}"
                        width="100%" alt="">
                </div>
            </div>
        </div>
    </div>


<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>


<style>
    .dataTables_filter{
        margin-top: 5px;
        margin-right: 5px;
    }

    .dataTables_length{
        margin-top: 12px;
        margin-left: 8px;
    }

    .dataTables_wrapper .dataTables_length select{
        padding-right: 20px;
        padding-left: 10px;
    }

    .dataTables_info{
        padding-left: 10px;
    }

    .dataTables_wrapper .dataTables_paginate
    {
        padding-top: 5px;
        padding-bottom: 5px;
    }
</style>


<script>
    $( document ).ready(function() {
        let table = new DataTable('#admin_table');

        $(document).on('click', '.trigger-modal', function(e){
            e.preventDefault();
			var source = $(this).attr('url');
			var title = $(this).attr('title');

			if (source != 'undefined') {
				$('#bigger-image').attr('src', source);
				$('#exampleModal').modal('show');
				$('.modal-title').text(title);
			}
        })

        $(document).on('click', '.delete_qrC', function(){

            let qrId = $(this).data('admin_id')
            let url = "{{ route('admin.qrcodes.destroy', ['qrcode' => 'placeholder']) }}"
            url = url.replace('placeholder', qrId);

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
                            _method: "DELETE",
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response){
                            if(response.success){

                                Swal.fire(
                                'Deleted!',
                                'The QR Code has been deleted.',
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