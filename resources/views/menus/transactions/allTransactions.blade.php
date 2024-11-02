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
                        Queue Payments
                    @else
                        My Payments
                    @endif
                </button>
                <button id="done" class="mr-4">
                    @if (Auth()->User()->hasRole('admin'))
                        Transactions
                    @else
                        My Transactions
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
                                Queue Payments
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
                    </div>
                </div>
                @if ($designHeaderAll->isEmpty())
                    <p>No Payments found.</p>
                @else
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 pl-4 text-left">NO</th>
                                <th class="py-3 px-4 text-left">Customer</th>
                                <th class="py-3 px-6 text-left">Designs</th>
                                <th class="py-3 px-6 text-left">Total Price</th>
                                <th class="py-3 px-6 text-left">Detail Date</th>
                                <th class="py-3 pr-6 pl-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @if (Auth::user()->hasRole('admin'))
                                @foreach ($designHeaderAll as $designHeader)
                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                        {{-- data-profile-image="{{ $user->profile_picture }}" data-name="{{ $user->name }}"
                                data-email="{{ $user->email }}" data-contacts="{{ $user->contact_info }}"
                                data-address="{{ $user->address }}" data-id="{{ $user->id }}"> --}}

                                        <td class="py-3 pl-4 text-left whitespace-nowrap">{{ $loop->iteration }}</td>
                                        <td class="py-3 px-4 text-left">
                                            <div class="flex items-center">
                                                <img src="{{ $designHeader->customer->profile_picture ? asset($designHeader->customer->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($designHeader->customer->name) . '&background=ccc&color=fff' }}"
                                                    alt="Profile Picture" class="object-cover w-10 h-10 rounded-full">
                                                <div class="ml-3">
                                                    <span
                                                        class="block font-semibold text-gray-800">{{ $designHeader->customer->name }}</span>
                                                    <span
                                                        class="block text-xs text-gray-500">{{ $designHeader->customer->email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6 text-left">
                                            <ul>
                                                @foreach ($designHeader->designRequests as $request)
                                                    <li>
                                                        <b>{{ chr(97 + $loop->index) }}.</b>
                                                        {{ $request->name }}
                                                        @if ($request->status == 'shipped')
                                                            (<i class="bx bx-package"></i>)
                                                        @elseif ($request->status == 'cancelled')
                                                            (<i class="bx bx-block"></i>)
                                                        @elseif ($request->status == 'completed')
                                                            (<i class="bx bx-check-circle"></i>)
                                                        @else
                                                            (<i class="bx bx-time"></i>)
                                                        @endif
                                                        <br>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="py-3 px-6 text-left font-semibold">
                                            {{-- Menghitung total semua design request --}}
                                            @php
                                                $totalPrice = $designHeader->designRequests->sum(function (
                                                    $designRequest,
                                                ) {
                                                    return $designRequest->price_per_piece *
                                                        $designRequest->total_pieces;
                                                });
                                            @endphp
                                            {{ 'Rp' . number_format($totalPrice, 2, ',', '.') }}
                                        </td>
                                        <td class="py-3 px-6 text-left">
                                            <p class="font-semibold text-nowrap">Requested at:</p>
                                            {{ $designHeader->created_at->format('d M, Y') }}
                                            <p class="font-semibold text-nowrap">Completed at :</p>
                                            {{ $designHeader->updated_at ? $designHeader->updated_at->format('d M, Y') : 'N/A' }}
                                        </td>
                                        <td class="py-3 pr-6 pl-4 text-center">
                                            <div class="flex item-center justify-center">
                                                <a href="#" data-id="{{ $designHeader->id }}"
                                                    data-customerName="{{ $designHeader->customer->name }}"
                                                    data-customerId="{{ $designHeader->customer->id }}"
                                                    data-customerAddress="{{ $designHeader->customer->address }}"
                                                    data-transDate="{{ now()->format('d M, Y') }}"
                                                    class="pay-button w-4 mr-2 scale-125 transform hover:text-indigo-500 hover:scale-150 transition duration-75">
                                                    {{-- data-user-id="{{ $user->id }}"
                                                data-user-name="{{ $user->name }}"
                                                data-user-email="{{ $user->email }}"
                                                data-user-telepon="{{ $user->contact_info }}"
                                                data-user-address="{{ $user->address }}"> --}}
                                                    <i class="bx bx-wallet"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @elseif (Auth::user()->hasRole('customer'))
                                @foreach ($designHeaderCustomer as $designHeader)
                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                        {{-- data-profile-image="{{ $user->profile_picture }}" data-name="{{ $user->name }}"
                        data-email="{{ $user->email }}" data-contacts="{{ $user->contact_info }}"
                        data-address="{{ $user->address }}" data-id="{{ $user->id }}"> --}}

                                        <td class="py-3 pl-4 text-left whitespace-nowrap">{{ $loop->iteration }}</td>
                                        <td class="py-3 px-4 text-left">
                                            <div class="flex items-center">
                                                <img src="{{ $designHeader->customer->profile_picture ? asset($designHeader->customer->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($designHeader->customer->name) . '&background=ccc&color=fff' }}"
                                                    alt="Profile Picture" class="object-cover w-10 h-10 rounded-full">
                                                <div class="ml-3">
                                                    <span
                                                        class="block font-semibold text-gray-800">{{ $designHeader->customer->name }}</span>
                                                    <span
                                                        class="block text-xs text-gray-500">{{ $designHeader->customer->email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6 text-left">
                                            <ul>
                                                @foreach ($designHeader->designRequests as $request)
                                                    <li>
                                                        <b>{{ chr(97 + $loop->index) }}.</b>
                                                        {{ $request->name }}
                                                        @if ($request->status == 'shipped')
                                                            (<i class="bx bx-package"></i>)
                                                        @elseif ($request->status == 'cancelled')
                                                            (<i class="bx bx-block"></i>)
                                                        @elseif ($request->status == 'completed')
                                                            (<i class="bx bx-check-circle"></i>)
                                                        @else
                                                            (<i class="bx bx-time"></i>)
                                                        @endif
                                                        <br>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="py-3 px-6 text-left font-semibold">
                                            {{-- Menghitung total semua design request --}}
                                            @php
                                                $totalPrice = $designHeader->designRequests->sum(function (
                                                    $designRequest,
                                                ) {
                                                    return $designRequest->price_per_piece *
                                                        $designRequest->total_pieces;
                                                });
                                            @endphp
                                            {{ 'Rp' . number_format($totalPrice, 2, ',', '.') }}
                                        </td>
                                        <td class="py-3 px-6 text-left">
                                            <p class="font-semibold text-nowrap">Requested at:</p>
                                            {{ $designHeader->created_at->format('d M, Y') }}
                                            <p class="font-semibold text-nowrap">Completed at :</p>
                                            {{ $designHeader->updated_at ? $designHeader->updated_at->format('d M, Y') : 'N/A' }}
                                        </td>
                                        <td class="py-3 pr-6 pl-4 text-center">
                                            <div class="flex item-center justify-center">
                                                <a href="#" data-id="{{ $designHeader->id }}"
                                                    data-customerName="{{ $designHeader->customer->name }}"
                                                    data-customerId="{{ $designHeader->customer->id }}"
                                                    data-customerAddress="{{ $designHeader->customer->address }}"
                                                    data-transDate="{{ now()->format('d M, Y') }}"
                                                    class="pay-button w-4 mr-2 scale-125 transform hover:text-indigo-500 hover:scale-150 transition duration-75">
                                                    <i class="bx bx-wallet"></i>
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
                            class="historyButton -mt-1 max-h-10 flex items-center bg-blue-500 text-white font-semibold px-4 text-sm rounded hover:bg-blue-600 transition duration-200">
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
                                <th class="py-3 px-6 text-center flex items-center justify-center">Status</th>
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
                                        <td class="py-3 px-6 text-center flex items-center justify-center">
                                            <span
                                                class="
                                                    w-24 px-3 py-1 rounded-lg font-semibold text-white text-center
                                                    {{ $transaction->status == 'pending' ? 'bg-orange-500' : ($transaction->status == 'paid' ? 'bg-green-500' : 'bg-gray-300') }}
                                                ">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-6 text-left">
                                            {{ $transaction->created_at->format('d M, Y') }}
                                        </td>
                                        <td class="py-3 px-6 text-center">
                                            <div class="flex item-center justify-center">
                                                @if ($transaction->status != 'pending')
                                                    <a href="#" data-id="{{ $transaction->id }}"
                                                        data-transaction
                                                        data-date="{{ $transaction->created_at->format('d M, Y') }}"
                                                        data-customerName="{{ $transaction->customer->name }}"
                                                        data-customerNumber="{{ $transaction->customer->contact_info }}"
                                                        data-customerAddress="{{ $transaction->customer->address }}"
                                                        data-paymentMethod="{{ $transaction->payment_type }}"
                                                        data-total="{{ 'Rp' . number_format($transaction->total_price, 2, ',', '.') }}"
                                                        class="view-button w-4 mr-2 scale-125 transform hover:text-green-500 hover:scale-150 transition duration-75">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                @elseif ($transaction->payment_type == 'credit')
                                                    <a href="{{ route('debt') }}"
                                                        class="w-4 mr-2 scale-125 transform hover:text-green-500 hover:scale-150 transition duration-75">
                                                        <i class="bx bx-wallet"></i>
                                                    </a>
                                                @else
                                                    <a href="#" data-id="{{ $transaction->id }}"
                                                        data-transaction
                                                        data-date="{{ $transaction->created_at->format('d M, Y') }}"
                                                        data-customerName="{{ $transaction->customer->name }}"
                                                        data-customerNumber="{{ $transaction->customer->contact_info }}"
                                                        data-customerAddress="{{ $transaction->customer->address }}"
                                                        data-paymentMethod="{{ $transaction->payment_type }}"
                                                        data-proofPic="{{ $transaction->payment_proof_pic }}"
                                                        data-total="{{ 'Rp' . number_format($transaction->total_price, 2, ',', '.') }}"
                                                        class="confirm-button w-4 mr-2 scale-125 transform hover:text-green-500 hover:scale-150 transition duration-75">
                                                        <i class="bx bx-check-circle"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @elseif (Auth::user()->hasRole('customer'))
                                @foreach ($transactionCustomer as $transaction)
                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
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
                                                <a href="#"
                                                    class="view-button w-4 mr-2 scale-125 transform hover:text-green-500 hover:scale-150 transition duration-75">
                                                    <i class="bx bx-show"></i>
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

    {{-- PAYMENT MODAL --}}
    <form id="transactionForm" action="{{ route('addTransaction') }}" method="POST" enctype="multipart/form-data"
        class="fixed inset-0 items-center justify-start flex-row-reverse bg-black bg-opacity-50 z-50 hidden">
        @csrf
        <div class="bg-white rounded-lg shadow-lg flex flex-col justify-between p-6 mx-7 max-w-lg w-full"
            style="height: 96vh;">
            <div>
                <div class="flex items-center justify-between w-full">
                    <h2 class="text-xl font-bold">Requests Payment</h2>
                    <i id="closeTransactionForm"
                        class="bx bx-x scale-125 font-extrabold cursor-pointer hover:scale-150"></i>
                </div>
                <hr>
                <input type="hidden" name="referenceImage">
                <div class="m-2 flex flex-col justify-between h-full">
                    <div>
                        <div class="flex justify-between my-2">
                            <div class="flex flex-col">
                                <h6 class="font-semibold text-sm opacity-50">BILL TO :</h6>
                                <h3 class="font-semibold" id="customerName"></h3>
                                <input type="hidden" name="customerId" id="customerId">
                                <input type="hidden" name="totalPaymentPrice" id="totalPaymentPrice">
                                <div class="max-w-64 overflow-hidden">
                                    <p class="font-extralight opacity-75 text-sm line-clamp-2" id="customerAddress">
                                    </p>
                                </div>
                            </div>
                            <div class="text-end">
                                <h6 class="font-semibold text-sm opacity-50">BILL DATE :</h6>
                                <h3 class="font-medium" id="transDate"></h3>
                            </div>
                        </div>
                        <hr>
                        <div class="w-full flex justify-between py-3 px-5 bg-slate-200 rounded-lg">
                            <label class="text-gray-500 font-normal text-sm">DESIGN NAME</label>
                            <label class="text-gray-500 font-normal text-sm">AMOUNT</label>
                        </div>
                        <input type="hidden" name="designId">
                        <input type="hidden" name="designRequestId">
                        <div id="request-container" class="request-container overflow-y-scroll"
                            style="max-height: 96px; min-height: 96px">
                        </div>
                        <hr>
                        <div class="py-2">
                            <div>
                                <label class="text-gray-500 font-normal text-sm flex">FEEDBACK
                                    <p class="text-base font-thin">(optional)</p>
                                </label>
                                <textarea id="description-1" name="feedback" placeholder="Enter Feedback" rows="2"
                                    class="w-full border rounded p-2 focus:outline-none focus:border-blue-500"></textarea>
                            </div>
                            <div>
                                <label class="text-gray-500 font-normal text-sm">RATING</label>
                                <div class="flex items-center">
                                    <span class="flex space-x-1 text-2xl" id="rating-stars">
                                        <input type="radio" id="star5" name="rating" value="1"
                                            class="hidden" />
                                        <label for="star5"
                                            class="cursor-pointer text-gray-300 hover:text-yellow-400 transition duration-200">&#9733;</label>

                                        <input type="radio" id="star4" name="rating" value="2"
                                            class="hidden" />
                                        <label for="star4"
                                            class="cursor-pointer text-gray-300 hover:text-yellow-400 transition duration-200">&#9733;</label>

                                        <input type="radio" id="star3" name="rating" value="3"
                                            class="hidden" />
                                        <label for="star3"
                                            class="cursor-pointer text-gray-300 hover:text-yellow-400 transition duration-200">&#9733;</label>

                                        <input type="radio" id="star2" name="rating" value="4"
                                            class="hidden" />
                                        <label for="star2"
                                            class="cursor-pointer text-gray-300 hover:text-yellow-400 transition duration-200">&#9733;</label>

                                        <input type="radio" id="star1" name="rating" value="5"
                                            class="hidden" />
                                        <label for="star1"
                                            class="cursor-pointer text-gray-300 hover:text-yellow-400 transition duration-200">&#9733;</label>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="flex justify-between mt-4">
                            <div class="flex flex-col">
                                <h6 class="font-semibold text-base">TOTAL</h6>
                            </div>
                            <div class="text-end">
                                <h6 class="font-semibold text-base" id="totalPrice"></h6>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
            <div class="flex flex-col gap-2">
                <h4 class="text-base font-semibold opacity-70">CHOOSE A PAYMENT METHOD</h4>
                @if (Auth()->user()->hasRole('admin'))
                    <div id="cashButton"
                        class="cursor-pointer p-2 rounded-md flex text-white items-center justify-between bg-blue-400 hover:bg-blue-500 bg-opacity-80">
                        <div class="flex items-center">
                            <i class="bx bx-money scale-150 mx-2"></i>
                            <p class="font-semibold ml-1">Cash</p>
                        </div>
                        <i class="bx bx-chevron-right scale-150"></i>
                    </div>
                @elseif (Auth()->user()->hasRole('customer'))
                    <div id="eWalletButton"
                        class="cursor-pointer p-2 rounded-md flex text-white items-center justify-between bg-blue-400 hover:bg-blue-500 bg-opacity-80">
                        <div class="flex items-center">
                            <i class="bx bx-money scale-150 mx-2"></i>
                            <p class="font-semibold ml-1">e-Wallet</p>
                        </div>
                        <i class="bx bx-chevron-right scale-150"></i>
                    </div>
                @endif
                @if (Auth()->user()->hasRole('admin'))
                    <div id="creditButton" type="button"
                        class="cursor-pointer p-2 rounded-md flex text-white items-center justify-between bg-blue-400 hover:bg-blue-500 bg-opacity-80">
                        <div class="flex items-center">
                            <i class="bx bx-money-withdraw scale-150 mx-2"></i>
                            <p class="font-semibold ml-1">Credit</p>
                        </div>
                        <i class="bx bx-chevron-right scale-150"></i>
                    </div>
                @elseif (Auth()->user()->hasRole('customer'))
                    <div id="transferButton"
                        class="cursor-pointer p-2 rounded-md flex text-white items-center justify-between bg-blue-400 hover:bg-blue-500 bg-opacity-80">
                        <div class="flex items-center">
                            <i class="bx bx-credit-card scale-150 mx-2"></i>
                            <p class="font-semibold ml-1">Transfer Bank</p>
                        </div>
                        <i class="bx bx-chevron-right scale-150"></i>
                    </div>
                @endif
            </div>
        </div>

        {{-- BATAS --}}
        {{-- CASH --}}

        <div id="paymentMethod"
            class="bg-white rounded-lg shadow-lg hidden flex-col justify-between p-6 max-w-sm w-full"
            style="height: 54vh; margin-top: 17.5rem">
        </div>
    </form>


    <div id="confirmModal" class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-xl w-full" style="max-height: 92vh; height: auto;">
            <div class="flex items-center justify-between">
                <i id="closeWorkModal"
                    class="bx bx-arrow-back scale-125 font-extrabold mb-4 cursor-pointer hover:scale-150"></i>
                <h2 class="text-xl font-bold mb-4 pr-4 mx-auto underline">Confirm Payment</h2>
            </div>
            <hr>
            <div id="note"
                class="request-container overflow-y-scroll flex flex-col justify-center border border-gray-300 rounded-md shadow-md"
                style="max-height: 480px">
                <div class="flex justify-between p-5 max-w-lg mx-auto">
                    <!-- Bagian Kiri: Logo dan Nama Usaha -->
                    <div class="flex items-center space-x-3" id="request-1">
                        <img src="{{ asset('assets/images/lg.png') }}" alt="logoLOEmbroidery" class="h-16 w-16">
                        <h3 class="font-sans font-bold text-lg">Embroidery</h3>
                    </div>

                    <!-- Bagian Kanan: Data Customer -->
                    <div class="flex flex-col space-y-2">
                        <div class="grid grid-cols-2 gap-x-4 text-sm">
                            <!-- Date -->
                            <div class="flex justify-end text-gray-600 font-semibold">
                                <span>Date</span>
                                <span class="ml-2">:</span>
                            </div>
                            <span class="text-gray-800 font-medium" id="paymentDate"></span>

                            <!-- Name -->
                            <div class="flex justify-end text-gray-600 font-semibold">
                                <span>Name</span>
                                <span class="ml-2">:</span>
                            </div>
                            <span class="text-gray-800 font-medium" id="customerName"></span>

                            <!-- Phone -->
                            <div class="flex justify-end text-gray-600 font-semibold">
                                <span>Phone</span>
                                <span class="ml-2">:</span>
                            </div>
                            <span class="text-gray-800 font-medium" id="customerNumber"></span>

                            <!-- Address -->
                            <div class="flex justify-end text-gray-600 font-semibold">
                                <span>Address</span>
                                <span class="ml-2">:</span>
                            </div>
                            <span class="text-gray-800 font-medium truncate max-w-xs overflow-wrap text-wrap"
                                style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;"
                                id="customerAddress">
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex scale-75 -ml-10">
                    <a href="https://www.instagram.com/lala.ociz/" target="_blank" rel="noopener noreferrer">
                        <div class="flex items-center hover:text-blue-500">
                            <i class="bx bxl-instagram-alt pr-1"></i>
                            <p>@loembroidery_</p>
                        </div>
                    </a>
                    <a href="https://www.facebook.com/lala.ociz?locale=ms_MY" target="_blank"
                        rel="noopener noreferrer">
                        <div class="flex items-center mx-5 hover:text-blue-500">
                            <i class="bx bxl-facebook-square pr-1"></i>
                            <p>loembroidery</p>
                        </div>
                    </a>
                    <a href="https://wa.me/+628815884512" target="_blank" rel="noopener noreferrer">
                        <div class="flex items-center hover:text-blue-500">
                            <i class="bx bxl-whatsapp-square pr-1"></i>
                            <p>08815184624</p>
                        </div>
                    </a>
                </div>
                <hr>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300 rounded-lg shadow-md">
                        <!-- Header Table -->
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left border-b">No</th>
                                <th class="py-3 px-6 text-left border-b">Item</th>
                                <th class="py-3 px-6 text-center border-b">Qty</th>
                                <th class="py-3 px-6 text-right border-b">Price</th>
                                <th class="py-3 px-6 text-right border-b">Amount</th>
                            </tr>
                        </thead>

                        <!-- Body Table -->
                        <tbody id="requestToConfirm" class="text-gray-700 text-sm font-light">

                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between p-5">
                    <div class="flex items-center space-x-8">
                        <!-- Kolom kiri -->
                        <div class="space-y-3">
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" id="cashCheckbox2" name="cashCheckbox2"
                                    class="rounded-lg h-5 w-5 text-blue-500 border-gray-300 focus:ring-blue-500">
                                <label for="cashCheckbox2" class="font-medium text-gray-700">Cash</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" id="creditCheckbox2" name="creditCheckbox2"
                                    class="rounded-lg h-5 w-5 text-blue-500 border-gray-300 focus:ring-blue-500">
                                <label for="credit" class="font-medium text-gray-700">Credit</label>
                            </div>
                        </div>
                        <!-- Kolom kanan -->
                        <div class="space-y-3">
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" id="ewalletCheckbox2" name="ewalletCheckbox2"
                                    class="rounded-lg h-5 w-5 text-blue-500 border-gray-300 focus:ring-blue-500">
                                <label for="ewallet" class="font-medium text-gray-700">e-Wallet</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" id="transferCheckbox2" name="transferCheckbox2"
                                    class="rounded-lg h-5 w-5 text-blue-500 border-gray-300 focus:ring-blue-500">
                                <label for="transfer" class="font-medium text-gray-700">Transfer</label>
                            </div>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="flex items-center gap-2">
                        <img id="proofPic" src="" alt="proofPic"
                    class="w-10 h-14 rounded-lg object-cover cursor-pointer">
                        <div id="imageOverlay" class="fixed inset-0 bg-black bg-opacity-75 items-center justify-center hidden">
                            <img id="fullProofPic" class="full-screen-image w-20 h-auto" src="" alt="Full Screen User Profile">
                        </div>
                        <div class="flex items-start flex-col space-x-2">
                            <span class="text-gray-600 font-light ml-2">TOTAL:</span>
                            <span class="text-gray-800 font-medium" id="total"></span>
                        </div>
                    </div>

                </div>
            </div>
            <div class="w-full h-auto flex items-center justify-end p-4">
                <form id="confirmForm" action="{{ route('transaction.confirm', ':id') }}" method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="TransHeaderId" name="transHeaderId">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-md shadow-md hover:bg-blue-600 transition duration-200">
                        Confirm
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div id="viewModal" class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-xl w-full" style="max-height: 92vh; height: auto;">
            <div class="flex items-center justify-between">
                <i id="closeWorkModal"
                    class="bx bx-arrow-back scale-125 font-extrabold mb-4 cursor-pointer hover:scale-150"></i>
                <h2 class="text-xl font-bold mb-4 pr-4 mx-auto underline">Payment Note</h2>
            </div>
            <hr>
            <div id="notePanel"
                class="request-container overflow-y-scroll flex flex-col justify-center border border-gray-300 rounded-md shadow-md"
                style="max-height: 480px">
                <div class="flex justify-between p-5 max-w-lg mx-auto">
                    <!-- Bagian Kiri: Logo dan Nama Usaha -->
                    <div class="flex items-center space-x-3" id="request-1">
                        <img src="{{ asset('assets/images/lg.png') }}" alt="logoLOEmbroidery" class="h-16 w-16">
                        <h3 class="font-sans font-bold text-lg">Embroidery</h3>
                    </div>

                    <!-- Bagian Kanan: Data Customer -->
                    <div class="flex flex-col space-y-2">
                        <div class="grid grid-cols-2 gap-x-4 text-sm">
                            <!-- Date -->
                            <div class="flex justify-end text-gray-600 font-semibold">
                                <span>Date</span>
                                <span class="ml-2">:</span>
                            </div>
                            <span class="text-gray-800 font-medium" id="paymentDate"></span>

                            <!-- Name -->
                            <div class="flex justify-end text-gray-600 font-semibold">
                                <span>Name</span>
                                <span class="ml-2">:</span>
                            </div>
                            <span class="text-gray-800 font-medium" id="customerName"></span>

                            <!-- Phone -->
                            <div class="flex justify-end text-gray-600 font-semibold">
                                <span>Phone</span>
                                <span class="ml-2">:</span>
                            </div>
                            <span class="text-gray-800 font-medium" id="customerNumber"></span>

                            <!-- Address -->
                            <div class="flex justify-end text-gray-600 font-semibold">
                                <span>Address</span>
                                <span class="ml-2">:</span>
                            </div>
                            <span class="text-gray-800 font-medium truncate max-w-xs overflow-wrap text-wrap"
                                style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;"
                                id="customerAddress">
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex scale-75 -ml-10">
                    <a href="https://www.instagram.com/lala.ociz/" target="_blank" rel="noopener noreferrer">
                        <div class="flex items-center hover:text-blue-500">
                            <i class="bx bxl-instagram-alt pr-1"></i>
                            <p>@loembroidery_</p>
                        </div>
                    </a>
                    <a href="https://www.facebook.com/lala.ociz?locale=ms_MY" target="_blank"
                        rel="noopener noreferrer">
                        <div class="flex items-center mx-5 hover:text-blue-500">
                            <i class="bx bxl-facebook-square pr-1"></i>
                            <p>loembroidery</p>
                        </div>
                    </a>
                    <a href="https://wa.me/+628815884512" target="_blank" rel="noopener noreferrer">
                        <div class="flex items-center hover:text-blue-500">
                            <i class="bx bxl-whatsapp-square pr-1"></i>
                            <p>08815184624</p>
                        </div>
                    </a>
                </div>
                <hr>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300 rounded-lg shadow-md">
                        <!-- Header Table -->
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left border-b">No</th>
                                <th class="py-3 px-6 text-left border-b">Item</th>
                                <th class="py-3 px-6 text-center border-b">Qty</th>
                                <th class="py-3 px-6 text-right border-b">Price</th>
                                <th class="py-3 px-6 text-right border-b">Amount</th>
                            </tr>
                        </thead>

                        <!-- Body Table -->
                        <tbody id="requests" class="text-gray-700 text-sm font-light">
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-6 text-left whitespace-nowrap">1</td>
                                <td class="py-3 px-6 text-left">Embroidery Patch</td>
                                <td class="py-3 px-6 text-center">3</td>
                                <td class="py-3 px-6 text-right">Rp20,000</td>
                                <td class="py-3 px-6 text-right">Rp60,000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between p-5">
                    <div class="flex items-center space-x-8">
                        <!-- Kolom kiri -->
                        <div class="space-y-3">
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" id="cashCheckBox1" name="cashCheckBox1"
                                    class="rounded-lg h-5 w-5 text-blue-500 border-gray-300 focus:ring-blue-500">
                                <label for="credit" class="font-medium text-gray-700">Cash</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" id="creditCheckbox1" name="creditCheckbox1"
                                    class="rounded-lg h-5 w-5 text-blue-500 border-gray-300 focus:ring-blue-500">
                                <label for="credit" class="font-medium text-gray-700">Credit</label>
                            </div>
                        </div>
                        <!-- Kolom kanan -->
                        <div class="space-y-3">
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" id="ewalletCheckbox1" name="ewalletCheckbox1"
                                    class="rounded-lg h-5 w-5 text-blue-500 border-gray-300 focus:ring-blue-500">
                                <label for="ewallet" class="font-medium text-gray-700">e-Wallet</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" id="transferCheckbox1" name="transferCheckbox1"
                                    class="rounded-lg h-5 w-5 text-blue-500 border-gray-300 focus:ring-blue-500">
                                <label for="transfer" class="font-medium text-gray-700">Transfer</label>
                            </div>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-600 font-semibold">TOTAL:</span>
                        <span class="text-gray-800 font-medium" id="total"></span>
                    </div>
                </div>
            </div>
            <div class="w-full h-auto flex items-center justify-end p-4">
                <button type="button" id="print"
                    class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-md shadow-md hover:bg-blue-600 transition duration-200">
                    Print
                </button>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const proofPic = document.getElementById('proofPic');
            const fullProofPic = document.getElementById('fullProofPic');
            const imageOverlay = document.getElementById('imageOverlay');

            proofPic.addEventListener('click', function() {
                fullProofPic.src = this.src;
                imageOverlay.style.display = 'flex'; // Tampilkan overlay
            });

            imageOverlay.addEventListener('click', function() {
                imageOverlay.style.display = 'none'; // Sembunyikan overlay saat diklik
            });
        });
    </script>
    <script>
        document.getElementById("print").addEventListener("click", function() {
            const notePanel = document.getElementById("notePanel");

            // Duplikat konten `notePanel` dan set `checked` attribute secara manual
            const notePanelClone = notePanel.cloneNode(true);
            notePanelClone.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                if (checkbox.checked) {
                    checkbox.setAttribute('checked', 'checked');
                } else {
                    checkbox.removeAttribute('checked');
                }
            });

            const notePanelContent = notePanelClone.innerHTML;
            // Membuat iframe untuk mencetak
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            document.body.appendChild(iframe);

            const doc = iframe.contentWindow.document;
            doc.open();
            doc.write(`
                    <html>
                        <head>
                            <title>Print Note Panel</title>
                            <link href="https://unpkg.com/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
                            <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
                        </head>
                        <body class="p-5">
                            ${notePanelContent}
                        </body>
                    </html>
                `);
            doc.close();

            // Mencetak konten dalam iframe
            iframe.contentWindow.focus();
            iframe.contentWindow.print();

            // Menghapus iframe setelah cetak
            iframe.contentWindow.onafterprint = function() {
                document.body.removeChild(iframe);
                // Refresh halaman setelah pencetakan
                window.location.reload();
            };
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewButton = document.querySelectorAll('.view-button');
            const confirmButton = document.querySelectorAll('.confirm-button');
            const viewModal = document.getElementById('viewModal');
            const confirmModal = document.getElementById('confirmModal');
            const closeWorkModal = document.getElementById('closeWorkModal');
            const workForm = document.getElementById('workForm');
            const requests = document.getElementById('requests');
            const requestToConfirm = document.getElementById('requestToConfirm');
            const checkboxes = [
                'cashCheckBox',
                'creditCheckbox',
                'ewalletCheckbox',
                'transferCheckbox'
            ].map(id => document.getElementById(id));
            const checkboxes2 = [
                'cashCheckBox2',
                'creditCheckbox2',
                'ewalletCheckbox2',
                'transferCheckbox2'
            ].map(id => document.getElementById(id));


            function checkMethod(paymentMethod) {
                console.log('Payment method received:', paymentMethod);

                // Get all checkboxes
                const cashCheckBox = document.getElementById('cashCheckBox1');
                const creditCheckbox = document.getElementById('creditCheckbox1');
                const ewalletCheckbox = document.getElementById('ewalletCheckbox1');
                const transferCheckbox = document.getElementById('transferCheckbox1');

                // Reset all checkboxes
                [creditCheckbox, ewalletCheckbox, transferCheckbox, cashCheckBox].forEach(checkbox => {
                    if (checkbox) checkbox.checked = false;
                });

                // Check the appropriate checkbox based on payment method
                switch (paymentMethod) {
                    case 'cash':
                        cashCheckBox.checked = true;
                        break;
                    case 'credit':
                        creditCheckbox.checked = true;
                        break;
                    case 'e-wallet':
                        ewalletCheckbox.checked = true;
                        break;
                    case 'transfer_bank':
                        transferCheckbox.checked = true;
                        break;
                    default:
                        console.warn('Unsupported payment method:', paymentMethod);
                }
            }
            function checkMethod2(paymentMethod) {
                console.log('Payment method received:', paymentMethod);

                // Get all checkboxes
                const cashCheckBox2 = document.getElementById('cashCheckBox2');
                const creditCheckbox2 = document.getElementById('creditCheckbox2');
                const ewalletCheckbox2 = document.getElementById('ewalletCheckbox2');
                const transferCheckbox2 = document.getElementById('transferCheckbox2');

                // Reset all checkboxes
                [creditCheckbox2, ewalletCheckbox2, transferCheckbox2, cashCheckBox2].forEach(checkbox => {
                    if (checkbox) checkbox.checked = false;
                });

                // Check the appropriate checkbox based on payment method
                switch (paymentMethod) {
                    case 'cash':
                        cashCheckBox2.checked = true;
                        break;
                    case 'credit':
                        creditCheckbox2.checked = true;
                        break;
                    case 'e-wallet':
                        ewalletCheckbox2.checked = true;
                        break;
                    case 'transfer_bank':
                        transferCheckbox2.checked = true;
                        break;
                    default:
                        console.warn('Unsupported payment method:', paymentMethod);
                }
            }
            const cashPaymentInput = transactionForm.querySelector('input[name="cashPayment"]');
            if (cashPaymentInput) {
                cashPaymentInput.value = total;
            }

            confirmButton.forEach(button => {
                button.addEventListener('click', function() {
                    const transactionId = this.getAttribute('data-id');
                    const paymentDate = this.getAttribute('data-date');
                    const customerName = this.getAttribute('data-customerName');
                    const proofPic = this.getAttribute('data-proofPic');
                    const customerNumber = this.getAttribute('data-customerNumber');
                    const customerAddress = this.getAttribute('data-customerAddress');
                    const paymentMethod = this.getAttribute('data-paymentMethod');
                    const total = this.getAttribute('data-total');
                    confirmModal.querySelector('#paymentDate').textContent = paymentDate;
                    confirmModal.querySelector('#customerName').textContent = customerName;
                    confirmModal.querySelector('#customerNumber').textContent =
                        customerNumber;
                    confirmModal.querySelector('#customerAddress').textContent =
                        customerAddress;
                    confirmModal.querySelector('#total').textContent = total;
                    confirmModal.querySelector('input[name="transHeaderId"]').value = transactionId;
                    confirmModal.querySelector('img[alt="proofPic"]').src = proofPic;
                    confirmModal.classList.remove('hidden');
                    confirmModal.classList.add('flex');
                    checkMethod2(paymentMethod);
                    const confirmForm = document.getElementById('confirmForm');
                    confirmForm.action = `{{ url('/transaction/confirm') }}/${transactionId}`;

                    requestToConfirm.innerHTML = '';

                    // Pastikan `transactionId` adalah ID transaksi yang valid
                    fetch(`/getDetailTransactions/${transactionId}`)
                        .then(response => {
                            if (!response.ok) throw new Error(
                                'Network response was not ok');
                            return response.json();
                        })
                        .then(data => {
                            console.log('Data received:', JSON.stringify(data, null,
                                2)); // Menampilkan data lengkap di konsol

                            // Cek jika data kosong
                            if (data.length === 0) {
                                requestToConfirm.innerHTML =
                                    `<tr><td colspan="5" class="text-center">Tidak ada data transaksi.</td></tr>`;
                                return;
                            }

                            let no = 1; // Inisialisasi nomor baris
                            data.forEach(transaction => {
                                const designRequest = transaction
                                    .design_request;

                                // Validasi bahwa designRequest ada sebelum ditampilkan
                                if (!designRequest) {
                                    console.warn(
                                        'Design request is missing for transaction',
                                        transaction);
                                    return; // Lewati iterasi jika `design_request` tidak ada
                                }

                                // Menambahkan baris data ke tabel
                                requestToConfirm.innerHTML += `
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="py-3 px-6 text-left whitespace-nowrap">${no}</td>
                                    <td class="py-3 px-6 text-left">${designRequest.name}</td>
                                    <td class="py-3 px-6 text-center">${designRequest.total_pieces}</td>
                                    <td class="py-3 px-6 text-right">${designRequest.price_per_piece}</td>
                                    <td class="py-3 px-6 text-right">${transaction.subtotal}</td>
                                </tr>
                                `;
                                no++;
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching design requestToConfirm:',
                                error);
                            alert('Failed to retrieve design requestToConfirm.');
                        });
                });
            });
            viewButton.forEach(button => {
                button.addEventListener('click', function() {
                    const transactionId = this.getAttribute('data-id');
                    const paymentDate = this.getAttribute('data-date');
                    const customerName = this.getAttribute('data-customerName');
                    const customerNumber = this.getAttribute('data-customerNumber');
                    const customerAddress = this.getAttribute('data-customerAddress');
                    const paymentMethod = this.getAttribute('data-paymentMethod');
                    const total = this.getAttribute('data-total');
                    viewModal.querySelector('#paymentDate').textContent = paymentDate;
                    viewModal.querySelector('#customerName').textContent = customerName;
                    viewModal.querySelector('#customerNumber').textContent = customerNumber;
                    viewModal.querySelector('#customerAddress').textContent =
                        customerAddress;
                    viewModal.querySelector('#total').textContent = total;
                    viewModal.classList.remove('hidden');
                    viewModal.classList.add('flex');
                    checkMethod(paymentMethod);

                    requests.innerHTML = '';

                    // Pastikan `transactionId` adalah ID transaksi yang valid
                    fetch(`/getDetailTransactions/${transactionId}`)
                        .then(response => {
                            if (!response.ok) throw new Error(
                                'Network response was not ok');
                            return response.json();
                        })
                        .then(data => {
                            console.log('Data received:', JSON.stringify(data, null,
                                2)); // Menampilkan data lengkap di konsol

                            // Cek jika data kosong
                            if (data.length === 0) {
                                requests.innerHTML =
                                    `<tr><td colspan="5" class="text-center">Tidak ada data transaksi.</td></tr>`;
                                return;
                            }

                            let no = 1; // Inisialisasi nomor baris
                            data.forEach(transaction => {
                                const designRequest = transaction
                                    .design_request;

                                // Validasi bahwa designRequest ada sebelum ditampilkan
                                if (!designRequest) {
                                    console.warn(
                                        'Design request is missing for transaction',
                                        transaction);
                                    return; // Lewati iterasi jika `design_request` tidak ada
                                }

                                // Menambahkan baris data ke tabel
                                requests.innerHTML += `
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="py-3 px-6 text-left whitespace-nowrap">${no}</td>
                                    <td class="py-3 px-6 text-left">${designRequest.name}</td>
                                    <td class="py-3 px-6 text-center">${designRequest.total_pieces}</td>
                                    <td class="py-3 px-6 text-right">${designRequest.price_per_piece}</td>
                                    <td class="py-3 px-6 text-right">${transaction.subtotal}</td>
                                </tr>
                                `;
                                no++;
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching design requests:', error);
                            alert('Failed to retrieve design requests.');
                        });
                });
            });

            // Tutup modal saat tombol "Close" diklik
            closeWorkModal.addEventListener('click', function() {
                viewModal.classList.add('hidden');
                viewModal.classList.remove('flex');
            });

            // Tutup modal saat area di luar modal diklik
            viewModal.addEventListener('click', function(e) {
                if (e.target === viewModal) {
                    viewModal.classList.add('hidden');
                    viewModal.classList.remove('flex');
                }
            });
            confirmModal.addEventListener('click', function(e) {
                if (e.target === confirmModal) {
                    confirmModal.classList.add('hidden');
                    confirmModal.classList.remove('flex');
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const payButton = document.querySelectorAll('.pay-button');
            const payButton2 = document.querySelectorAll('.pay2-button');
            const transactionForm = document.getElementById('transactionForm');
            const closeTransactionForm = document.getElementById('closeTransactionForm');
            const requestContainer = document.getElementById('request-container');
            const totalElement = transactionForm.querySelector('#totalPrice');
            const totalPaymentCard = transactionForm.querySelector('#totalPaymentCard');

            // Untuk payment Method
            const cashButton = document.querySelectorAll('#cashButton');
            const creditButton = document.querySelectorAll('#creditButton');
            const eWalletButton = document.querySelectorAll('#eWalletButton');
            const transferButton = document.querySelectorAll('#transferButton');
            const closePaymentCard = document.querySelectorAll('#closePaymentCard');
            const paymentCard = document.getElementById('paymentMethod');
            let totalAll = 0;

            function updateTotal() {
                const checkboxes = document.querySelectorAll('.design-checkbox:checked');
                let total = 0;

                checkboxes.forEach(checkbox => {
                    const price = parseFloat(checkbox.getAttribute('data-price'));
                    total += price;
                });

                // Update total payment display
                totalElement.textContent = `RP${total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}`;

                const totalPaymentPrice = document.getElementById('totalPaymentPrice');
                if (totalPaymentPrice) {
                    totalPaymentPrice.value = total;
                }

                const totalPaymentCardElement = document.getElementById('totalPaymentCard');
                if (totalPaymentCardElement) {
                    totalPaymentCardElement.textContent =
                        `RP${total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}`;
                }

                const cashPaymentInput = transactionForm.querySelector('input[name="cashPayment"]');
                if (cashPaymentInput) {
                    cashPaymentInput.value = total;
                }
                const creditPaymentInput = transactionForm.querySelector('input[name="creditPayment"]');
                if (creditPaymentInput) {
                    const maxCredit = total - (total * (10 / 100));
                    creditPaymentInput.max = maxCredit;

                    // Listen for input events and enforce max limit
                    // creditPaymentInput.addEventListener('input', function() {
                    //     if (parseFloat(creditPaymentInput.value) > maxCredit) {
                    //         creditPaymentInput.value = maxCredit; // Set to max if value exceeds limit
                    //         // alert(`Maximum allowed down payment is: RP${maxCredit.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}`);
                    //     }
                    // });
                }
            }

            payButton.forEach(button => {
                button.addEventListener('click', function() {
                    let customerName = this.getAttribute('data-customerName');
                    let customerId = this.getAttribute('data-customerId');
                    let customerAddress = this.getAttribute('data-customerAddress');
                    let transDate = this.getAttribute('data-transDate');
                    transactionForm.querySelector('input[name="customerId"]').value = customerId;
                    transactionForm.querySelector('#customerName').textContent = customerName;
                    transactionForm.querySelector('#customerAddress').textContent = customerAddress;
                    transactionForm.querySelector('#transDate').textContent = transDate;
                    transactionForm.classList.remove('hidden');
                    transactionForm.classList.add('flex');
                    updateTotal();

                    let headerId = this.getAttribute('data-id');
                    console.log('Header ID:', headerId);

                    function formatRupiah(number) {
                        return 'Rp' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") +
                            '.00';
                    }

                    requestContainer.innerHTML = '';
                    paymentCard.innerHTML = '';

                    fetch(`/getDesignRequests/${headerId}`)
                        .then(response => {
                            console.log('Fetching data from:',
                                `/getDesignRequests/${headerId}`);
                            return response.json();
                        })
                        .then(data => {
                            console.log('Data received:', data);
                            data.forEach(request => {
                                const totalPrice = request.price_per_piece * request
                                    .total_pieces;
                                requestContainer.innerHTML += `
                                <div class="flex flex-col px-3" id="request-${request.id}">
                                    <div>
                                        <div class="w-full flex justify-between">
                                            <div class="pr-2 py-2 text-black font-semibold flex items-center">
                                                <input type="checkbox" id="custom-checkbox-${request.id}"
                                                class="design-checkbox form-checkbox cursor-pointer h-5 w-5 text-blue-500 border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:ring-opacity-50 mr-2.5"
                                                value="${request.id}" name="designRequests[]" data-price="${totalPrice}">
                                                <img src="${request.reference_image}"
                                                    class="w-9 h-9 rounded-md mr-2" alt="Design Image">
                                                <h3 id="designName">${request.name}</h3>
                                                <h4 id="designPiece" class="font-light text-xs pt-1 pl-1">(${request.total_pieces})
                                                </h4>
                                            </div>
                                            <div class="p-2 text-black font-semibold flex items-center">
                                                <h4 id="designPrice" class="font-semibold text-sm pt-1 pl-1">
                                                     ${formatRupiah(totalPrice)}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                `;
                            });
                            const newCheckboxes = document.querySelectorAll('.design-checkbox');
                            newCheckboxes.forEach(checkbox => {
                                checkbox.addEventListener('change', updateTotal);
                            });

                            updateTotal();
                        })
                        .catch(error => {
                            console.error('Error fetching design requests:', error);
                            alert('Failed to retrieve design requests.');
                        });

                });
            });

            payButton2.forEach(button => {
                button.addEventListener('click', function() {
                    let customerName = this.getAttribute('data-customerName');
                    let customerId = this.getAttribute('data-customerId');
                    let customerAddress = this.getAttribute('data-customerAddress');
                    let transDate = this.getAttribute('data-transDate');
                    transactionForm.querySelector('input[name="customerId"]').value = customerId;
                    transactionForm.querySelector('#customerName').textContent = customerName;
                    transactionForm.querySelector('#customerAddress').textContent = customerAddress;
                    transactionForm.querySelector('#transDate').textContent = transDate;
                    transactionForm.classList.remove('hidden');
                    transactionForm.classList.add('flex');
                    updateTotal();

                    let headerId = this.getAttribute('data-id');
                    console.log('Header ID:', headerId);

                    function formatRupiah(number) {
                        return 'Rp' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") +
                            '.00';
                    }

                    requestContainer.innerHTML = '';
                    paymentCard.innerHTML = '';

                    fetch(`/getDesignRequests/${headerId}`)
                        .then(response => {
                            console.log('Fetching data from:',
                                `/getDesignRequests/${headerId}`);
                            return response.json();
                        })
                        .then(data => {
                            console.log('Data received:', data);
                            data.forEach(request => {
                                const totalPrice = request.price_per_piece * request
                                    .total_pieces;
                                requestContainer.innerHTML += `
                                <div class="flex flex-col px-3" id="request-${request.id}">
                                    <div>
                                        <div class="w-full flex justify-between">
                                            <div class="pr-2 py-2 text-black font-semibold flex items-center">
                                                <input type="checkbox" id="custom-checkbox-${request.id}"
                                                class="design-checkbox form-checkbox cursor-pointer h-5 w-5 text-blue-500 border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:ring-opacity-50 mr-2.5"
                                                value="${request.id}" name="designRequests[]" data-price="${totalPrice}">
                                                <img src="${request.reference_image}"
                                                    class="w-9 h-9 rounded-md mr-2" alt="Design Image">
                                                <h3 id="designName">${request.name}</h3>
                                                <h4 id="designPiece" class="font-light text-xs pt-1 pl-1">(${request.total_pieces})
                                                </h4>
                                            </div>
                                            <div class="p-2 text-black font-semibold flex items-center">
                                                <h4 id="designPrice" class="font-semibold text-sm pt-1 pl-1">
                                                     ${formatRupiah(totalPrice)}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                `;
                            });
                            const newCheckboxes = document.querySelectorAll('.design-checkbox');
                            newCheckboxes.forEach(checkbox => {
                                checkbox.addEventListener('change', updateTotal);
                            });

                            updateTotal();
                        })
                        .catch(error => {
                            console.error('Error fetching design requests:', error);
                            alert('Failed to retrieve design requests.');
                        });

                });
            });

            closeTransactionForm.addEventListener('click', function() {
                // Menampilkan konfirmasi
                const userConfirmed = confirm('Close this transaction?');

                if (userConfirmed) {
                    // Jika pengguna memilih "OK"
                    transactionForm.classList.add('hidden');
                    transactionForm.classList.remove('flex');
                    paymentCard.classList.remove('flex');
                    paymentCard.classList.add('hidden');
                    requestContainer.innerHTML = ''; // Kosongkan container saat modal ditutup
                }
            });

            // --- Payment Method ---
            // CASH
            cashButton.forEach(button => {
                button.addEventListener('click', function() {
                    paymentCard.innerHTML = "";
                    paymentCard.innerHTML = `
                    <div>
                        <div class="flex items-center justify-between w-full">
                        <h2 class="text-base font-bold opacity-65">CASH</h2>
                        <i id="closePaymentCard"
                        class="bx bx-x scale-125 font-extrabold cursor-pointer hover:scale-150"></i>
                    </div>
                    <hr>
                    <div class="flex justify-between font-semibold text-lg py-5">
                        <h1>Total Payment</h1>
                        <h1 id="totalPaymentCard"></h1>
                    </div>
                    <div>
                        <table class="w-full table-auto border-separate border-spacing-0 overflow-hidden rounded-lg">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="px-4 py-2 text-left text-gray-700 border flex items-center border-gray-300">
                                        <span>Cash</span>
                                        <i class="bx bx-money text-green-500 pl-2 text-xl"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-4 py-4 border border-gray-300">
                                        <div class="flex flex-col space-y-2">
                                            <p class="text-gray-600">Input amount</p>
                                            <input type="hidden" name="method" value="cash" />
                                            <input type="number" readonly name="cashPayment" id="cashPayment"
                                            class="w-full px-4 py-2 bg-slate-100 text-gray-700 rounded-md border border-neutral-300 focus:border-blue-500 focus:ring focus:ring-blue-200 outline-none">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <button id="submitButton" type="submit"
                        class="p-2 rounded-md flex text-white items-center justify-center bg-blue-500 bg-opacity-80">
                        <h3>PAY</h3>
                    </button>
                </div>
                    `;
                    paymentCard.classList.remove('hidden');
                    paymentCard.classList.add('flex');
                    updateTotal();

                    const submitButton = document.querySelector('#submitButton');
                    if (submitButton) {
                        submitButton.addEventListener('click', (event) => {
                            const checkboxes = document.querySelectorAll(
                                '.design-checkbox');
                            const atLeastOneChecked = Array.from(checkboxes).some(
                                checkbox => checkbox.checked);
                            if (!atLeastOneChecked) {
                                event.preventDefault(); // Prevent form submission
                                alert('Please select at least one design request.');
                            }
                        });
                    }
                });
            });

            // CREDIT
            creditButton.forEach(button => {
                button.addEventListener('click', function() {
                    paymentCard.innerHTML = "";
                    paymentCard.innerHTML = `
                    <div>
                        <div class="flex items-center justify-between w-full">
                            <h2 class="text-base font-bold opacity-65">CREDIT</h2>
                            <i id="closeApproveModal"
                                class="bx bx-x scale-125 font-extrabold cursor-pointer hover:scale-150"></i>
                        </div>
                        <hr>
                        <div class="flex justify-between font-semibold text-lg py-5">
                            <h1>Total Payment</h1>
                            <h1 id="totalPaymentCard"></h1>
                        </div>
                        <div>
                            <table class="w-full table-auto border-separate border-spacing-0 overflow-hidden rounded-lg">
                                <thead class="bg-gray-200">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-gray-700 border flex items-center border-gray-300">
                                            <span>Down Payment</span>
                                            <i class="bx bx-money-withdraw text-green-500 pl-2 text-xl"></i>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-4 py-4 border border-gray-300">
                                            <div class="flex flex-col space-y-2">
                                                <p class="text-gray-600">Input DP</p>
                                                <input type="hidden" name="method" value="credit" />
                                                <input type="number" name="creditPayment"  min="0" required
                                                    class="w-full px-4 py-2 rounded-md border border-neutral-300 focus:border-blue-500 focus:ring focus:ring-blue-200 outline-none">
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <button id="submitButton" type="submit"
                            class="p-2 rounded-md flex text-white items-center justify-center bg-blue-500 bg-opacity-80">
                            <h3>PAY</h3>
                        </button>
                    </div>
                    `;
                    paymentCard.classList.remove('hidden');
                    paymentCard.classList.add('flex');
                    updateTotal();

                    const submitButton = document.querySelector('#submitButton');
                    if (submitButton) {
                        submitButton.addEventListener('click', (event) => {
                            const checkboxes = document.querySelectorAll(
                                '.design-checkbox');
                            const atLeastOneChecked = Array.from(checkboxes).some(
                                checkbox => checkbox.checked);
                            if (!atLeastOneChecked) {
                                event.preventDefault(); // Prevent form submission
                                alert('Please select at least one design request.');
                            }
                        });
                    }
                });
            });

            // E-WALLET
            eWalletButton.forEach(button => {
                button.addEventListener('click', function() {
                    paymentCard.innerHTML = "";
                    paymentCard.innerHTML = `
                    <div>
                        <div class="flex items-center justify-between w-full">
                            <h2 class="text-base font-bold opacity-65">E-MONEY</h2>
                            <i id="closeApproveModal"
                                class="bx bx-x scale-125 font-extrabold cursor-pointer hover:scale-150"></i>
                        </div>
                        <hr>
                        <div class="flex justify-between font-semibold text-lg py-5">
                            <h1>Total Payment</h1>
                            <h1 id="totalPaymentCard"></h1>
                        </div>
                        <div>
                            <table class="w-full table-auto border-separate border-spacing-0 overflow-hidden rounded-lg">
                                <thead class="bg-gray-200">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-gray-700 border flex items-center border-gray-300">
                                            <span>Upload Payment Proof</span>
                                            <i class="bx bx-image text-blue-500 pl-2 text-xl"></i>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-4 py-4 border border-gray-300">
                                            <div class="flex flex-col space-y-2">
                                                <p class="text-gray-600">Select image</p>
                                                <input type="hidden" name="method" value="ewallet" />
                                                <input type="file" name="proofPayment" accept="image/*" required
                                                    class="w-full px-4 py-2 rounded-md border border-neutral-300 focus:border-blue-500 focus:ring focus:ring-blue-200 outline-none">
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <button id="submitButton" type="submit"
                            class="p-2 rounded-md flex text-white items-center justify-center bg-blue-500 bg-opacity-80">
                            <h3>UPLOAD</h3>
                        </button>
                    </div>
                    `;
                    paymentCard.classList.remove('hidden');
                    paymentCard.classList.add('flex');
                    updateTotal();

                    const submitButton = document.querySelector('#submitButton');
                    if (submitButton) {
                        submitButton.addEventListener('click', (event) => {
                            const checkboxes = document.querySelectorAll(
                                '.design-checkbox');
                            const atLeastOneChecked = Array.from(checkboxes).some(
                                checkbox => checkbox.checked);
                            if (!atLeastOneChecked) {
                                event.preventDefault(); // Prevent form submission
                                alert('Please select at least one design request.');
                            }
                        });
                    }

                });
            });

            // TRANSFER BANK
            transferButton.forEach(button => {
                button.addEventListener('click', function() {
                    paymentCard.innerHTML = "";
                    paymentCard.innerHTML = `
                    <div>
                        <div class="flex items-center justify-between w-full">
                            <h2 class="text-base font-bold opacity-65">TRANSFER BANK</h2>
                            <i id="closeApproveModal"
                                class="bx bx-x scale-125 font-extrabold cursor-pointer hover:scale-150"></i>
                        </div>
                        <hr>
                        <div class="flex justify-between font-semibold text-lg py-5">
                            <h1>Total Payment</h1>
                            <h1 id="totalPaymentCard"></h1>
                        </div>
                        <div>
                            <table class="w-full table-auto border-separate border-spacing-0 overflow-hidden rounded-lg">
                                <thead class="bg-gray-200">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-gray-700 border flex items-center border-gray-300">
                                            <span>Upload Payment Proof</span>
                                            <i class="bx bx-image text-blue-500 pl-2 text-xl"></i>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-4 py-4 border border-gray-300">
                                            <div class="flex flex-col space-y-2">
                                                <p class="text-gray-600">Select image</p>
                                                <input type="hidden" name="method" value="transfer" />
                                                <input type="file" name="proofPayment" accept="image/*" required
                                                    class="w-full px-4 py-2 rounded-md border border-neutral-300 focus:border-blue-500 focus:ring focus:ring-blue-200 outline-none">
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <button id="submitButton" type="submit"
                            class="p-2 rounded-md flex text-white items-center justify-center bg-blue-500 bg-opacity-80">
                            <h3>UPLOAD</h3>
                        </button>
                    </div>
                    `;
                    paymentCard.classList.remove('hidden');
                    paymentCard.classList.add('flex');
                    updateTotal();

                    const submitButton = document.querySelector('#submitButton');
                    if (submitButton) {
                        submitButton.addEventListener('click', (event) => {
                            const checkboxes = document.querySelectorAll(
                                '.design-checkbox');
                            const atLeastOneChecked = Array.from(checkboxes).some(
                                checkbox => checkbox.checked);
                            if (!atLeastOneChecked) {
                                event.preventDefault(); // Prevent form submission
                                alert('Please select at least one design request.');
                            }
                        });
                    }

                });
            });
        });
    </script>


    <script>
        // Ambil semua label bintang
        const stars = document.querySelectorAll('#rating-stars label');
        const starInputs = document.querySelectorAll('#rating-stars input[type="radio"]');

        // Fungsi untuk memperbarui warna bintang yang dipilih
        function updateStars(rating) {
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.add('text-yellow-400');
                    star.classList.remove('text-gray-300');
                } else {
                    star.classList.add('text-gray-300');
                    star.classList.remove('text-yellow-400');
                }
            });
        }

        // Event listener untuk semua bintang
        starInputs.forEach((input, index) => {
            input.addEventListener('change', function() {
                updateStars(index + 1);
            });
        });

        // Hover efek untuk sementara menampilkan bintang kuning
        stars.forEach((star, index) => {
            star.addEventListener('mouseover', () => updateStars(index + 1));
            star.addEventListener('mouseout', () => {
                const selectedRating = document.querySelector('#rating-stars input[type="radio"]:checked');
                updateStars(selectedRating ? selectedRating.value : 0);
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewButton = document.querySelectorAll('.transactionButton');
            const approveModal = document.getElementById('transactionModal');
            const closeApproveModal = document.getElementById('closeTransactionModal');
            const approveForm = document.getElementById('transactionForm');

            viewButton.forEach(button => {
                button.addEventListener('click', function() {
                    const designId = this.getAttribute('data-design-id');
                    const designRequestId = this.getAttribute('data-design-requestId');
                    const designPic = this.getAttribute('data-design-pic');
                    const designName = this.getAttribute('data-design-name');
                    const designerName = this.getAttribute('data-design-designer');
                    const designerPic = this.getAttribute('data-design-designerPic');
                    const customerName = this.getAttribute('data-design-customer');
                    const customerPic = this.getAttribute('data-design-customerPic');
                    const designFile = this.getAttribute('data-design-file');

                    // Mengisi form dengan data dari tombol yang diklik
                    approveForm.action =
                        `/design/approve/${designId}`; // Set action form
                    approveForm.querySelector('img#designReference').src = `/${designPic}`;
                    approveForm.querySelector('img#designerPic').src = `/${designerPic}`;
                    approveForm.querySelector('img#customerPic').src = `/${customerPic}`;
                    approveForm.querySelector('#designName').textContent = designName;
                    approveForm.querySelector('#designer').textContent = designerName;
                    approveForm.querySelector('#customer').textContent = customerName;
                    approveForm.querySelector('input[name="designId"]').value = designId;
                    approveForm.querySelector('input[name="designRequestId"]').value =
                        designRequestId;
                    approveForm.querySelector('#customer').textContent = customerName;
                    approveForm.querySelector('#downloadDesign').setAttribute('href', designFile);

                    // Tampilkan modal
                    approveModal.classList.remove('hidden');
                    approveModal.classList.add('flex');
                    console.log(referenceImage); // Debugging
                });
            });

            // Tutup modal saat tombol "Close" diklik
            closeApproveModal.addEventListener('click', function() {
                approveModal.classList.add('hidden');
                approveModal.classList.remove('flex');
            });

            // Tutup modal saat area di luar modal diklik
            approveModal.addEventListener('click', function(e) {
                if (e.target === approveModal) {
                    approveModal.classList.add('hidden');
                    approveModal.classList.remove('flex');
                }
            });
        });
    </script>


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
</x-app-layout>
