<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-600 leading-tight">
                {{ __('Ticket Printing') }}
            </h2>
            <a href="{{ route('admin.ticket_printing.create') }}" class="btn btn-primary btn-sm">Create New</a>
        </div>
    </x-slot>

    <div class="container mx-auto py-6">
        <div class="overflow-x-auto bg-white shadow-md rounded-lg p-4">
            
            @if($tickets->count())

            
            <table id="ticketTable" class="table-auto w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="p-2">#</th>
                        <th class="p-2">Serial</th>
                        <th class="p-2">Draw No</th>
                        <th class="p-2">Draw Date</th>
                        <th class="p-2">Outlet No.</th>
                        <th class="p-2">Created At</th>
                        <th class="p-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                        <tr class="border-b">
                            <td class="p-2">{{ $loop->iteration }}</td>
                            <td class="p-2">{{ $ticket->serial_number }}</td>
                            <td class="p-2">{{ ($ticket->draw->draw_no ?? '-') . '/'. ($ticket->draw->year ?? '-') }}</td>
                            <td class="p-2">{{ $ticket->draw->created_at  ?  $ticket->draw->created_at->format('d-M-Y') : '-' }}</td>
                            <td class="p-2">{{ $ticket->outlet_number }}</td>
                            <td class="p-2">{{ $ticket->created_at->format('Y-m-d H:i') }}</td>
                            <td class="p-2">
                                <a href="{{ route('admin.ticket_printing.print', $ticket->id) }}" target="_blank" class="btn btn-info btn-sm">Print</a>
                            
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <div class="text-center text-gray-500 py-6">No tickets found.</div>
            @endif
        </div>
    </div>
     @push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    @endpush
     @push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#ticketTable').DataTable({
                search: {
                    search: '' // your default filter
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
