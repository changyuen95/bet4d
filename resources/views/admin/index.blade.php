
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Admin List') }}
            </h2>
            <a href="{{route('admin.admins.create')}}" class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-3 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">Create Admin</a>
        </div>
    </x-slot>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table id="admin_table" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-800">
            <thead class="text-xs text-gray-700 uppercase bg-cyan-100 dark:bg-cyan-100 dark:text-gray-900 py-10">
                <tr>
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
                @foreach($admins as $admin)
                <tr class="odd:bg-white odd:dark:bg-gray-100 even:bg-gray-50 even:dark:bg-gray-200 border-b dark:border-gray-700 dark:hover:bg-gray-400">

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
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>


<!-- DataTables -->


<script>
    $( document ).ready(function() {
        console.log('g');
    })
</script>


</x-app-layout>