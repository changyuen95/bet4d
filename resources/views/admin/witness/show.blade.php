
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-600 leading-tight bg-gradient">
                {{ __('Witness Details') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{route('admin.witnesses.edit', $witness->id)}}" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-md px-4 py-2">Edit</a>
                <a href="{{route('admin.witnesses.index')}}" class="focus:outline-none text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-md px-4 py-2">Back to List</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Witness Information -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Witness Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $witness->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">IC Number</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $witness->formatted_ic }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $witness->phone ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Total Participations</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $witness->draws->count() }} draws</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Address</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $witness->address ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Remarks</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $witness->remarks ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Created At</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $witness->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $witness->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Participation History -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Participation History</h3>
                    
                    @if($witness->draws->count() > 0)
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Draw No</th>
                                        <th scope="col" class="px-6 py-3">Draw Date</th>
                                        <th scope="col" class="px-6 py-3">Selected At</th>
                                        <th scope="col" class="px-6 py-3">Signed</th>
                                        <th scope="col" class="px-6 py-3">Signed At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($witness->draws as $draw)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $draw->full_draw_no }}</td>
                                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($draw->expired_at)->format('d M Y H:i') }}</td>
                                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($draw->pivot->selected_at)->format('d M Y H:i') }}</td>
                                        <td class="px-6 py-4">
                                            @if($draw->pivot->has_signed)
                                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Yes</span>
                                            @else
                                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">No</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $draw->pivot->signed_at ? \Carbon\Carbon::parse($draw->pivot->signed_at)->format('d M Y H:i') : '-' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No participation history yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
