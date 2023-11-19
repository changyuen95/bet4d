
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-600 leading-tight bg-gradient">
                {{ __('Admin List') }}
            </h2>
            @if(Auth::user()->role == "super_admin")
                <a href="{{route('admin.admins.create')}}" class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-md px-4 py-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">Create Admin</a>
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
                        Name
                    </th>
                    <th scope="col" class="text-base px-6 py-4">
                        Email
                    </th>
                    <th scope="col" class="text-base px-6 py-4">
                        Role
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
                @foreach($admins as $index => $admin)
                <tr class="odd:bg-white odd:dark:bg-gray-100 even:bg-gray-50 even:dark:bg-gray-200 border-b dark:border-gray-700 dark:hover:bg-gray-400">

                    <td class="px-6 py-4">
                        {{ $index+1 }}
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-black">
                        {{ $admin->name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $admin->email }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $admin->role }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $admin->status }}
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.admins.show', $admin->id) }}" class="font-medium btn btn-info mr-2"><i class="fa fa-eye"></i></a>


                        @if(Auth::user()->role == 'super_admin')
                            <a href="{{ route('admin.admins.edit', $admin->id) }}" class="font-large btn btn-warning mr-2"><i class="fa fa-edit"></i></a>
                            <button class="font-large btn btn-danger delete_admin" data-admin_id="{{$admin->id}}"><i class="fa fa-trash"></i></button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
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


        $(document).on('click', '.delete_admin', function(){

            let adminId = $(this).data('admin_id')
            let url = "{{ route('admin.admins.destroy', ['admin' => 'placeholder']) }}"
            url = url.replace('placeholder', adminId);

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
                                'The admin has been deleted.',
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