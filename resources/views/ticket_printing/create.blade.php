<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-600 leading-tight">
            {{ __('Create Ticket Printing') }}
        </h2>
    </x-slot>

    <div class="container mx-auto py-6">
<form id="ticketForm" method="POST" action="{{ route('admin.ticket_printing.store') }}">
            @csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
               <div>
        <label for="serial_number" class="block text-sm font-medium text-gray-700">Serial Number</label>
        <input style="text-transform:uppercase;" type="text" id="serial_number" name="serial_number"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
            placeholder="example:xxxx-xxxx-xxxx-xxxx" />
    </div>

    <!-- Draw Number -->
    <div>
        <label for="draw_id" class="block text-sm font-medium text-gray-700">Draw Number</label>
        <select id="draw_id" name="draw_id"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">Select Draw</option>
                    @foreach ($draws as $draw)
                        <option value="{{ $draw->id }}">
                            {{ $draw->draw_no . '/' . $draw->year }} - {{ \Carbon\Carbon::parse($draw->expired_at)->format('D, d-M-Y') }}
                        </option>
                    @endforeach
                 </select>
    </div>
</div>

<div class="mb-4">
               <label for="ticket_type" class="block text-sm font-medium text-gray-700">Ticket Type</label>
        <select id="ticket_type" name="ticket_type"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <!--<option value="">Select type</option>-->
                        <option value="Straight">Straight</option>
                        <option value="Box">Box</option>
                        <option value="E-BOX">E-BOX</option>
                 </select>
            </div>


            <div class="mb-4">
    <label for="printed_at" class="block font-medium text-sm text-gray-700">Date & Time</label>
    <input type="datetime-local"
           name="printed_at"
           id="printed_at"
           value="{{ now()->format('Y-m-d\TH:i:s') }}"
           step="1"  {{-- ✅ Enables seconds --}}
           class="form-control w-full mt-1"
           required>
</div>


            <div class="mb-4">
                <label for="outlet_number" class="block font-medium text-sm text-gray-700">Outlet Number</label>
                <input type="text" name="outlet_number" id="outlet_number" class="form-control w-full mt-1" required>
            </div>

            <hr class="my-4">
            <label class="block font-semibold text-gray-700 mb-2">Ticket Numbers</label>
            
            <div id="ticket-rows" class="space-y-2">
    <div class="ticket-row flex grid-cols-12 gap-2 items-center" style="margin-bottom:10px" data-index="0">
        <div class="col-span-4">
            <input type="text" name="tickets[0][number]" placeholder="4D Number" maxlength="4" required class="form-input w-full border rounded px-3 py-2">
        </div>
        <div class="col-span-4">
            <input type="number" name="tickets[0][big]" placeholder="Big" step="0.01" required class="form-input w-full border rounded px-3 py-2">
        </div>
        <div class="col-span-4">
            <input type="number" name="tickets[0][small]" placeholder="Small" step="0.01" required class="form-input w-full border rounded px-3 py-2">
        </div>
    </div>
</div>

<!-- Add Row -->
<button type="button" id="add-row" class="bg-blue-500 hover:bg-blue-600 text-gray-100 text-sm px-4 py-1 rounded">
    + Add Row
</button>

            <div class="mb-4">
                <label for="subtotal" class="block font-medium text-sm text-gray-700">Subtotal(excluded tax)</label>
                <input type="number" step="0.01" min="0.01" value=0 name="subtotal" id="subtotal" class="form-control w-full mt-1" required>
            </div>

<div class="mt-6 space-x-2">
<div class="mt-6 flex space-x-2">
    <button type="submit" name="action" value="save" style="margin-right:5px" class="bg-green-600 hover:bg-green-700 text-gray-100 font-semibold px-6 py-2 rounded shadow">
        Save
    </button>
<button type="submit" name="action" value="print" id="printButton"
        class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold px-6 py-2 rounded shadow">
        Save & Print
    </button>
</div>

</div>



        </form>
    </div>

  <script>
let rowCount = 1;

function updateRowIndices() {
    document.querySelectorAll('#ticket-rows .ticket-row').forEach((row, index) => {
        row.setAttribute('data-index', index);
        row.querySelectorAll('input').forEach(input => {
            if (input.name.includes('[number]')) input.name = `tickets[${index}][number]`;
            if (input.name.includes('[big]')) input.name = `tickets[${index}][big]`;
            if (input.name.includes('[small]')) input.name = `tickets[${index}][small]`;
        });
    });
}

document.getElementById('add-row').addEventListener('click', function () {
    if (rowCount >= 6) return;

    const container = document.getElementById('ticket-rows');
    const row = document.createElement('div');
    row.className = 'ticket-row flex grid-cols-12 gap-2 items-center mb-2';
    row.setAttribute('data-index', rowCount);

    row.innerHTML = `
        <div class="col-span-4">
            <input type="text" name="tickets[${rowCount}][number]" class="form-control w-full" placeholder="4D Number" maxlength="4" required>
        </div>
        <div class="col-span-4">
            <input type="number" name="tickets[${rowCount}][big]" class="form-control w-full" placeholder="Big" step="0.01" required>
        </div>
        <div class="col-span-4 flex gap-2">
            <input type="number" name="tickets[${rowCount}][small]" class="form-control w-full" placeholder="Small" step="0.01" required>
<button type="button" class="remove-row bg-red-500 hover:bg-red-600 text-gray-100 px-2 rounded">
    ×
</button>
        </div>
    `;

    container.appendChild(row);
    rowCount++;
});

document.getElementById('ticket-rows').addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-row')) {
        e.target.closest('.ticket-row').remove();
        rowCount--;
        updateRowIndices();
    }
});
</script>


@push('scripts')
<script>
    document.getElementById('printButton').addEventListener('click', function () {
        document.getElementById('ticketForm').setAttribute('target', '_blank');
    });

    document.querySelector('button[name="action"][value="save"]').addEventListener('click', function () {
        document.getElementById('ticketForm').removeAttribute('target');
    });
</script>
@endpush

</x-app-layout>
