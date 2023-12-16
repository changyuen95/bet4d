
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-600 leading-tight bg-gradient">
                {{ __('Ticket List') }}
            </h2>
        </div>
    </x-slot>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-5">
        <table id="ticket_table" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-800 table-responsive pt-4">
            <input type="hidden" value="{{$date}}" name="calendar_date" id="calendar_date">
            <thead class="text-xs text-gray-700 uppercase bg-cyan-100 dark:bg-cyan-100 dark:text-gray-900 py-10">
                <tr>
                    <th scope="col" class="text-base px-6 py-4">
                        No.
                    </th>
                    <th scope="col" class="text-base px-6 py-4">
                        Buyer
                    </th>
                    <th scope="col" class="text-base px-6 py-4">
                        Game
                    </th>
                    <th scope="col" class="text-base px-6 py-4">
                        Purhace Time
                    </th>
                    {{-- <th scope="col" class="text-base px-6 py-4">
                        Number
                    </th> --}}
                </tr>
            </thead>
            <tbody>
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


    #admin_table tbody tr {
        height: 60px;
    }
</style>


<script>
    $( document ).ready(function() {

        var calendarDate = $('#calendar_date').val();
console.log(calendarDate);
        var table= new DataTable('#ticket_table', {
            processing: true,
                serverSide: true,
                pageLength:10,
                stateSave: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf','print'
                ],
                ajax: ({
                    url:"{{ route('admin.draws.ticket_list_datatable') }}",
                    data: function(d){
                        d.calendarDate = calendarDate;
                        $('#search').val(d.search.value);

                    },
                }),
                columns: [
                        {data: 'DT_RowIndex',searchable:false,orderable:false},
                        {data: 'users.name',searchable:true,orderable:true},
                        {data: 'games.name',searchable:true,orderable:true},
                        {data: 'tickets.created_at',searchable:true,orderable:true},
                    ],
                    order: [[3, "desc"]]
        })
    })
</script>


</x-app-layout>