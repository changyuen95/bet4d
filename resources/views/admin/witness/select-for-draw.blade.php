
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-600 leading-tight bg-gradient">
                {{ __('Select Witnesses for Draw') }}
            </h2>
            <div class="flex gap-2">
                @if($selectedWitnesses->count() > 0)
                    <a href="{{route('admin.witnesses.print')}}" target="_blank" class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-md px-4 py-2">Print Witness Form</a>
                @endif
                <a href="{{route('admin.witnesses.index')}}" class="focus:outline-none text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-md px-4 py-2">Back to List</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Current Draw Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-blue-700 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-blue-900">Current Draw: {{ $currentDraw->full_draw_no }}</h3>
                        <p class="text-sm text-blue-700">Draw Date: {{ \Carbon\Carbon::parse($currentDraw->expired_at)->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Currently Selected Witnesses -->
            @if($selectedWitnesses->count() > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Currently Selected Witnesses ({{ $selectedWitnesses->count() }})</h3>
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-green-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Position</th>
                                    <th scope="col" class="px-6 py-3">Name</th>
                                    <th scope="col" class="px-6 py-3">IC Number</th>
                                    <th scope="col" class="px-6 py-3">Phone</th>
                                    <th scope="col" class="px-6 py-3">Selected At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($selectedWitnesses as $index => $witness)
                                <tr class="bg-white border-b">
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-800 font-bold">
                                            {{ $witness->pivot->position ?? ($index + 1) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $witness->name }}</td>
                                    <td class="px-6 py-4">{{ $witness->formatted_ic }}</td>
                                    <td class="px-6 py-4">{{ $witness->phone ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($witness->pivot->selected_at)->format('d M Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Select Witnesses Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Select Witnesses from Database</h3>
                    
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.witnesses.save-selected') }}" method="POST">
                        @csrf
                        <input type="hidden" name="draw_id" value="{{ $currentDraw->id }}">
                        
                        <!-- Manager Selection Section -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <h4 class="text-base font-semibold mb-4 text-gray-800">Select Recorder & Certifier</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="recorded_by_id" class="block mb-2 text-sm font-medium text-gray-900">Recorded By <span class="text-red-600">*</span></label>
                                    <select id="recorded_by_id" name="recorded_by_id" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                        <option value="">Select Recorder</option>
                                        @foreach($recorders as $recorder)
                                            <option value="{{ $recorder->id }}" {{ old('recorded_by_id', $drawManager?->recorded_by_id) == $recorder->id ? 'selected' : '' }}>
                                                {{ $recorder->name }} ({{ $recorder->formatted_ic }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="certified_by_id" class="block mb-2 text-sm font-medium text-gray-900">Certified By <span class="text-red-600">*</span></label>
                                    <select id="certified_by_id" name="certified_by_id" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                        <option value="">Select Certifier</option>
                                        @foreach($certifiers as $certifier)
                                            <option value="{{ $certifier->id }}" {{ old('certified_by_id', $drawManager?->certified_by_id) == $certifier->id ? 'selected' : '' }}>
                                                {{ $certifier->name }} ({{ $certifier->formatted_ic }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">💡 To add new managers, go to Manager menu</p>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Select witnesses to participate in this draw. You can select multiple witnesses.</p>
                        </div>

                        <div class="relative overflow-x-auto mb-4" style="max-height: 500px; overflow-y: auto;">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 sticky top-0">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            <input type="checkbox" id="select-all" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded">
                                        </th>
                                        <th scope="col" class="px-6 py-3">Position</th>
                                        <th scope="col" class="px-6 py-3">Name</th>
                                        <th scope="col" class="px-6 py-3">IC Number</th>
                                        <th scope="col" class="px-6 py-3">Phone</th>
                                        <th scope="col" class="px-6 py-3">Past Participations</th>
                                    </tr>
                                </thead>
                                <tbody id="witness-tbody">
                                    @foreach($witnesses as $witness)
                                    @php
                                        $selectedWitness = $selectedWitnesses->firstWhere('id', $witness->id);
                                        $isSelected = $selectedWitness !== null;
                                        $currentPosition = $isSelected ? ($selectedWitness->pivot->position ?? '') : '';
                                    @endphp
                                    <tr class="bg-white border-b hover:bg-gray-50" data-witness-id="{{ $witness->id }}">
                                        <td class="px-6 py-4">
                                            <input type="checkbox" name="witness_ids[]" value="{{ $witness->id }}" 
                                                   class="witness-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded"
                                                   {{ $isSelected ? 'checked' : '' }}>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="number" name="positions[]" min="1" max="99" 
                                                   value="{{ $currentPosition }}"
                                                   class="position-input w-16 px-2 py-1 border border-gray-300 rounded text-center"
                                                   placeholder="#"
                                                   {{ $isSelected ? '' : 'disabled' }}>
                                        </td>
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $witness->name }}</td>
                                        <td class="px-6 py-4">{{ $witness->formatted_ic }}</td>
                                        <td class="px-6 py-4">{{ $witness->phone ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $witness->draws->count() }} times</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Save Selected Witnesses</button>
                            <a href="{{ route('admin.witnesses.index') }}" class="text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-assign positions for already selected witnesses that don't have positions
            autoAssignPositions();
        });

        // Select all functionality
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.witness-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
                togglePositionInput(checkbox);
            });
            autoAssignPositions();
        });

        // Toggle position input when checkbox changes
        function togglePositionInput(checkbox) {
            const row = checkbox.closest('tr');
            const positionInput = row.querySelector('.position-input');
            positionInput.disabled = !checkbox.checked;
            if (!checkbox.checked) {
                positionInput.value = '';
            }
        }

        // Auto-assign sequential positions
        function autoAssignPositions() {
            const checkedCheckboxes = document.querySelectorAll('.witness-checkbox:checked');
            let nextPosition = 1;
            
            checkedCheckboxes.forEach((checkbox) => {
                const row = checkbox.closest('tr');
                const positionInput = row.querySelector('.position-input');
                
                // Only auto-assign if the field is empty or has no value
                if (!positionInput.value || positionInput.value === '' || positionInput.value === '0') {
                    positionInput.value = nextPosition;
                }
                nextPosition++;
            });
        }

        // Update select-all checkbox when individual checkboxes change
        document.querySelectorAll('.witness-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allCheckboxes = document.querySelectorAll('.witness-checkbox');
                const checkedCheckboxes = document.querySelectorAll('.witness-checkbox:checked');
                document.getElementById('select-all').checked = allCheckboxes.length === checkedCheckboxes.length;
                
                togglePositionInput(this);
                autoAssignPositions();
            });
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const checkedCheckboxes = document.querySelectorAll('.witness-checkbox:checked');
            const positions = [];
            let hasError = false;

            checkedCheckboxes.forEach(checkbox => {
                const row = checkbox.closest('tr');
                const positionInput = row.querySelector('.position-input');
                const position = parseInt(positionInput.value);

                if (!position || position < 1) {
                    hasError = true;
                    positionInput.classList.add('border-red-500');
                } else {
                    positionInput.classList.remove('border-red-500');
                    positions.push(position);
                }
            });

            if (hasError) {
                e.preventDefault();
                alert('Please enter valid positions (1 or higher) for all selected witnesses.');
                return false;
            }

            // Check for duplicate positions
            const duplicates = positions.filter((item, index) => positions.indexOf(item) !== index);
            if (duplicates.length > 0) {
                e.preventDefault();
                alert('Duplicate positions found: ' + duplicates.join(', ') + '. Each witness must have a unique position.');
                return false;
            }
        });
    </script>
</x-app-layout>
