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
    <x-slot name="header">
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
        <div class="flex flex-col ml-8">
            <div class="flex">
                <button id="work" class="ml-1.5 mr-4 text-blue-500 font-semibold">
                    @if (Auth()->User()->hasRole('admin'))
                        Pending Payments
                    @else
                        My Payment
                    @endif
                </button>
                <button id="done" class="mr-4">
                    @if (Auth()->User()->hasRole('admin'))
                        Transaction History
                    @else
                        My Transaction
                    @endif
                </button>
            </div>
            <!-- Tambahkan hr setelah button pertama -->
            <hr id="underline" class="ml-1 w-0 border-blue-500 mb-3"
                style="border-width: 1.5px; transition: 0.5s ease;">
        </div>

        <div id="tableTransaction" class="max-w-7xl mx-auto sm:px-6 lg:px-8 transition-all duration-500 opacity-100">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex">
                        <h3 class="text-lg font-medium pt-1 mr-3">
                            @if (Auth()->User()->hasRole('admin'))
                                Pending Payments
                            @else
                                Pending Orders
                            @endif
                        </h3>
                        <!-- Filter and Sort Section -->
                        <div class="flex pb-4 items-center space-x-4">
                            <div class="w-px h-6 bg-gray-300"></div>
                            <div class="flex items-center">
                                <i class="bx bx-filter text-lg"></i>
                                <span class="ml-1">Filter</span>
                            </div>
                            <div class="flex items-center">
                                <i class="bx bx-sort text-lg"></i>
                                <span class="ml-1">Sort</span>
                            </div>
                            <div class="w-px h-6 bg-gray-300"></div>
                            <button
                                class="flex items-center text-black-500 py-2 rounded hover:text-blue-600 transition duration-200">
                                <i class="bx bx-export mr-1"></i>
                                <span>Export</span>
                            </button>
                            <button
                                class="flex items-center text-black-500 py-2 rounded hover:text-green-600 transition duration-200">
                                <i class="bx bx-printer mr-1"></i>
                                <span>Print</span>
                            </button>
                        </div>
                    </div>
                    <div class="flex">
                        <!-- Search Bar -->
                        <div class="relative inline-block h-12 w-12 -mr-6">
                            <input
                                class="-mr-3 search expandright absolute right-[49px] rounded bg-white border-none h-8 w-0 focus:w-[190px] transition-all duration-400 outline-none z-10 focus:px-4"
                                id="searchright" type="text" name="q" placeholder="Search">
                            <label class="z-20 button searchbutton absolute text-[22px] w-full cursor-pointer"
                                for="searchright">
                                <span class="-ml-3 inline-block">
                                    <i class="bx bx-search"></i>
                                </span>
                            </label>
                        </div>
                        <button
                            class="add-button -mt-1 max-h-10 flex items-center bg-blue-500 text-white font-semibold px-4 text-sm rounded hover:bg-blue-600 transition duration-200">
                            <i class="bx bx-plus mr-2"></i> <!-- Menggunakan Boxicons untuk ikon -->
                            Add User
                        </button>
                    </div>
                </div>
                @if ($designReqAll->isEmpty())
                    <p>No Qc Ops found.</p>
                @else
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">NO</th>
                                <th class="py-3 px-6 text-left">Customer</th>
                                <th class="py-3 px-6 text-left">Request Name</th>
                                <th class="py-3 px-6 text-left">Total Price</th>
                                <th class="py-3 px-6 text-left">Detail Date</th>
                                <th class="py-3 px-6 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @if (Auth::user()->hasRole('admin'))
                                @foreach ($designReqAll as $designReq)
                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                        {{-- data-profile-image="{{ $user->profile_picture }}" data-name="{{ $user->name }}"
                                data-email="{{ $user->email }}" data-contacts="{{ $user->contact_info }}"
                                data-address="{{ $user->address }}" data-id="{{ $user->id }}"> --}}

                                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $loop->iteration }}</td>
                                        <td class="py-3 px-6 text-left">
                                            <button class="text-blue-500"
                                                onclick="openModal('modal-{{ $designReq->id }}')">
                                                <img src="{{ asset($designReq->reference_image) }}"
                                                    alt="Reference Image" class="w-24 h-auto">
                                            </button>
                                        </td>
                                        <td class="py-3 px-6 text-left">{{ $designReq->name }}</td>
                                        <td class="py-3 px-6 text-left">
                                            <p class="font-semibold">Cost :</p>
                                            {{ $designReq->price_per_piece * $designReq->total_pieces }}
                                            <p class="font-semibold">From :</p>
                                            {{ $designReq->total_pieces.' Pcs' }}
                                        </td>
                                        <td class="py-3 px-6 text-left">
                                            <p class="font-semibold">Requested at :</p>
                                            {{ $designReq->created_at->format('d M, Y') }}
                                            <p class="font-semibold">Completed at :</p>
                                            {{ $designReq->completed_at ? $designReq->completed_at->format('d M, Y') : 'N/A' }}
                                        </td>
                                        <td class="py-3 px-6 text-center">
                                            <div class="flex item-center justify-center">
                                                {{-- <a href="{{ route('design.download', basename($design->design_files)) }}" --}}
                                                {{-- class="download-button w-4 mr-2 scale-125 transform hover:text-green-500 hover:scale-150 transition duration-75">
                                            <i class="bx bx-download"></i> --}}
                                                {{-- </a> --}}
                                                <a href="#"
                                                    class="view-button w-4 mr-2 scale-125 transform hover:text-green-500 hover:scale-150 transition duration-75">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <a href="#"
                                                    class="update-button w-4 mr-2 scale-125 transform hover:text-indigo-500 hover:scale-150 transition duration-75">
                                                    {{-- data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->name }}"
                                            data-user-email="{{ $user->email }}"
                                            data-user-telepon="{{ $user->contact_info }}"
                                            data-user-address="{{ $user->address }}"> --}}
                                                    <i class="bx bx-edit"></i>
                                                </a>

                                                <a href="#"
                                                    class="delete-button w-4 mr-2 scale-125 transform hover:text-red-500 hover:scale-150 transition duration-75">
                                                    <i class="bx bx-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @elseif (Auth::user()->hasRole('customer'))
                                @foreach ($transactionCustomer as $designReq)
                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                        {{-- data-profile-image="{{ $user->profile_picture }}" data-name="{{ $user->name }}"
                                        data-email="{{ $user->email }}" data-contacts="{{ $user->contact_info }}"
                                        data-address="{{ $user->address }}" data-id="{{ $user->id }}"> --}}

                                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $loop->iteration }}</td>
                                        <td class="py-3 px-6 text-left">{{ $designReq->customer->name }}</td>
                                        <td class="py-3 px-6 text-left">{{ $designReq->total_price }}</td>
                                        <td class="py-3 px-6 text-left">{{ $designReq->payment_type }}</td>
                                        <td class="py-3 px-6 text-left">{{ $designReq->status }}</td>
                                        <td class="py-3 px-6 text-left">
                                            {{ $designReq->created_at->format('d M, Y') }}
                                        </td>
                                        <td class="py-3 px-6 text-center">
                                            <div class="flex item-center justify-center">
                                                {{-- <a href="{{ route('design.download', basename($design->design_files)) }}" --}}
                                                {{-- class="download-button w-4 mr-2 scale-125 transform hover:text-green-500 hover:scale-150 transition duration-75">
                                        <i class="bx bx-download"></i> --}}
                                                {{-- </a> --}}
                                                <a href="#"
                                                    class="view-button w-4 mr-2 scale-125 transform hover:text-green-500 hover:scale-150 transition duration-75">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <a href="#"
                                                    class="update-button w-4 mr-2 scale-125 transform hover:text-indigo-500 hover:scale-150 transition duration-75">
                                                    {{-- data-user-id="{{ $user->id }}"
                                        data-user-name="{{ $user->name }}"
                                        data-user-email="{{ $user->email }}"
                                        data-user-telepon="{{ $user->contact_info }}"
                                        data-user-address="{{ $user->address }}"> --}}
                                                    <i class="bx bx-edit"></i>
                                                </a>

                                                <a href="#"
                                                    class="delete-button w-4 mr-2 scale-125 transform hover:text-red-500 hover:scale-150 transition duration-75">
                                                    <i class="bx bx-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                        </tbody>
                    </table>
                @endif
            </div>
        </div>
        <div id="tableHistory"
            class="max-w-7xl mx-auto sm:px-6 lg:px-8 transition-all transform ease-in-out duration-500 opacity-0 hidden">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex">
                        <h3 class="text-lg font-medium pt-1 mr-3">
                            @if (Auth()->User()->hasRole('admin'))
                                Transaction History
                            @else
                                My Transaction History
                            @endif
                        </h3>
                        <!-- Filter and Sort Section -->
                        <div class="flex pb-4 items-center space-x-4">
                            <div class="w-px h-6 bg-gray-300"></div>
                            <div class="flex items-center">
                                <i class="bx bx-filter text-lg"></i>
                                <span class="ml-1">Filter</span>
                            </div>
                            <div class="flex items-center">
                                <i class="bx bx-sort text-lg"></i>
                                <span class="ml-1">Sort</span>
                            </div>
                            <div class="w-px h-6 bg-gray-300"></div>
                            <button
                                class="flex items-center text-black-500 py-2 rounded hover:text-blue-600 transition duration-200">
                                <i class="bx bx-export mr-1"></i>
                                <span>Export</span>
                            </button>
                            <button
                                class="flex items-center text-black-500 py-2 rounded hover:text-green-600 transition duration-200">
                                <i class="bx bx-printer mr-1"></i>
                                <span>Print</span>
                            </button>
                        </div>
                    </div>
                    <div class="flex">
                        <!-- Search Bar -->
                        <div class="relative inline-block h-12 w-12 -mr-6">
                            <input
                                class="-mr-3 search expandright absolute right-[49px] rounded bg-white border-none h-8 w-0 focus:w-[190px] transition-all duration-400 outline-none z-10 focus:px-4"
                                id="searchright" type="text" name="q" placeholder="Search">
                            <label class="z-20 button searchbutton absolute text-[22px] w-full cursor-pointer"
                                for="searchright">
                                <span class="-ml-3 inline-block">
                                    <i class="bx bx-search"></i>
                                </span>
                            </label>
                        </div>
                        <button
                            class="add-button -mt-1 max-h-10 flex items-center bg-blue-500 text-white font-semibold px-4 text-sm rounded hover:bg-blue-600 transition duration-200">
                            <i class="bx bx-plus mr-2"></i> <!-- Menggunakan Boxicons untuk ikon -->
                            Add User
                        </button>
                    </div>
                </div>
                @if ($transactionAll->isEmpty())
                    <p>No Qc Ops found.</p>
                @else
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">NO</th>
                                <th class="py-3 px-6 text-left">Customer</th>
                                <th class="py-3 px-6 text-left">Total Price</th>
                                <th class="py-3 px-6 text-left">Payment Type</th>
                                <th class="py-3 px-6 text-left">Status</th>
                                <th class="py-3 px-6 text-left">Payment Date</th>
                                <th class="py-3 px-6 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @if (Auth::user()->hasRole('admin'))
                                @foreach ($transactionAll as $transaction)
                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                        {{-- data-profile-image="{{ $user->profile_picture }}" data-name="{{ $user->name }}"
                                data-email="{{ $user->email }}" data-contacts="{{ $user->contact_info }}"
                                data-address="{{ $user->address }}" data-id="{{ $user->id }}"> --}}

                                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $loop->iteration }}</td>
                                        <td class="py-3 px-6 text-left">{{ $transaction->customer->name }}</td>
                                        <td class="py-3 px-6 text-left">{{ $transaction->total_price }}</td>
                                        <td class="py-3 px-6 text-left">{{ $transaction->payment_type }}</td>
                                        <td class="py-3 px-6 text-left">{{ $transaction->status }}</td>
                                        <td class="py-3 px-6 text-left">
                                            {{ $transaction->created_at->format('d M, Y') }}
                                        </td>
                                        <td class="py-3 px-6 text-center">
                                            <div class="flex item-center justify-center">
                                                {{-- <a href="{{ route('design.download', basename($design->design_files)) }}" --}}
                                                {{-- class="download-button w-4 mr-2 scale-125 transform hover:text-green-500 hover:scale-150 transition duration-75">
                                            <i class="bx bx-download"></i> --}}
                                                {{-- </a> --}}
                                                <a href="#"
                                                    class="view-button w-4 mr-2 scale-125 transform hover:text-green-500 hover:scale-150 transition duration-75">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <a href="#"
                                                    class="update-button w-4 mr-2 scale-125 transform hover:text-indigo-500 hover:scale-150 transition duration-75">
                                                    {{-- data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->name }}"
                                            data-user-email="{{ $user->email }}"
                                            data-user-telepon="{{ $user->contact_info }}"
                                            data-user-address="{{ $user->address }}"> --}}
                                                    <i class="bx bx-edit"></i>
                                                </a>

                                                <a href="#"
                                                    class="delete-button w-4 mr-2 scale-125 transform hover:text-red-500 hover:scale-150 transition duration-75">
                                                    <i class="bx bx-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @elseif (Auth::user()->hasRole('customer'))
                                @foreach ($transactionCustomer as $transaction)
                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                        {{-- data-profile-image="{{ $user->profile_picture }}" data-name="{{ $user->name }}"
                                        data-email="{{ $user->email }}" data-contacts="{{ $user->contact_info }}"
                                        data-address="{{ $user->address }}" data-id="{{ $user->id }}"> --}}

                                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $loop->iteration }}</td>
                                        <td class="py-3 px-6 text-left">{{ $transaction->customer->name }}</td>
                                        <td class="py-3 px-6 text-left">{{ $transaction->total_price }}</td>
                                        <td class="py-3 px-6 text-left">{{ $transaction->payment_type }}</td>
                                        <td class="py-3 px-6 text-left">{{ $transaction->status }}</td>
                                        <td class="py-3 px-6 text-left">
                                            {{ $transaction->created_at->format('d M, Y') }}
                                        </td>
                                        <td class="py-3 px-6 text-center">
                                            <div class="flex item-center justify-center">
                                                {{-- <a href="{{ route('design.download', basename($design->design_files)) }}" --}}
                                                {{-- class="download-button w-4 mr-2 scale-125 transform hover:text-green-500 hover:scale-150 transition duration-75">
                                        <i class="bx bx-download"></i> --}}
                                                {{-- </a> --}}
                                                <a href="#"
                                                    class="view-button w-4 mr-2 scale-125 transform hover:text-green-500 hover:scale-150 transition duration-75">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <a href="#"
                                                    class="update-button w-4 mr-2 scale-125 transform hover:text-indigo-500 hover:scale-150 transition duration-75">
                                                    {{-- data-user-id="{{ $user->id }}"
                                        data-user-name="{{ $user->name }}"
                                        data-user-email="{{ $user->email }}"
                                        data-user-telepon="{{ $user->contact_info }}"
                                        data-user-address="{{ $user->address }}"> --}}
                                                    <i class="bx bx-edit"></i>
                                                </a>

                                                <a href="#"
                                                    class="delete-button w-4 mr-2 scale-125 transform hover:text-red-500 hover:scale-150 transition duration-75">
                                                    <i class="bx bx-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const underLine = document.getElementById('underline');
            const workButton = document.getElementById('work');
            const doneButton = document.getElementById('done');
            const workTable = document.getElementById('tableHistory');
            const doneTable = document.getElementById('tableTransaction');

            function setActiveButton(activeButton, inactiveButton) {
                activeButton.classList.add('text-blue-500', 'font-semibold');
                inactiveButton.classList.remove('text-blue-500', 'font-semibold');

                const activeButtonRect = activeButton.getBoundingClientRect();
                underLine.style.width = `${activeButtonRect.width}px`;
                underLine.style.marginLeft =
                    `${activeButtonRect.left - workButton.getBoundingClientRect().left + 3}px`;
            }

            function switchTables(showTable, hideTable) {
                // Fade out the current table
                hideTable.style.opacity = '0';
                hideTable.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    hideTable.classList.add('hidden');
                    showTable.classList.remove('hidden');

                    // Trigger reflow
                    void showTable.offsetWidth;

                    // Fade in the new table
                    showTable.style.opacity = '1';
                    showTable.style.transform = 'translateY(0)';
                }, 300); // Match this with your CSS transition duration
            }

            doneButton.addEventListener('click', function() {
                setActiveButton(doneButton, workButton);
                switchTables(workTable, doneTable);
            });

            workButton.addEventListener('click', function() {
                setActiveButton(workButton, doneButton);
                switchTables(doneTable, workTable);
            });

            // Initial setup
            setActiveButton(workButton, doneButton);
        });
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
    </div>

    <!-- Modal Add User -->
    <div id="addModal" class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl w-full">
            <div class="flex items-center justify-between">
                <i id="closeAddModal"
                    class="bx bx-arrow-back scale-125 font-extrabold mb-4 cursor-pointer hover:scale-150"></i>
                <h2 class="text-xl font-bold mb-4 pr-4 mx-auto underline">Add New User</h2>
            </div>
            <form id="addUserForm" action="{{ route('allUsers.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="mt-4">
                    <!-- Profile Picture -->
                    <label for="profile_pic" class="text-gray-600 font-light text-sm">Profile Picture</label>
                    <input type="file" name="profile_pic" id="profile_pic" accept="image/*"
                        class="w-full border rounded p-2 focus:outline-none focus:border-blue-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Name -->
                    <div>
                        <label for="name" class="text-gray-600 font-light text-sm">Name</label>
                        <input type="text" name="name" id="name" placeholder="Enter Name"
                            class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required>
                    </div>
                    <!-- Username -->
                    <div>
                        <label for="username" class="text-gray-600 font-light text-sm">Username</label>
                        <input type="text" name="username" id="username" placeholder="Enter Username"
                            class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <!-- Email -->
                    <div>
                        <label for="email" class="text-gray-600 font-light text-sm">Email</label>
                        <input type="email" name="email" id="email" placeholder="Enter Email Address"
                            class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required>
                    </div>
                    <!-- Phone -->
                    <div>
                        <label for="telepon" class="text-gray-600 font-light text-sm">Phone</label>
                        <input type="number" name="telepon" id="telepon"
                            class="w-full border rounded p-2 focus:outline-none focus:border-blue-500 appearance-none"
                            placeholder="Your phone number" required pattern="[0-9]*"
                            title="Please enter numbers only"
                            style="moz-appearance:textfield; -webkit-appearance:none;">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mt-4">
                    <!-- Address -->
                    <div>
                        <label for="address" class="text-gray-600 font-light text-sm">Address</label>
                        <input type="text" name="address" id="address" placeholder="Enter address"
                            class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <!-- Password -->
                    <div>
                        <label for="password" class="text-gray-600 font-light text-sm">Password</label>
                        <input type="password" name="password" id="password" placeholder="Enter Password"
                            class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required>
                    </div>
                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="text-gray-600 font-light text-sm">Confirm
                            Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            placeholder="Confirm Password"
                            class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="bg-blue-500 text-white font-bold py-2 px-6 rounded hover:bg-blue-600 transition duration-200">Add
                        User</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Update User -->
    <div id="updateModal" class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl w-full">
            <div class="flex items-center justify-between">
                <i id="closeUpdateModal"
                    class="bx bx-arrow-back scale-125 font-extrabold mb-4 cursor-pointer hover:scale-150"></i>
                <h2 class="text-xl font-bold mb-4 pr-4 mx-auto underline">Update User</h2>
            </div>
            <form id="updateUserForm" action="{{ route('allUsers.update', $user->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">

                <div class="mt-4">
                    <!-- Profile Picture -->
                    <label for="profile_pic" class="text-gray-600 font-light text-sm">Profile Picture</label>
                    <input type="file" name="profile_pic" id="profile_pic" accept="image/*"
                        class="w-full border rounded p-2 focus:outline-none focus:border-blue-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Name -->
                    <div>
                        <label for="name" class="text-gray-600 font-light text-sm">Name</label>
                        <input type="text" name="name" id="name" placeholder="Enter Name"
                            value="{{ $user->name }}"
                            class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required>
                    </div>
                    <!-- Username -->
                    <div>
                        <label for="username" class="text-gray-600 font-light text-sm">Username</label>
                        <input type="text" name="username" id="username" placeholder="Enter Username"
                            value="{{ $user->username }}"
                            class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <!-- Email -->
                    <div>
                        <label for="email" class="text-gray-600 font-light text-sm">Email</label>
                        <input type="email" name="email" id="email" placeholder="Enter Email Address"
                            value="{{ $user->email }}"
                            class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required>
                    </div>
                    <!-- Phone -->
                    <div>
                        <label for="telepon" class="text-gray-600 font-light text-sm">Phone</label>
                        <input type="number" name="telepon" id="telepon"
                            class="w-full border rounded p-2 focus:outline-none focus:border-blue-500 appearance-none"
                            placeholder="Your phone number" value="{{ $user->contact_info }}" required
                            pattern="[0-9]*" title="Please enter numbers only"
                            style="moz-appearance:textfield; -webkit-appearance:none;">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mt-4">
                    <!-- Address -->
                    <div>
                        <label for="address" class="text-gray-600 font-light text-sm">Address</label>
                        <input type="text" name="address" id="address" placeholder="Enter address"
                            value="{{ $user->address }}"
                            class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <!-- Password -->
                    <div>
                        <label for="password" class="text-gray-600 font-light text-sm">Password</label>
                        <input type="password" name="password" id="password" placeholder="Enter Password"
                            class="w-full border rounded p-2 focus:outline-none focus:border-blue-500">
                    </div>
                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="text-gray-600 font-light text-sm">Confirm
                            Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            placeholder="Confirm Password"
                            class="w-full border rounded p-2 focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="bg-blue-500 text-white font-bold py-2 px-6 rounded hover:bg-blue-600 transition duration-200">Update
                        User</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Delete -->
    <div id="deleteModal" class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 hidden">
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

    {{-- Script Modal Add --}}
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mendapatkan elemen-elemen yang dibutuhkan
            const updateButtons = document.querySelectorAll('.update-button');
            const updateModal = document.getElementById('updateModal');
            const closeModal = document.getElementById('closeUpdateModal');
            const updateUserForm = document.getElementById('updateUserForm');

            // Menampilkan modal saat tombol "update User" diklik
            updateButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id'); // Ambil ID dari atribut data
                    const userName = this.getAttribute(
                        'data-user-name'); // Ambil nama dari atribut data
                    const userEmail = this.getAttribute(
                        'data-user-email'); // Ambil email dari atribut data
                    const userTelepon = this.getAttribute(
                        'data-user-telepon'); // Ambil telepon dari atribut data
                    const userAddress = this.getAttribute(
                        'data-user-address'); // Ambil address dari atribut data

                    // Mengisi form di modal dengan data pengguna
                    updateUserForm.action = `/allUsers/${userId}`; // Set action form
                    updateUserForm.querySelector('input[name="name"]').value = userName;
                    updateUserForm.querySelector('input[name="email"]').value = userEmail;
                    updateUserForm.querySelector('input[name="telepon"]').value = userTelepon;
                    updateUserForm.querySelector('input[name="address"]').value = userAddress;

                    // Tampilkan modal
                    updateModal.classList.remove('hidden');
                    updateModal.classList.add('flex'); // Menggunakan 'flex' untuk menampilkan modal
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