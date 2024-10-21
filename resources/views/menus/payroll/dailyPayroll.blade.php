<!-- resources/views/user-management/all-users.blade.php -->
<x-app-layout>
    <style>
        #userModal {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            /* Awalnya disembunyikan */
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: 0.5s ease;
            z-index: 1000;
            transform: translateY(20);
        }

        #userModal.show {
            transition: 0.5s ease;
            display: flex;
            /* Menampilkan modal */
            opacity: 1;
            transform: translateY(0);
            /* Menampilkan efek muncul */
        }

        .full-screen-image {
            width: auto;
            height: auto;
            max-width: 80%;
            max-height: 80%;
            object-fit: contain;
            border-radius: 15px;
            /* Menjaga proporsi gambar */
        }

        #deleteModal.show {
            display: flex;
            z-index: 101
        }

        #addModal {
            z-index: 101;
        }

        #addModal.show {
            display: flex
        }

        #updateModal {
            z-index: 101;
        }

        #updateModal .show {
            display: flex
        }

        #imageOverlay {
            display: none;
            /* Sembunyikan overlay secara default */
        }

        input[type="number"] {
            -moz-appearance: textfield;
            /* Untuk Firefox */
            -webkit-appearance: none;
            /* Untuk Chrome dan Safari */
            appearance: none;
            /* Untuk browser lain */
        }

        /* Sembunyikan spinner */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
    <x-slot name="expense">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('expenses') }}
        </h2>
    </x-slot>

    <div class="">
        <nav class="flex pb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Expenses</span>
                    </div>
                </li>
            </ol>
        </nav>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">User Management</h3>
                    <button
                        class="add-button flex items-center bg-blue-500 text-white font-semibold py-2 px-4 text-sm rounded hover:bg-blue-600 transition duration-200">
                        <i class="bx bx-plus mr-2"></i> <!-- Menggunakan Boxicons untuk ikon -->
                        Add User
                    </button>
                </div>

                @if ($dailyPayrolls->isEmpty())
                    <p>No design request found.</p>
                @else
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-11 text-left">NO</th>
                                <th class="py-3 px-6 text-left">Employee</th>
                                <th class="py-3 px-6 text-left">work_date</th>
                                <th class="py-3 px-6 text-left">Total Pieces</th>
                                <th class="py-3 px-6 text-left">Total Pay</th>
                                <th class="py-3 px-6 text-center text-sm font-bold opacity-60">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dailyPayrolls as $dailyPayroll)
                                <tr class="border-b border-gray-200 hover:bg-gray-100" data-expense>
                                    <td class="py-3 px-6 text-left">
                                        <i class="bx bx-chevron-right cursor-pointer"></i>
                                        <!-- Tombol chevron di sini -->
                                        <i class="bx bx-archive mr-3 opacity-50"></i>
                                        {{ $loop->iteration }}.
                                    </td>
                                    <td class="py-3 px-6 text-left">{{ $dailyPayroll->employee->name }}</td>
                                    <td class="py-3 px-6 text-left">{{ $dailyPayroll->work_date }}</td>
                                    <td class="py-3 px-6 text-left">{{ $dailyPayroll->total_pieces }}</td>
                                    <td class="py-3 px-6 text-left">{{ $dailyPayroll->daily_total_pay }}</td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex item-center justify-center">
                                            <a href="#"
                                                class="view-button w-4 mr-2 scale-125 opacity-75 transform hover:text-green-500 hover:scale-150 transition duration-75">
                                                <i class="bx bx-plus-circle"></i>
                                            </a>
                                            <a href="#"
                                                class="view-button w-4 mr-2 scale-125 opacity-75 transform hover:text-red-500 hover:scale-150 transition duration-75">
                                                <i class="bx bx-x-circle"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="display: none;">
                                    <td colspan="5">
                                        <table class="nested-table w-full mt-2 ml-12 ">
                                            <thead>
                                                <tr class="nested-expense">
                                                    <th class="py-3 px-6 text-left text-sm font-bold opacity-60">No</th>
                                                    <th class="py-3 px-6 text-left text-sm font-bold opacity-60">Design
                                                        Request</th>
                                                    <th class="py-3 px-6 text-left text-sm font-bold opacity-60">Job
                                                        Type</th>
                                                    {{-- <th class="py-3 px-6 text-left text-sm font-bold opacity-60">Pieces
                                                        Worked</th>
                                                    <th class="py-3 px-6 text-left text-sm font-bold opacity-60">Pay per
                                                        Piece</th> --}}
                                                    <th class="py-3 px-6 text-left text-sm font-bold opacity-60">Subtotal
                                                        Pay</th>
                                                    <th class="py-3 px-6 text-center text-sm font-bold opacity-60">
                                                        Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($dailyPayroll->dailyPayrollDetail as $detail)
                                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                                        <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                            <ol>
                                                                <li>{{ chr(97 + $loop->index) }}.</li>
                                                            </ol>
                                                        </td>
                                                        <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                            {{ $detail->designRequest->name }}
                                                        </td>
                                                        <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                            {{ $detail->job_type }}</td>
                                                        {{-- <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                            {{ $detail->pieces_worked }}</td>
                                                        <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                            {{ $detail->pay_per_piece }}</td> --}}
                                                        <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                            {{ $detail->subtotal_pay }}</td>
                                                        <td class="py-3 px-6 text-center">
                                                            <div class="flex item-center justify-center">
                                                                <a href="#"
                                                                    class="view-button w-4 mr-2 scale-125 transform hover:text-green-500 hover:scale-150 transition duration-75">
                                                                    <i class="bx bx-show"></i>
                                                                </a>
                                                                @if (auth()->user()->hasRole('admin'))
                                                                    <a href="#"
                                                                        class="update-button w-4 mr-2 scale-125 transform hover:text-indigo-500 hover:scale-150 transition duration-75">
                                                                        {{-- data-user-id="{{ $user->id }}"
                                                                    data-user-userName="{{ $user->username }}"
                                                                    data-user-name="{{ $user->name }}"
                                                                    data-user-email="{{ $user->email }}"
                                                                    data-user-telepon="{{ $user->contact_info }}"
                                                                    data-user-address="{{ $user->address }}"> --}} <i class="bx bx-edit"></i>
                                                                    </a>

                                                                    <a href="#"
                                                                        class="delete-button w-4 mr-2 scale-125 transform hover:text-red-500 hover:scale-150 transition duration-75">
                                                                        <i class="bx bx-trash"></i>
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </td>

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Menangkap semua baris yang berisi data expense
            const rows = document.querySelectorAll('tr[data-expense]');

            // Menambahkan event listener untuk setiap baris
            rows.forEach(row => {
                const arrowIcon = row.querySelector('.bx');

                row.addEventListener('click', function() {
                    const nestedTable = this
                        .nextElementSibling; // Mengambil elemen berikutnya (tabel nested)

                    // Toggle visibilitas tabel nested
                    if (nestedTable.style.display === "table-row") {
                        nestedTable.style.display = "none";
                        arrowIcon.classList.remove('bx-chevron-down');
                        arrowIcon.classList.add('bx-chevron-right');
                    } else {
                        nestedTable.style.display = "table-row";
                        arrowIcon.classList.remove('bx-chevron-right');
                        arrowIcon.classList.add('bx-chevron-down');
                    }
                });
            });
        });
    </script>
    <script>
        console.log(localStorage.getItem('data-navLink-id'));
    </script>

    {{-- <!-- Modal View -->
    <div id="userModal" class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full">
            <div class="flex items-center justify-between">
                <i id="closeModal"
                    class="bx bx-arrow-back scale-125 font-extrabold mb-4 ml-1 cursor-pointer hover:scale-150"></i>
                <h2 class="text-xl font-bold mb-4 pr-4 mx-auto underline">User Details</h2>
            </div>
            <div class="flex justify-center">
                <img id="userProfileImage" src="" alt="User Profile"
                    class="w-32 h-32 rounded-full object-cover cursor-pointer">
            </div>
            <div id="imageOverlay" class="fixed inset-0 bg-black bg-opacity-75 items-center justify-center hidden">
                <img id="fullScreenImage" class="full-screen-image" src="" alt="Full Screen User Profile">
            </div>
            <div class="flex flex-col items-center mb-4 mt-2">
                <h3 id="userName" class="text-lg font-semibold"></h3>
                <p class="text-gray-600">Admin</p>
            </div>
            <hr>
            <div class="my-4">

                <p class="text-gray-600 font-light text-sm"><i class="bx bx-envelope mr-2 scale-110 pt-3"></i>Email
                    Address:</p>
                <a href="mailto:huwaiza137@gmail.com" class="flex hover:text-blue-500" target="_blank">
                    <p id="userEmail" class="text-base font-medium mb-2 transition duration-75 cursor-pointer"></p><i
                        class="bx bx-link-alt ml-1 pt-1"></i>
                </a>
                <p class="text-gray-600 font-light text-sm"><i class="bx bx-phone mr-2 scale-110 pt-3"></i>Contact
                    Address:</p>
                <a href="https://wa.me/08815184624" class="flex hover:text-blue-500" target="_blank">
                    <p id="userContacts" class="text-base font-medium mb-2 transition duration-75 cursor-pointer"></p>
                    <i class="bx bx-link-alt ml-1 pt-1"></i>
                </a>
                <p class="text-gray-600 font-light text-sm"><i class="bx bx-map mr-2 scale-110 pt-3"></i>Address:</p>
                <a href="https://maps.app.goo.gl/5kFdZUb2p61mVekN7" class="flex hover:text-blue-500" target="_blank">
                    <p id="userAddress" class="text-base font-medium mb-2 transition duration-75 cursor-pointer"></p>
                    <i class="bx bx-link-alt ml-1 pt-1"></i>
                </a>
            </div>
        </div>
    </div> --}}

    <!-- Modal Add Expenses -->
    {{-- <div id="addModal" class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl w-full">
            <div class="flex items-center justify-between">
                <i id="closeAddModal"
                    class="bx bx-arrow-back scale-125 font-extrabold mb-4 cursor-pointer hover:scale-150"></i>
                <h2 class="text-xl font-bold mb-4 pr-4 mx-auto underline">Add New Expense</h2>
            </div>
            <form id="addExpenseForm" action="{{ route('expenses.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                <!-- Item Name -->
                <div class="mb-4">
                    <label for="item_name" class="text-gray-600 font-light text-sm">Item Name</label>
                    <input type="text" name="item_name" id="item_name" placeholder="Enter Item Name"
                        class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required>
                </div>

                <!-- Amount -->
                <div class="mb-4">
                    <label for="amount" class="text-gray-600 font-light text-sm">Amount</label>
                    <input type="number" name="amount" id="amount" placeholder="Enter Amount"
                        class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required>
                </div>

                <!-- Date -->
                <div class="mb-4">
                    <label for="date" class="text-gray-600 font-light text-sm">Date</label>
                    <input type="date" name="date" id="date" placeholder="Enter Date"
                        class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="text-gray-600 font-light text-sm">Description</label>
                    <textarea name="description" id="description" placeholder="Enter Description"
                        class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="bg-blue-500 text-white font-bold py-2 px-6 rounded hover:bg-blue-600 transition duration-200">
                        Add Expense
                    </button>
                </div>
            </form>
        </div>
    </div> --}}

    <!-- Modal Edit Expenses -->
    {{-- <div id="updateModal" class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl w-full">
            <div class="flex items-center justify-between">
                <i id="closeUpdateModal"
                    class="bx bx-arrow-back scale-125 font-extrabold mb-4 cursor-pointer hover:scale-150"></i>
                <h2 class="text-xl font-bold mb-4 pr-4 mx-auto underline">Update Expense</h2>
            </div>
            <form id="updateExpenseForm" action="{{ route('expenses.update', $expense->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                <input type="hidden" name="expense_id" id="expense_id" value="{{ $expense->id }}">

                <!-- Expense Name -->
                <div class="mb-4">
                    <label for="expense_name" class="text-gray-600 font-light text-sm">Expense Name</label>
                    <input type="text" name="expense_name" id="expense_name" placeholder="Enter Expense Name"
                        value="{{ $expense->expense_name }}"
                        class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required>
                </div>

                <!-- Expense Amount -->
                <div class="mb-4">
                    <label for="amount" class="text-gray-600 font-light text-sm">Amount</label>
                    <input type="number" name="amount" id="amount" placeholder="Enter Amount"
                        value="{{ $expense->amount }}"
                        class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required>
                </div>

                <!-- Expense Date -->
                <div class="mb-4">
                    <label for="expense_date" class="text-gray-600 font-light text-sm">Date</label>
                    <input type="date" name="expense_date" id="expense_date"
                        value="{{ $expense->expense_date }}"
                        class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required>
                </div>

                <!-- Expense Description -->
                <div class="mb-4">
                    <label for="description" class="text-gray-600 font-light text-sm">Description</label>
                    <textarea name="description" id="description" rows="3" placeholder="Enter Description"
                        class="w-full border rounded p-2 focus:outline-none focus:border-blue-500">{{ $expense->description }}</textarea>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="bg-blue-500 text-white font-bold py-2 px-6 rounded hover:bg-blue-600 transition duration-200">
                        Update Expense
                    </button>
                </div>
            </form>
        </div>
    </div> --}}

    <!-- Modal Delete -->
    {{-- <div id="deleteModal" class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-red-600 underline">Confirm Deletion</h2>
            </div>
            <p class="text-gray-700 mt-4">Are you sure you want to delete this item? This action cannot be undone.</p>
            <div class="mt-6 flex justify-end">
                <button id="cancelDelete" class="bg-gray-300 text-gray-700 py-2 px-4 rounded mr-2 hover:bg-gray-400">
                    Cancel
                </button>
                <button id="confirmDelete" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600">
                    Delete
                </button>
            </div>
        </div>
    </div> --}}

    {{-- Script Modal Delete --}}
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-button');
            const deleteModal = document.getElementById('deleteModal');
            const cancelDelete = document.getElementById('cancelDelete');
            const confirmDelete = document.getElementById('confirmDelete');
            let userId; // Declare userId outside

            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const row = this.closest('tr'); // Get the closest tr
                    userId = row.getAttribute('data-id'); // Set userId from data-id
                    deleteModal.classList.add('show'); // Show the modal
                });
            });

            cancelDelete.addEventListener('click', function() {
                deleteModal.classList.remove('show'); // Hide the modal
            });

            confirmDelete.addEventListener('click', function() {
                if (userId) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/users/${userId}`; // Use the userId

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';

                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    form.submit(); // Submit the form
                }
                deleteModal.classList.remove('show'); // Hide the modal
            });
        });
    </script> --}}

    {{-- Script Modal View --}}
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cari semua elemen dengan kelas 'view-button'
            document.querySelectorAll('.view-button').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('keklik bang')
                    const row = this.closest('tr');
                    if (row) {
                        const userProfileImage = row.getAttribute('data-profile-image');
                        const userName = row.getAttribute('data-name');
                        const userEmail = row.getAttribute('data-email');
                        const userContacts = row.getAttribute('data-contacts');
                        const userAddress = row.getAttribute('data-address');

                        // Update gambar profil
                        if (userProfileImage) {
                            const imageUrl = "{{ asset('') }}" + userProfileImage;
                            document.getElementById('userProfileImage').src = imageUrl;
                        }

                        // Update data user di modal
                        document.getElementById('userName').innerText = userName || 'N/A';
                        document.getElementById('userEmail').innerText = userEmail || 'N/A';
                        document.getElementById('userContacts').innerText = userContacts || 'N/A';
                        document.getElementById('userAddress').innerText = userAddress || 'N/A';
                    } else {
                        console.error("Baris tidak ditemukan!");
                    }

                    // Tampilkan modal
                    const userModal = document.getElementById('userModal');
                    console.log(
                        userModal); // Tambahkan log untuk memeriksa apakah userModal ditemukan
                    if (userModal) {
                        userModal.classList.add('show'); // Tambahkan class show
                    } else {
                        console.error("Element with ID 'userModal' not found!");
                    }
                });
            });

            // Menutup modal
            const closeModalButton = document.getElementById('closeModal');
            if (closeModalButton) {
                closeModalButton.addEventListener('click', function() {
                    const userModal = document.getElementById('userModal');
                    console.log(
                        userModal
                    ); // Tambahkan log untuk memeriksa apakah userModal ditemukan saat mencoba menutup modal
                    if (userModal) {
                        userModal.classList.remove('show'); // Hapus class show
                    } else {
                        console.error("Element with ID 'userModal' not found!");
                    }
                });
            } else {
                console.error("Element with ID 'closeModal' not found!");
            }
        });
    </script> --}}

    {{-- Script Modal Edit --}}
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const updateButtons = document.querySelectorAll('.update-expense-button');
            const updateModal = document.getElementById('updateModal');
            const closeModal = document.getElementById('closeUpdateModal');
            const updateExpenseForm = document.getElementById('updateExpenseForm');

            // Menampilkan modal saat tombol "update Expense" diklik
            updateButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const expenseId = this.getAttribute('data-expense-id');
                    const expenseName = this.getAttribute('data-expense-name');
                    const expenseAmount = this.getAttribute('data-expense-amount');
                    const expenseDate = this.getAttribute('data-expense-date');
                    const expenseDescription = this.getAttribute('data-expense-description');

                    // Mengisi form di modal dengan data expense
                    updateExpenseForm.action = `/expenses/${expenseId}`;
                    updateExpenseForm.querySelector('input[name="expense_name"]').value =
                        expenseName;
                    updateExpenseForm.querySelector('input[name="amount"]').value = expenseAmount;
                    updateExpenseForm.querySelector('input[name="expense_date"]').value =
                        expenseDate;
                    updateExpenseForm.querySelector('textarea[name="description"]').value =
                        expenseDescription;

                    // Tampilkan modal
                    updateModal.classList.remove('hidden');
                    updateModal.classList.add('flex');
                });
            });

            // Menutup modal saat tombol "Close" diklik
            closeModal.addEventListener('click', function() {
                updateModal.classList.add('hidden');
                updateModal.classList.remove('flex');
            });

            // Menutup modal saat area di luar modal diklik
            updateModal.addEventListener('click', function(e) {
                if (e.target === updateModal) {
                    updateModal.classList.add('hidden');
                    updateModal.classList.remove('flex');
                }
            });
        });
    </script> --}}

    {{-- Script Modal Add --}}
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mendapatkan elemen-elemen yang dibutuhkan
            const addButton = document.querySelectorAll('.add-button');
            const addModal = document.getElementById('addModal');
            const closeModal = document.getElementById('closeAddModal');
            const addUserForm = document.getElementById('addUserForm');

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
    </script> --}}

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userProfileImage = document.getElementById('userProfileImage');
            const fullScreenImage = document.getElementById('fullScreenImage');
            const imageOverlay = document.getElementById('imageOverlay');

            userProfileImage.addEventListener('click', function() {
                fullScreenImage.src = this.src;
                imageOverlay.style.display = 'flex'; // Tampilkan overlay
            });

            imageOverlay.addEventListener('click', function() {
                imageOverlay.style.display = 'none'; // Sembunyikan overlay saat diklik
            });
        });
    </script> --}}



</x-app-layout>
