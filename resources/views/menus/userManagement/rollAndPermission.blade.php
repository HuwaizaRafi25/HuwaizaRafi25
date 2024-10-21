<x-app-layout>
    <style>
        #assignRoleModal {
            z-index: 101;
        }

        #assignRoleModal.show {
            display: flex
        }

        #assignPermissionModal {
            z-index: 101;
        }

        #assignPermissionModal.show {
            display: flex
        }

        #unassignRoleModal.show {
            display: flex;
            z-index: 101
        }

        #unassignPermissionModal.show {
            display: flex;
            z-index: 101
        }
    </style>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Roles and Permissions') }}
        </h2>
    </x-slot>

    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path
                            d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 9 4-4-4-4" />
                    </svg>
                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">User Management</span>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 9 4-4-4-4" />
                    </svg>
                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Roles & Permissions</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-10">

                <!-- Roles Table -->
                <h3 class="font-semibold text-lg text-gray-700">Roles</h3>
                @if ($roles->isEmpty())
                    <p>No roles found.</p>
                @else
                    <div>
                        <div class="flex gap-4 my-2 flex-wrap">
                            @foreach ($roles as $role)
                                <div
                                    class="p-1 px-5 bg-blue-400 text-white text-base font-medium w-max rounded-lg shadow-md transition duration-150 ease-in-out cursor-pointer transform hover:bg-blue-500 hover:shadow-lg hover:scale-105">
                                    {{ $role->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <hr class="my-8 border-t-2 border-slate-400">

                <!-- Permissions Table -->
                <h3 class="font-semibold text-lg text-gray-700">Permissions</h3>
                @if ($permissions->isEmpty())
                    <p>No permissions found.</p>
                @else
                    <div class="grid grid-cols-4 gap-4 my-2 z-10">
                        @foreach ($permissions as $permission)
                            <div
                                class="p-2 px-5 bg-blue-400 text-white text-base font-medium w-[calc(100%-1rem)] rounded-lg shadow-md transition duration-150 ease-in-out cursor-pointer transform hover:bg-blue-500 hover:shadow-lg hover:scale-105">
                                {{ $permission->name }}
                            </div>
                        @endforeach
                    </div>
                @endif

                <hr class="my-8 border-t-2 border-slate-400">

                <!-- Role-Has-Permissions Table -->
                <h3 class="font-semibold text-lg text-gray-700">Role-Has-Permissions</h3>

                @if ($roleHasPermissions->isEmpty())
                    <p>No role-permissions found.</p>
                @else
                    <table class="min-w-full table-auto mb-6">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">NO</th>
                                <th class="py-3 px-6 text-left">Role</th>
                                <th class="py-3 px-6 text-left">Permission</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @foreach ($roleHasPermissions as $rp)
                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                    <td class="py-3 px-6 text-left whitespace-nowrap">{{ $loop->iteration }}</td>
                                    <td class="py-3 px-6 text-left">{{ $rp->role->name }}</td>
                                    <td class="py-3 px-6 text-left">{{ $rp->permission->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <hr class="my-8 border-t-2 border-slate-400">

                <!-- Model-Has-Roles Table -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-lg text-gray-700">Model-Has-Roles</h3>
                    <button
                        class="role-assign-button inline-flex items-center bg-blue-500 text-white font-semibold px-4 py-2 text-sm rounded-lg hover:bg-blue-600 transition-colors duration-200">
                        <i class="bx bx-link mr-2 text-xl"></i>
                        Assign Roles
                    </button>
                </div>
                @if ($modelHasRoles->isEmpty())
                    <p>No model-roles found.</p>
                @else
                    <table class="min-w-full table-auto mb-6">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">NO</th>
                                <th class="py-3 px-6 text-left">Model</th>
                                <th class="py-3 px-6 text-left">Role</th>
                                <th class="py-3 px-6 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @foreach ($modelHasRoles as $mr)
                                <tr class="border-b border-gray-200 hover:bg-gray-100" data-id="{{ $mr->id }}">
                                    <td class="py-3 px-6 text-left whitespace-nowrap">{{ $loop->iteration }}</td>
                                    <td class="py-3 px-6 text-left">{{ $mr->model->name }}</td>
                                    <td class="py-3 px-6 text-left">{{ $mr->role->name }}</td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex item-center justify-center">
                                            <a data-id="unassignRoleModal{{ $mr->role_id . $mr->model_id }}"
                                                class="unassign-role-button w-4 mr-2 scale-125 transform hover:text-red-500 hover:scale-150 transition duration-75">
                                                <i class="bx bx-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                {{-- {{ $mr->role_id }} --}}
                                <!-- Unassign Role Modal -->
                                <div id="unassignRoleModal{{ $mr->role_id . $mr->model_id }}" data-modal=""
                                    class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 z-50 hidden">
                                    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
                                        <div class="flex items-center justify-between">
                                            <h2 class="text-xl font-bold text-red-600 underline">Confirm Deletion</h2>
                                        </div>
                                        <p class="text-gray-700 mt-4">Are you sure you want to delete this item? This
                                            action cannot be undone.</p>
                                        <form method="POST" action="{{ route('unassignRole') }}" class="flex">
                                            @csrf
                                            <input type="hidden" value="{{ $mr->model_id }}" name="model_id">
                                            <input type="hidden" value="{{ $mr->role_id }}" name="role_id">

                                            <input type="hidden" value="modelRole" name="assign">
                                            <div class="flex justify-end w-full mt-3">
                                                <button type="button"
                                                    data-id="unassignRoleModal{{ $mr->role_id . $mr->model_id }}"
                                                    id="cancelUnassignRoleModal"
                                                    class="unassign-role-button bg-gray-300 text-gray-700 py-2 px-4 rounded mr-2 hover:bg-gray-400">
                                                    Cancel
                                                </button>
                                                <button type="submit"
                                                    class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600"
                                                    data-id="{{ $mr->id }}" data-unassign="modelRole">
                                                    Delete
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <hr class="my-8 border-t-2 border-slate-400">

                <!-- Model-Has-Permissions Table -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-lg text-gray-700">Model-Has-Permissions</h3>
                    <button
                        class="permission-assign-button inline-flex items-center bg-blue-500 text-white font-semibold px-4 py-2 text-sm rounded-lg hover:bg-blue-600 transition-colors duration-200">
                        <i class="bx bx-link mr-2 text-xl"></i>
                        Assign Permissions
                    </button>
                </div>
                @if ($modelHasPermissions->isEmpty())
                    <p>No model-permissions found.</p>
                @else
                    <table class="min-w-full table-auto mb-6">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">NO</th>
                                <th class="py-3 px-6 text-left">Model</th>
                                <th class="py-3 px-6 text-left">Permission</th>
                                <th class="py-3 px-6 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @foreach ($modelHasPermissions as $mp)
                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                    <td class="py-3 px-6 text-left whitespace-nowrap">{{ $loop->iteration }}</td>
                                    <td class="py-3 px-6 text-left">{{ $mp->model->name }}</td>
                                    <td class="py-3 px-6 text-left">{{ $mp->permission->name }}</td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex item-center justify-center">
                                            <a data-id="unassignPermissionModal{{ $mp->permission_id . $mp->model_id }}"
                                                class="unassign-permission-button w-4 mr-2 scale-125 transform hover:text-red-500 hover:scale-150 transition duration-75">
                                                <i class="bx bx-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <div id="unassignPermissionModal{{ $mp->permission->id . $mp->model_id }}"
                                    class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 z-50 hidden">
                                    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
                                        <div class="flex items-center justify-between">
                                            <h2 class="text-xl font-bold text-red-600 underline">Confirm Deletion</h2>
                                        </div>
                                        <p class="text-gray-700 mt-4">Are you sure you want to delete this item? This
                                            action cannot be undone.</p>
                                        <form method="POST" action="{{ route('unassignPermission') }}" class="flex">
                                            @csrf
                                            <input type="hidden" value="{{ $mp->model_id }}" name="model_id">
                                            <input type="hidden" value="{{ $mp->permission_id }}"
                                                name="permission_id">

                                            <input type="hidden" value="modelPermission" name="assign">
                                            <div class="flex justify-end w-full mt-3">
                                                <button type="button"
                                                    data-id="unassignPermissionModal{{ $mp->permission_id . $mp->model_id }}"
                                                    id="cancelUnassignPermissionModal"
                                                    class="unassign-role-button bg-gray-300 text-gray-700 py-2 px-4 rounded mr-2 hover:bg-gray-400">
                                                    Cancel
                                                </button>
                                                <button type="submit"
                                                    class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600"
                                                    data-id="{{ $mp->id }}" data-unassign="modelPermission">
                                                    Delete
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Assign Role -->
    <div id="assignRoleModal" class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
            <div class="flex items-center justify-between">
                <i id="closeAssignRoleModal"
                    class="bx bx-arrow-back scale-125 font-extrabold mb-4 cursor-pointer hover:scale-150"></i>
                <h2 class="text-xl font-bold mb-4 pr-4 mx-auto underline">Model Has Role</h2>
            </div>
            <form id="assignRoleForm" action="{{ route('assignRole') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                {{-- <label for="name" class="text-gray-600 font-light text-sm">Name</label> --}}
                <select name="user" id="user"
                    class="w-full border rounded p-2 my-3 focus:outline-none focus:border-blue-500" required>
                    <option selected disabled value="">Choose Model/User</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                <div class="flex justify-center">
                    <i class="bx bx-link mr-2 text-xl"></i>
                    <p>Assign with Role</p>
                </div>
                <select name="role" id="role"
                    class="w-full border rounded p-2 my-3 focus:outline-none focus:border-blue-500" required>
                    <option selected disabled value="">Choose role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }} {{ $role->id }}</option>
                    @endforeach
                </select>
                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="bg-blue-500 text-white font-bold py-2 px-6 rounded hover:bg-blue-600 transition duration-200">Assign</button>
                </div>
            </form>
        </div>
    </div>
    {{-- Script Modal Add --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mendapatkan elemen-elemen yang dibutuhkan
            const addButton = document.querySelectorAll('.role-assign-button');
            const addModal = document.getElementById('assignRoleModal');
            const closeModal = document.getElementById('closeAssignRoleModal');
            const addUserForm = document.getElementById('assignRoleForm');

            // Menampilkan modal saat tombol "add User" diklik
            addButton.forEach(button => {
                button.addEventListener('click', function() {
                    // Tampilkan modal
                    addModal.classList.remove('hidden');
                    addModal.classList.add('flex'); // Menggunakan 'flex' untuk menampilkan modal
                });
            });

            // Menutup modal saat tombol "Close" diklik
            closeModal.addEventListener('click', function() {
                addModal.classList.add('hidden');
                addModal.classList.remove('flex');
            });

            // Menutup modal saat area di luar modal diklik
            addModal.addEventListener('click', function(e) {
                if (e.target === addModal) {
                    addModal.classList.add('hidden');
                    addModal.classList.remove('flex');
                }
            });
        });
    </script>

    <!-- Modal Assign Permission -->
    <div id="assignPermissionModal" class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
            <div class="flex items-center justify-between">
                <i id="closeAssignPermissionModal"
                    class="bx bx-arrow-back scale-125 font-extrabold mb-4 cursor-pointer hover:scale-150"></i>
                <h2 class="text-xl font-bold mb-4 pr-4 mx-auto underline">Model Has Permission</h2>
            </div>
            <form id="assignPermissionForm" action="{{ route('assignPermission') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                {{-- <label for="name" class="text-gray-600 font-light text-sm">Name</label> --}}
                <select name="user" id="user"
                    class="w-full border rounded p-2 my-3 focus:outline-none focus:border-blue-500" required>
                    <option selected disabled value="">Choose Model/User</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                <div class="flex justify-center">
                    <i class="bx bx-link mr-2 text-xl"></i>
                    <p>Assign with Permission</p>
                </div>
                <select name="permission" id="permission"
                    class="w-full border rounded p-2 my-3 focus:outline-none focus:border-blue-500" required>
                    <option selected disabled value="">Choose Permission</option>
                    @foreach ($permissions as $permission)
                        <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                    @endforeach
                </select>
                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="bg-blue-500 text-white font-bold py-2 px-6 rounded hover:bg-blue-600 transition duration-200">Assign</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script Modal Add --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mendapatkan elemen-elemen yang dibutuhkan
            const addButton = document.querySelectorAll('.permission-assign-button');
            const addModal = document.getElementById('assignPermissionModal');
            const closeModal = document.getElementById('closeAssignPermissionModal');
            const addUserForm = document.getElementById('assignPermissionForm');

            // Menampilkan modal saat tombol "add User" diklik
            addButton.forEach(button => {
                button.addEventListener('click', function() {
                    // Tampilkan modal
                    addModal.classList.remove('hidden');
                    addModal.classList.add('flex'); // Menggunakan 'flex' untuk menampilkan modal
                });
            });

            // Menutup modal saat tombol "Close" diklik
            closeModal.addEventListener('click', function() {
                addModal.classList.add('hidden');
                addModal.classList.remove('flex');
            });

            // Menutup modal saat area di luar modal diklik
            addModal.addEventListener('click', function(e) {
                if (e.target === addModal) {
                    addModal.classList.add('hidden');
                    addModal.classList.remove('flex');
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.unassign-role-button');
            const cancelButtons = document.querySelectorAll('.cancelUnassignRoleModal');
            const confirmButtons = document.querySelectorAll('.confirmUnassignRoleModal');

            // Event listener untuk tombol hapus
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const modalId = this.getAttribute('data-id'); // Dapatkan ID modal
                    const modal = document.getElementById(modalId); // Temukan modal yang sesuai
                    if (modal.classList.contains('hidden')) {
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    } else {
                        modal.classList.remove('flex');
                        modal.classList.add('hidden');
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.unassign-permission-button');
            const cancelButtons = document.querySelectorAll('.cancelUnassignPermissionModal');
            const confirmButtons = document.querySelectorAll('.confirmUnassignPermissionModal');

            // Event listener untuk tombol hapus
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const modalId = this.getAttribute('data-id'); // Dapatkan ID modal
                    const modal = document.getElementById(modalId); // Temukan modal yang sesuai
                    if (modal.classList.contains('hidden')) {
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    } else {
                        modal.classList.remove('flex');
                        modal.classList.add('hidden');
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.unassign-permission-button');
            const unassignPermissionModal = document.getElementById('unassignPermissionModal');
            const cancelDelete = document.getElementById('cancelUnassignPermissionModal');
            const unassignPermissionForm = document.getElementById('unassignPermissionForm');
            let modelRoleId; // Declare modelRoleId

            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const row = this.closest('tr'); // Get the closest tr
                    modelRoleId = row.getAttribute('data-id'); // Get the model_has_roles id
                    // Set the form action dynamically
                    unassignPermissionForm.action =
                        `/user-management/roles-permissions/roles/${modelRoleId}`;
                    unassignPermissionModal.classList.remove('hidden'); // Show the modal
                    unassignPermissionModal.classList.add('show'); // Show the modal
                });
            });

            cancelDelete.addEventListener('click', function() {
                unassignPermissionModal.classList.remove('show'); // Show the modal
                unassignPermissionModal.classList.add('hidden'); // Hide the modal
            });
        });
    </script>




</x-app-layout>
