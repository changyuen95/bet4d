
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-600 leading-tight bg-gradient">
                {{ __('Witness List') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{route('admin.witnesses.select-for-draw')}}" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-md px-4 py-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-900">Select Witnesses for Draw</a>
                <a href="{{route('admin.witnesses.create')}}" class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-md px-4 py-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">Create Witness</a>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.witnesses.index') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                            <input type="text" id="name" name="name" value="{{ request('name') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Search by name">
                        </div>
                        <div class="flex-1 min-w-[200px]">
                            <label for="ic" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">IC Number</label>
                            <input type="text" id="ic" name="ic" value="{{ request('ic') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="xxx,xxx,xx,xx,xxx">
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Filter</button>
                            <a href="{{ route('admin.witnesses.index') }}" class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-800">
                    <thead class="text-xs text-gray-700 uppercase bg-cyan-100 dark:bg-cyan-100 dark:text-gray-900">
                        <tr>
                            <th scope="col" class="text-base px-6 py-4">No</th>
                            <th scope="col" class="text-base px-6 py-4">Name</th>
                            <th scope="col" class="text-base px-6 py-4">IC Number</th>
                            <th scope="col" class="text-base px-6 py-4">Phone</th>
                            <th scope="col" class="text-base px-6 py-4">Total Participations</th>
                            <th scope="col" class="text-base px-6 py-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($witnesses as $index => $witness)
                        <tr class="odd:bg-white odd:dark:bg-gray-100 even:bg-gray-50 even:dark:bg-gray-200 border-b dark:border-gray-700">
                            <td class="px-6 py-4">{{ $witnesses->firstItem() + $index }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $witness->name }}</td>
                            <td class="px-6 py-4">{{ $witness->formatted_ic }}</td>
                            <td class="px-6 py-4">{{ $witness->phone ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $witness->draws->count() }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.witnesses.show', $witness->id) }}" class="font-medium text-blue-600 hover:underline">View</a>
                                    <a href="{{ route('admin.witnesses.edit', $witness->id) }}" class="font-medium text-green-600 hover:underline">Edit</a>
                                    <form action="{{ route('admin.witnesses.destroy', $witness->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this witness?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-600 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No witnesses found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $witnesses->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
