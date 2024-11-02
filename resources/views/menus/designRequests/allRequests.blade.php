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

        #addRequestsModal {
            z-index: 101;
        }

        #addRequestsModal.show {
            display: flex
        }

        #approveModal {
            z-index: 101;
        }

        #approveModal.show {
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
                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Design Requests</span>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 9 4-4-4-4" />
                    </svg>
                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">All Requests</span>
                </div>
            </li>
        </ol>
    </nav>
    <div class="flex flex-col ml-8">
        <div class="flex">
            <button id="work" class="ml-1.5 mr-4 text-blue-500 font-semibold">
                @if (Auth()->User()->hasRole('admin'))
                    Ongoing Requests
                @else
                    My Requests
                @endif
            </button>
            <button id="done" class="mr-4">
                @if (Auth()->User()->hasRole('admin'))
                    Accomplished Requests
                @else
                    Requests History
                @endif
            </button>
        </div>
        <!-- Tambahkan hr setelah button pertama -->
        <hr id="underline" class="ml-1 w-0 border-blue-500 mb-3" style="border-width: 1.5px; transition: 0.5s ease;">
    </div>
    <div id="uncompletedRequests" class="max-w-7xl mx-auto sm:px-6 lg:px-8 transition-all duration-500 opacity-100">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

            <div class="flex justify-between items-center mb-4">
                <div class="flex">
                    <h3 class="text-lg font-medium pt-1 mr-3">
                        @if (Auth()->User()->hasRole('admin'))
                            Ongoing Requests
                        @else
                            My Requests
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

                <!-- Search Bar -->
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
                        Add Request
                    </button>
                </div>
            </div>

            @if ($uncompletedDesignRequestHeaders->isEmpty())
                <p>No design request found.</p>
            @else
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 pl-10 text-left">NO</th>
                            <th class="py-3 px-6 text-left">Customer</th>
                            <th class="py-3 px-6 text-left">Status</th>
                            <th class="py-3 px-6 text-left">Timestamp</th>
                            <th class="py-3 px-6 text-center text-sm font-bold opacity-60">Actions</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($uncompletedDesignRequestHeaders as $header)
                            <tr class="border-b border-gray-200 hover:bg-gray-100" data-header>
                                <td class="py-3 pl-6 text-left">
                                    <i class="bx bx-chevron-right cursor-pointer"></i>
                                    <i class="bx bx-archive mr-3 opacity-50"></i>
                                    {{ $loop->iteration }}.
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <div class="flex items-center">
                                        <img src="{{ $header->customer->profile_picture ? asset($header->customer->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($header->customer->name) . '&background=ccc&color=fff' }}"
                                            alt="Profile Picture" class="object-cover w-10 h-10 rounded-full">
                                        <div class="ml-3">
                                            <span
                                                class="block font-semibold text-gray-800">{{ $header->customer->name }}</span>
                                            <span
                                                class="block text-xs text-gray-500">{{ $header->customer->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-left">{{ $header->status }}</td>
                                <td class="py-3 px-6 text-left">{{ $header->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center">
                                        <a href="#" data-requestHeaderId="{{ $header->id }}"
                                            class="addRequests-button w-4 mr-2 scale-125 opacity-75 transform hover:text-green-500 hover:scale-150 transition duration-75">
                                            <i class="bx bx-plus-circle"></i>
                                        </a>
                                        <a href="#" data-headerId="{{ $header->id }}"
                                            data-customerPic="{{ $header->customer->profile_picture }}"
                                            data-customerName="{{ $header->customer->name }}"
                                            data-customerEmail="{{ $header->customer->email }}"
                                            data-requestedDate="{{ $header->created_at->format('d M, Y') }}"
                                            data-designNames="{{ implode(', ', $header->designRequests->pluck('name')->toArray()) }}"
                                            class="cancelAllRequests-button w-4 mr-2 scale-125 opacity-75 transform hover:text-red-500 hover:scale-150 transition duration-75">
                                            <i class="bx bx-x-circle"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr style="display: none;">
                                <td colspan="5">
                                    <table class="nested-table w-full mt-2 mx-6 ">
                                        <thead>
                                            <tr class="nested-header2">
                                                <th class="py-3 px-6 text-left text-sm font-bold opacity-60">
                                                    No
                                                </th>
                                                <th class="py-3 px-6 text-left text-sm font-bold opacity-60">
                                                    Reference</th>
                                                <th class="py-3 px-6 text-left text-sm font-bold opacity-60">
                                                    Name</th>
                                                <th class="py-3 px-6 text-left text-sm font-bold opacity-60">
                                                    Designer</th>
                                                <th class="py-3 px-6 text-left text-sm font-bold opacity-60">
                                                    Total
                                                    Pieces</th>
                                                <th class="py-3 px-6 text-left text-sm font-bold opacity-60">
                                                    Status
                                                </th>
                                                <th class="py-3 px-6 text-center text-sm font-bold opacity-60">
                                                    Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($header->designRequests as $request)
                                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                                    <td class="py-3 pl-6 text-left text-gray-600 text-sm">
                                                        <ol>
                                                            <li>{{ chr(97 + $loop->index) }}.</li>
                                                        </ol>
                                                    </td>
                                                    <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                        <img src="{{ asset($request->reference_image) }}"
                                                            class="w-20 h-auto" alt="Reference Image">
                                                    </td>
                                                    <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                        {{ $request->name }}
                                                    </td>
                                                    <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                        {{ $request->assignedDesigner ? $request->assignedDesigner->name : 'N/A' }}
                                                    </td>
                                                    <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                        {{ $request->total_pieces }}</td>
                                                    <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                        {{ $request->status }}</td>
                                                    <td class="py-3 px-6 text-center">
                                                        <div class="flex item-center justify-center">
                                                            @if ($request->status == 'pending' && Auth()->user()->hasrole('admin'))
                                                                <a href="#"
                                                                    class="approve-button w-4 mr-2 scale-125 transform hover:text-teal-500 hover:scale-150 transition duration-75"
                                                                    data-designRequest-id="{{ $request->id }}"
                                                                    data-designRequest-customer="{{ $header->customer->name }}"
                                                                    data-designRequest-Size="{{ $request->size }}"
                                                                    data-designRequest-customer="{{ $header->customer->name }}"
                                                                    data-designRequest-referenceImage="{{ $request->reference_image }}"
                                                                    data-requestHeader-name="{{ $request->name }}"
                                                                    data-requestHeader-totalPieces="{{ $request->total_pieces }}"
                                                                    data-requestHeader-description="{{ $request->description }}">
                                                                    <i class="bx bx-check-circle"></i>
                                                                </a>
                                                            @elseif (Auth()->user()->hasRole('admin'))
                                                                <a class="approved-button w-4 mr-2 scale-125 transform hover:text-teal-500 hover:scale-150 transition duration-75"
                                                                    data-id="{{ $request->id }}">
                                                                    <i class="bx bx-check-circle"></i>
                                                                </a>
                                                            @endif
                                                            <a href="#"
                                                                data-requestPic="{{ $request->reference_image }}"
                                                                data-requestCustomer="{{ $request->designRequestHeader->customer->name }}"
                                                                data-requestName="{{ $request->name }}"
                                                                data-requestPiece="{{ $request->total_pieces }}"
                                                                @php if (isset($request->size) && strpos($request->size, 'x') !== false) {
                                                                    [$width, $height] = explode('x', $request->size);
                                                                } else {
                                                                    $width = $height = null;
                                                                } @endphp
                                                                data-requestSizeW="{{ $width }}"
                                                                data-requestSizeH="{{ $height }}"
                                                                data-requestDescription="{{ $request->description }}"
                                                                class="view-button w-4 mr-2 scale-125 transform hover:text-green-500 hover:scale-150 transition duration-75">
                                                                <i class="bx bx-show"></i>
                                                            </a>
                                                            {{-- @if ($request->status == 'pending')
                                                            <a href="#" data-id="{{ $request->id }}"
                                                                        class="updateBlock-button w-4 mr-2 scale-125 transform hover:text-indigo-500 hover:scale-150 transition duration-75">
                                                                        <i class="bx bx-edit"></i></a>
                                                                @else
                                                                    <a href="#"
                                                                        class="update-button w-4 mr-2 scale-125 transform hover:text-indigo-500 hover:scale-150 transition duration-75"
                                                                        data-designRequest-id="{{ $request->id }}"
                                                                        data-designRequest-customer="{{ $header->customer->name }}"
                                                                        data-designRequest-Size="{{ $request->size }}"
                                                                        data-designRequest-customer="{{ $header->customer->name }}"
                                                                        data-designRequest-referenceImage="{{ $request->reference_image }}"
                                                                        data-requestHeader-name="{{ $request->name }}"
                                                                        data-requestHeader-totalPieces="{{ $request->total_pieces }}"
                                                                        data-requestHeader-colors="{{ $request->color }}"
                                                                        data-requestHeader-description="{{ $request->description }}"
                                                                        data-requestHeader-designerId="{{ $request->assigned_designer_id }}"
                                                                        data-requestHeader-designerName="{{ $request->assignedDesigner ? $request->assignedDesigner->name : 'N/A' }}"
                                                                        data-requestHeader-payPerPiece="{{ (int) $request->price_per_piece }}"
                                                                        data-requestHeader-payDesigner="{{ $request->payrollJob ? (int) $request->payrollJob->pay_designer : 0 }}"
                                                                        data-requestHeader-payMachineOps="{{ $request->payrollJob ? (int) $request->payrollJob->pay_machine_operator : 0 }}"
                                                                        data-requestHeader-payQcOps="{{ $request->payrollJob ? (int) $request->payrollJob->pay_qc : 0 }}">
                                                                        <i class="bx bx-edit"></i>
                                                                    </a>
                                                                    @endif --}}

                                                            @if (auth()->user()->hasRole('admin'))
                                                                <a href="#" data-id="{{ $request->id }}"
                                                                    class="cancel-button w-4 mr-2 scale-125 transform hover:text-red-500 hover:scale-150 transition duration-75">
                                                                    <i class="bx bx-trash"></i>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!-- Modal Approval -->
                                                <div id="approvalModal-{{ $request->id }}"
                                                    class="fixed inset-0 items-center justify-center pointer-events-none z-50 hidden">
                                                    <div id="approvalText-{{ $request->id }}"
                                                        class="bg-white p-4 rounded-lg shadow-lg text-center max-w-sm w-full opacity-0 transform translate-y-4 transition-all duration-500 ease-out">
                                                        <p class="text-gray-700">The request for
                                                            <br><b>{{ $request->name }}</b><br> has been
                                                            approved
                                                        </p>
                                                    </div>
                                                </div>
                                                <!-- Modal updateBlock -->
                                                <div id="updateBlockModal-{{ $request->id }}"
                                                    class="fixed inset-0 items-center justify-center pointer-events-none z-50 hidden">
                                                    <div id="updateBlockText-{{ $request->id }}"
                                                        class="bg-white p-4 rounded-lg shadow-lg text-center max-w-sm w-full opacity-0 transform translate-y-4 transition-all duration-500 ease-out">
                                                        <p class="text-gray-700">The request for
                                                            <br><b>{{ $request->name }}</b><br> has not been approved
                                                            yet.
                                                        </p>
                                                    </div>
                                                </div>
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
    <div id="completedRequests"
        class="max-w-7xl mx-auto sm:px-6 lg:px-8 transition-all transform ease-in-out duration-500 opacity-0 hidden">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

            <div class="flex justify-between items-center mb-4">
                <div class="flex">
                    <h3 class="text-lg font-medium pt-1 mr-3">
                        @if (Auth()->User()->hasRole('admin'))
                            Completed Requests
                        @else
                            My Requests
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

                <!-- Search Bar -->
                <div class="relative inline-block h-12 w-12 ml-4">
                    <input
                        class="-mr-3 search expandright absolute right-[49px] rounded bg-white border-none h-8 w-0 focus:w-[240px] transition-all duration-400 outline-none z-10 focus:px-4"
                        id="searchright" type="text" name="q" placeholder="Search">
                    <label class="z-20 button searchbutton absolute text-[22px] w-full cursor-pointer"
                        for="searchright">
                        <span class="-ml-3 inline-block">
                            <i class="bx bx-search"></i>
                        </span>
                    </label>
                </div>
            </div>

            @if ($completedDesignRequestHeaders->isEmpty())
                <p>No design request found.</p>
            @else
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-11 text-left">NO</th>
                            <th class="py-3 px-6 text-left">Customer</th>
                            {{-- <th class="py-3 px-6 text-left">Supervisor</th> --}}
                            <th class="py-3 px-6 text-left">Status</th>
                            <th class="py-3 px-6 text-left">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($completedDesignRequestHeaders as $header)
                            <tr class="border-b border-gray-200 hover:bg-gray-100" data-header2>
                                <td class="py-3 px-6 text-left">
                                    <i class="bx bx-chevron-right cursor-pointer"></i>
                                    <!-- Tombol chevron di sini -->
                                    <i class="bx bx-archive mr-3 opacity-50"></i>
                                    {{ $loop->iteration }}.
                                </td>
                                <td class="py-3 px-6 text-left">{{ $header->customer->name }}</td>
                                {{-- <td class="py-3 px-6 text-left">
                                        {{ $header->supervisor ? $header->supervisor->name : 'N/A' }}
                                    </td> --}}
                                <td class="py-3 px-6 text-left">{{ $header->status }}</td>
                                <td class="py-3 px-6 text-left">
                                    {{ $header->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                            <tr style="display: none;">
                                <td colspan="5">
                                    <table class="nested-table w-full mt-2 mx-6 ">
                                        <thead>
                                            <tr class="nested-header">
                                                <th class="py-3 px-6 text-left text-sm font-bold opacity-60">
                                                    No
                                                </th>
                                                <th class="py-3 px-6 text-left text-sm font-bold opacity-60">
                                                    Reference</th>
                                                <th class="py-3 px-6 text-left text-sm font-bold opacity-60">
                                                    Name</th>
                                                <th class="py-3 px-6 text-left text-sm font-bold opacity-60">
                                                    Designer</th>
                                                <th class="py-3 px-6 text-left text-sm font-bold opacity-60">
                                                    Total
                                                    Pieces</th>
                                                <th class="py-3 px-6 text-left text-sm font-bold opacity-60">
                                                    Status
                                                </th>
                                                <th class="py-3 px-6 text-center text-sm font-bold opacity-60">
                                                    Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($header->designRequests as $request)
                                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                                    <td class="py-3 pl-6 text-left text-gray-600 text-sm">
                                                        <ol>
                                                            <li>{{ chr(97 + $loop->index) }}.</li>
                                                        </ol>
                                                    </td>
                                                    <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                        <img src="{{ asset($request->reference_image) }}"
                                                            class="w-20 h-auto" alt="Reference Image">
                                                    </td>
                                                    <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                        {{ $request->name }}
                                                    </td>
                                                    <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                        {{ $request->assignedDesigner ? $request->assignedDesigner->name : 'N/A' }}
                                                    </td>
                                                    <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                        {{ $request->total_pieces }}</td>
                                                    <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                        {{ $request->status }}</td>
                                                    <td class="py-3 px-6 text-center">
                                                        <div class="flex item-center justify-center">
                                                            <a href="#"
                                                            data-requestPic="{{ $request->reference_image }}"
                                                                data-requestCustomer="{{ $request->designRequestHeader->customer->name }}"
                                                                data-requestName="{{ $request->name }}"
                                                                data-requestPiece="{{ $request->total_pieces }}"
                                                                @php if (isset($request->size) && strpos($request->size, 'x') !== false) {
                                                                    [$width, $height] = explode('x', $request->size);
                                                                } else {
                                                                    $width = $height = null;
                                                                } @endphp
                                                                data-requestSizeW="{{ $width }}"
                                                                data-requestSizeH="{{ $height }}"
                                                                data-requestDescription="{{ $request->description }}"
                                                                class="view-button w-4 mr-2 scale-125 transform hover:text-green-500 hover:scale-150 transition duration-75">
                                                                <i class="bx bx-show"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!-- Modal Approval -->
                                                <div id="approvalModal-{{ $request->id }}"
                                                    class="fixed inset-0 items-center justify-center pointer-events-none z-50 hidden">
                                                    <div id="approvalText-{{ $request->id }}"
                                                        class="bg-white p-4 rounded-lg shadow-lg text-center max-w-sm w-full opacity-0 transform translate-y-4 transition-all duration-500 ease-out">
                                                        <p class="text-gray-700">The request for
                                                            <br><b>{{ $request->name }}</b><br> has been
                                                            approved
                                                        </p>
                                                    </div>
                                                </div>
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

    <!-- Modal Add Requests -->
    <div id="addModal" class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-auto" style="height : 520px">
            <div class="flex items-center justify-between">
                <i id="closeAddModal"
                    class="bx bx-arrow-back scale-125 font-extrabold mb-4 cursor-pointer hover:scale-150"></i>
                <h2 class="text-xl font-bold mb-4 pr-4 mx-auto underline">Add New Requests</h2>
            </div>
            <form id="addUserForm" action="{{ route('designRequest.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="request-container overflow-y-scroll" style="max-height: 325px">
                    <div class="flex p-3" id="request-1">
                        <div class="flex justify-center mt-3">
                            <input type="file" name="referenceImage[0]" id="userProfileImageInput-1"
                                accept="image/*" class="hidden" onchange="previewImage(event, 'userProfileImage-1')">
                            <label for="userProfileImageInput-1" class="cursor-pointer">
                                <img id="userProfileImage-1" src="{{ asset('assets/images/placeHolder.png') }}"
                                    alt="User Profile" class="w-48 h-48 rounded-xl object-cover">
                            </label>
                        </div>
                        <div class="p-3">
                            <!-- Name -->
                            <div>
                                <label for="name-1" class="text-gray-600 font-light text-sm">Name</label>
                                <input type="text" id="name-1" name="name[0]" placeholder="Enter Name"
                                    class="w-full border rounded p-2 focus:outline-none focus:border-blue-500"
                                    required>
                            </div>

                            <!-- Total Pieces -->
                            <div>
                                <label for="total_pieces-1" class="text-gray-600 font-light text-sm">Total
                                    Pieces</label>
                                <input type="number" id="total_pieces-1" min="1" name="total_pieces[0]"
                                    placeholder="Enter Total Pieces"
                                    class="w-full border rounded p-2 focus:outline-none focus:border-blue-500"
                                    required>
                            </div>

                            <!-- Size Input (Width and Height) -->
                            <div class="bg-white rounded-lg shadow-md w-full max-w-lg">
                                <div class="mb-4">
                                    <label for="sizeWH-1" class="block text-gray-600 font-light text-sm mt-1">Size
                                        (CM)</label>
                                    <div class="flex space-x-2">
                                        <div class="flex items-center w-full">
                                            <input type="number" id="sizeW-1" min="1" name="sizeW[0]"
                                                placeholder="Width"
                                                class="w-full border rounded p-2 focus:outline-none focus:border-blue-500"
                                                required>
                                            <span class="mx-2 text-gray-600">X</span>
                                            <input type="number" id="sizeH-1" min="1" name="sizeH[0]"
                                                placeholder="Height"
                                                class="w-full border rounded p-2 focus:outline-none focus:border-blue-500"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Color Input -->
                                <div class="mb-4">
                                    <input type="hidden" name="colors[0]" id="hidden-color-input-1">
                                    <label for="color-1" class="block text-gray-600 font-light text-sm mb-2">Colors
                                        (Max 12)</label>
                                    <div class="content">
                                        <ul id="tag-list-1"
                                            class="flex flex-wrap p-2 border border-gray-300 rounded-lg space-y-2">
                                            <!-- Place for tags -->
                                            <li class="flex w-full items-center input-li">
                                                <input type="text" id="color-input-1" placeholder="Enter color"
                                                    class="flex-1 border rounded p-2 focus:outline-none focus:border-blue-500 text-sm">
                                                <button id="add-tag-btn-1"
                                                    class="ml-2 p-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none">
                                                    Enter
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1" id="tag-count-1">12 colors remaining</p>
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description-1"
                                    class="text-gray-600 font-light text-sm">Description</label>
                                <textarea id="description-1" name="description[0]" placeholder="Enter Description" rows="4"
                                    class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required></textarea>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="flex justify-end mr-3">
                    <p class="text-blue-600 ml-3 cursor-pointer" id="addRequest">+ Add Request</p>
                </div>
                <hr>
                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="bg-blue-500 text-white font-bold py-2 px-6 rounded hover:bg-blue-600 transition duration-200">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Insert Requests -->
    <div id="addRequestsModal" class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-auto" style="height : 520px">
            <div class="flex items-center justify-between">
                <i id="closeInsertModal"
                    class="bx bx-arrow-back scale-125 font-extrabold mb-4 cursor-pointer hover:scale-150"></i>
                <h2 class="text-xl font-bold mb-4 pr-4 mx-auto underline">Insert New Requests</h2>
            </div>
            <form id="insertRequestsForm" action="{{ route('designRequest.insert') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="headerId">
                <div class="request-container2 overflow-y-scroll" style="max-height: 325px">
                    <div class="flex p-3" id="request-1">
                        <div class="flex justify-center mt-3">
                            <input type="file" name="referenceImage[0]" id="userProfileImageInput2-1"
                                accept="image/*" class="hidden"
                                onchange="previewImage2(event, 'userProfileImage2-1')">
                            <label for="userProfileImageInput2-1" class="cursor-pointer">
                                <img id="userProfileImage2-1" src="{{ asset('assets/images/placeHolder.png') }}"
                                    alt="User Profile" class="w-48 h-48 rounded-xl object-cover">
                            </label>
                        </div>
                        <div class="p-3">
                            <!-- Name -->
                            <div>
                                <label for="name-1" class="text-gray-600 font-light text-sm">Name</label>
                                <input type="text" id="name-1" name="name[0]" placeholder="Enter Name"
                                    class="w-full border rounded p-2 focus:outline-none focus:border-blue-500"
                                    required>
                            </div>

                            <!-- Total Pieces -->
                            <div>
                                <label for="total_pieces-1" class="text-gray-600 font-light text-sm">Total
                                    Pieces</label>
                                <input type="number" id="total_pieces-1" min="1" name="total_pieces[0]"
                                    placeholder="Enter Total Pieces"
                                    class="w-full border rounded p-2 focus:outline-none focus:border-blue-500"
                                    required>
                            </div>

                            <!-- Size Input (Width and Height) -->
                            <div class="bg-white rounded-lg shadow-md w-full max-w-lg">
                                <div class="mb-4">
                                    <label for="sizeWH-1" class="block text-gray-600 font-light text-sm mt-1">Size
                                        (CM)</label>
                                    <div class="flex space-x-2">
                                        <div class="flex items-center w-full">
                                            <input type="number" min="1" id="sizeW-1" name="sizeW[0]"
                                                placeholder="Width"
                                                class="w-full border rounded p-2 focus:outline-none focus:border-blue-500"
                                                required>
                                            <span class="mx-2 text-gray-600">X</span>
                                            <input type="number" min="1" id="sizeH-1" name="sizeH[0]"
                                                placeholder="Height"
                                                class="w-full border rounded p-2 focus:outline-none focus:border-blue-500"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Color Input -->
                                <div class="mb-4">
                                    <input type="hidden" name="colors[0]" id="hidden-color-input2-1">
                                    <label for="color2-1" class="block text-gray-600 font-light text-sm mb-2">Colors
                                        (Max 12)</label>
                                    <div class="content">
                                        <ul id="tag-list2-1"
                                            class="flex flex-wrap p-2 border border-gray-300 rounded-lg space-y-2">
                                            <!-- Place for tags -->
                                            <li class="flex w-full items-center input-li">
                                                <input type="text" id="color-input2-1" placeholder="Enter color"
                                                    class="flex-1 border rounded p-2 focus:outline-none focus:border-blue-500 text-sm">
                                                <button id="add-tag-btn2-1"
                                                    class="ml-2 p-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none">
                                                    Enter
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1" id="tag-count2-1">12 colors remaining</p>
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description-1"
                                    class="text-gray-600 font-light text-sm">Description</label>
                                <textarea id="description-1" name="description[0]" placeholder="Enter Description" rows="4"
                                    class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required></textarea>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="flex justify-end mr-3">
                    <p class="text-blue-600 ml-3 cursor-pointer" id="addRequest2">+ Add Request</p>
                </div>
                <hr>
                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="bg-blue-500 text-white font-bold py-2 px-6 rounded hover:bg-blue-600 transition duration-200">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Update User -->
    <div id="approveModal" class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-4xl w-full" style="height: 600px;">
            <div class="flex items-center justify-between">
                <i id="closeApproveModal"
                    class="bx bx-arrow-back scale-125 font-extrabold mb-4 cursor-pointer hover:scale-150"></i>
                <h2 class="text-xl font-bold mb-4 pr-4 mx-auto underline">Approve Request</h2>
            </div>
            <form id="approveForm" action="{{ route('designRequest.approve', $request->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="referenceImage">
                <div class="request-container overflow-y-scroll" style="max-height: 420px">
                    <div class="flex p-3" id="request-1">
                        <div class="flex justify-center mt-3 mx-2">
                            <input type="file" name="referenceImage" id="userProfileImageInput-1"
                                accept="image/*" class="hidden" onchange="previewImage(event, 'userProfileImage-1')">
                            <label for="userProfileImageInput-1" class="cursor-pointer">
                                <img id="userProfileImage-1" alt="User Profile"
                                    class="w-48 h-48 rounded-xl object-contain">
                            </label>
                        </div>
                        <div class="p-3">
                            <!-- Customer -->
                            <div class="w-full">
                                <label for="customer" class="text-gray-600 font-light text-sm">Customer</label>
                                <input type="text" name="customer" placeholder="Enter Name" readonly
                                    class="w-full border rounded text-gray-600 p-2 bg-slate-200" required>
                            </div>

                            <!-- Name and Total Pieces -->
                            <div class="flex space-x-4">
                                <div class="w-1/2">
                                    <label for="name-1" class="text-gray-600 font-light text-sm">Name</label>
                                    <input type="text" name="name" placeholder="Enter Name"
                                        class="w-full border rounded text-gray-600 p-2 bg-slate-200" readonly>
                                </div>

                                <div class="w-1/2">
                                    <label for="total_pieces-1" class="text-gray-600 font-light text-sm">Total
                                        Pieces</label>
                                    <input type="number" name="total_pieces" placeholder="Enter Total Pieces"
                                        class="w-full border rounded text-gray-600 p-2 bg-slate-200" readonly>
                                </div>
                            </div>

                            <!-- Size -->
                            <div>
                                <label for="sizeWH" class="block text-gray-600 font-light text-sm mt-1">Size
                                    (CM)</label>
                                <div class="flex space-x-2">
                                    <div class="flex items-center w-full">
                                        <input type="text" id="sizeW" name="sizeW" placeholder="Width"
                                            class="w-full border rounded text-gray-600 p-2 bg-slate-200" readonly>
                                        {{-- <span class="mx-2 text-gray-600">X</span> --}}
                                        <i class="bx bx-x mx-1"></i>
                                        <input type="text" id="sizeH" name="sizeH" placeholder="Height"
                                            class="w-full border rounded text-gray-600 p-2 bg-slate-200" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mt-4">
                                <label for="description-1"
                                    class="text-gray-600 font-light text-sm">Description</label>
                                <textarea name="description" placeholder="Enter Description" rows="4"
                                    class="w-full border rounded text-gray-600 p-2 bg-slate-200" readonly></textarea>
                            </div>

                            <!-- designer -->
                            <div class="w-full">
                                <label for="designer" class="text-gray-600 font-light text-sm">designer</label>
                                {{-- <input list="designer-options" name="designer" placeholder="Enter Name"
                                    class="w-full border rounded text-gray-600 p-2 bg-slate-200" required>
                                <datalist id="designer-options">
                                    @foreach ($designers as $designer)
                                        <option value="{{ $designer->id }}">{{ $designer->name }}</option>
                                    @endforeach
                                </datalist> --}}
                                <select name="designer" id="designer"
                                    class="w-full border rounded text-gray-600 p-2 bg-slate-200" required>
                                    <option disabled selected>Choose Designer</option>
                                    @foreach ($designers as $designer)
                                        <option value="{{ $designer->id }}">{{ $designer->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Pay fields -->
                            <div class="flex space-x-4 mt-4">
                                <div class="w-1/2">
                                    <label for="pay_per_piece-1" class="text-gray-600 font-light text-sm">Pay per
                                        Piece</label>
                                    <input type="number" name="pay_per_piece" placeholder="Enter Pay per Piece"
                                        class="w-full border rounded p-2 focus:outline-none focus:border-blue-500"
                                        required>
                                </div>

                                <div class="w-1/2">
                                    <label for="pay_designer-1" class="text-gray-600 font-light text-sm">Pay
                                        Designer</label>
                                    <input type="number" name="pay_designer" placeholder="Enter Pay Designer"
                                        class="w-full border rounded p-2 focus:outline-none focus:border-blue-500"
                                        required>
                                </div>
                            </div>

                            <!-- Pay per Operator and Pay per QC (in one row) -->
                            <div class="flex space-x-4 mt-4">
                                <!-- Pay per Operator -->
                                <div class="w-1/2">
                                    <label for="pay_per_operator-1" class="text-gray-600 font-light text-sm">Pay
                                        per
                                        Operator</label>
                                    <input type="number" name="pay_per_operator"
                                        placeholder="Enter Pay per Operator"
                                        class="w-full border rounded p-2 focus:outline-none focus:border-blue-500"
                                        required>
                                </div>

                                <!-- Pay per QC -->
                                <div class="w-1/2">
                                    <label for="pay_per_qc-1" class="text-gray-600 font-light text-sm">Pay per
                                        QC</label>
                                    <input type="number" name="pay_per_qc" placeholder="Enter Pay per QC"
                                        class="w-full border rounded p-2 focus:outline-none focus:border-blue-500"
                                        required>
                                </div>
                            </div>
                            <!-- Add other pay fields as needed... -->
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="bg-blue-500 text-white font-bold py-2 px-6 rounded hover:bg-blue-600 transition duration-200">Approve</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Update Request -->
    <div id="updateRequestModal" class="fixed inset-0 items-center z-50 justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-4xl w-full" style="height: 600px;">
            <div class="flex items-center justify-between">
                <i id="closeUpdateRequestModal"
                    class="bx bx-arrow-back scale-125 font-extrabold mb-4 cursor-pointer hover:scale-150"></i>
                <h2 class="text-xl font-bold mb-4 pr-4 mx-auto underline">Update Request</h2>
            </div>
            <form id="updateRequestForm" action="{{ route('designRequest.update') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="designRequestId">
                <div class="request-container overflow-y-scroll" style="max-height: 420px">
                    <div class="flex p-3" id="request-1">
                        <div class="flex justify-center mt-3 mx-2">
                            <input type="file" name="referenceImage" id="referenceImageInputUpdate"
                                accept="image/*" class="hidden" onchange="prevImage(event, 'referenceImageUpdate')">
                            <label for="referenceImageInputUpdate" class="cursor-pointer">
                                <img id="referenceImageUpdate" alt="User Profile"
                                    class="w-48 h-48 rounded-xl object-contain">
                            </label>
                        </div>
                        <div class="p-3">
                            <!-- Customer -->
                            <div class="w-full">
                                <label for="customer" class="text-gray-600 font-light text-sm">Customer</label>
                                <input type="text" name="customer" placeholder="Enter Name" readonly
                                    class="w-full border rounded text-gray-600 p-2" required>
                            </div>

                            <!-- Name and Total Pieces -->
                            <div class="flex space-x-4">
                                <div class="w-1/2">
                                    <label for="name-1" class="text-gray-600 font-light text-sm">Name</label>
                                    <input type="text" name="name" placeholder="Enter Name"
                                        class="w-full border rounded text-gray-600 p-2">
                                </div>

                                <div class="w-1/2">
                                    <label for="total_pieces-1" class="text-gray-600 font-light text-sm">Total
                                        Pieces</label>
                                    <input type="number" name="total_pieces" placeholder="Enter Total Pieces"
                                        class="w-full border rounded text-gray-600 p-2">
                                </div>
                            </div>

                            <!-- Size -->
                            <div>
                                <label for="sizeWH" class="block text-gray-600 font-light text-sm mt-1">Size
                                    (CM)</label>
                                <div class="flex space-x-2">
                                    <div class="flex items-center w-full">
                                        <input type="text" id="sizeW" name="sizeW" placeholder="Width"
                                            class="w-full border rounded text-gray-600 p-2">
                                        {{-- <span class="mx-2 text-gray-600">X</span> --}}
                                        <i class="bx bx-x mx-1"></i>
                                        <input type="text" id="sizeH" name="sizeH" placeholder="Height"
                                            class="w-full border rounded text-gray-600 p-2">
                                    </div>
                                </div>
                            </div>

                            {{-- Color --}}
                            <div class="mb-4">
                                <input type="hidden" name="colors" id="hidden-color-input3">
                                <label for="color3" class="block text-gray-600 font-light text-sm mb-2">Colors
                                    (Max 12)</label>
                                <div class="content">
                                    <ul id="tag-list3"
                                        class="flex flex-wrap p-2 border border-gray-300 rounded-lg space-y-2">
                                        <li class="flex w-full items-center input-li">
                                            <input type="text" id="color-input3" placeholder="Enter color"
                                                class="flex-1 border rounded p-2 focus:outline-none focus:border-blue-500 text-sm">
                                            <button id="add-tag-btn3"
                                                class="ml-2 p-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none">
                                                Enter
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                <p class="text-sm text-gray-500 mt-1" id="tag-count3">12 colors remaining</p>
                            </div>

                            <!-- Description -->
                            <div class="mt-4">
                                <label for="description-1"
                                    class="text-gray-600 font-light text-sm">Description</label>
                                <textarea name="description" placeholder="Enter Description" rows="4"
                                    class="w-full border rounded text-gray-600 p-2"></textarea>
                            </div>

                            <!-- designer -->
                            <div class="w-full" id="designerInput">
                            </div>

                            <!-- Pay fields -->
                            <div class="flex space-x-4 mt-4">
                                <div class="w-1/2">
                                    <label for="pay_per_piece-1" class="text-gray-600 font-light text-sm">Pay per
                                        Piece</label>
                                    <input type="number" name="pay_per_piece" placeholder="Enter Pay per Piece"
                                        class="w-full border rounded p-2 focus:outline-none focus:border-blue-500"
                                        required>
                                </div>

                                <div class="w-1/2">
                                    <label for="pay_designer-1" class="text-gray-600 font-light text-sm">Pay
                                        Designer</label>
                                    <input type="number" name="pay_designer" placeholder="Enter Pay Designer"
                                        class="w-full border rounded p-2 focus:outline-none focus:border-blue-500"
                                        required>
                                </div>
                            </div>

                            <!-- Pay per Operator and Pay per QC (in one row) -->
                            <div class="flex space-x-4 mt-4">
                                <!-- Pay per Operator -->
                                <div class="w-1/2">
                                    <label for="pay_per_operator-1" class="text-gray-600 font-light text-sm">Pay
                                        per
                                        Operator</label>
                                    <input type="number" name="pay_per_operator"
                                        placeholder="Enter Pay per Operator"
                                        class="w-full border rounded p-2 focus:outline-none focus:border-blue-500"
                                        required>
                                </div>

                                <!-- Pay per QC -->
                                <div class="w-1/2">
                                    <label for="pay_per_qc-1" class="text-gray-600 font-light text-sm">Pay per
                                        QC</label>
                                    <input type="number" name="pay_per_qc" placeholder="Enter Pay per QC"
                                        class="w-full border rounded p-2 focus:outline-none focus:border-blue-500"
                                        required>
                                </div>
                            </div>
                            <!-- Add other pay fields as needed... -->
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="bg-blue-500 text-white font-bold py-2 px-6 rounded hover:bg-blue-600 transition duration-200">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal View Request -->
    <div id="requestModal" class="fixed inset-0 items-center justify-center z-50 bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-4xl w-full" style="height: auto">
            <div class="flex items-center justify-between">
                <i id="closeRequestModal"
                    class="bx bx-arrow-back scale-125 font-extrabold mb-4 cursor-pointer hover:scale-150"></i>
                <h2 class="text-xl font-bold mb-4 pr-4 mx-auto underline">View Request</h2>
            </div>
            <input type="hidden" name="referenceImage">
            <div class="request-container overflow-y-scroll" style="max-height: 420px">
                <div class="flex p-3" id="request-1">
                    <div class="flex justify-center mt-3 mx-2">
                        <img id="requestPic" alt="User Profile" class="w-48 h-48 rounded-xl object-contain">
                    </div>
                    <div class="p-3">
                        <!-- Customer -->
                        <div class="w-full">
                            <label for="customer" class="text-gray-600 font-light text-sm">Customer</label>
                            <input type="text" name="customer" placeholder="Enter Name" readonly
                                class="w-full border rounded text-gray-600 p-2" required>
                        </div>

                        <!-- Name and Total Pieces -->
                        <div class="flex space-x-4">
                            <div class="w-1/2">
                                <label for="name-1" class="text-gray-600 font-light text-sm">Name</label>
                                <input type="text" name="name" placeholder="Enter Name"
                                    class="w-full border rounded text-gray-600 p-2" readonly>
                            </div>

                            <div class="w-1/2">
                                <label for="total_pieces-1" class="text-gray-600 font-light text-sm">Total
                                    Pieces</label>
                                <input type="number" name="total_pieces" placeholder="Enter Total Pieces"
                                    class="w-full border rounded text-gray-600 p-2" readonly>
                            </div>
                        </div>

                        <!-- Size -->
                        <div>
                            <label for="sizeWH" class="block text-gray-600 font-light text-sm mt-1">Size
                                (CM)</label>
                            <div class="flex space-x-2">
                                <div class="flex items-center w-full">
                                    <input type="text" id="sizeW" name="sizeW" placeholder="Width"
                                        class="w-full border rounded text-gray-600 p-2" readonly>
                                    {{-- <span class="mx-2 text-gray-600">X</span> --}}
                                    <i class="bx bx-x mx-1"></i>
                                    <input type="text" id="sizeH" name="sizeH" placeholder="Height"
                                        class="w-full border rounded text-gray-600 p-2" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <label for="description-1" class="text-gray-600 font-light text-sm">Description</label>
                            <textarea name="description" placeholder="Enter Description" rows="4"
                                class="w-full border rounded text-gray-600 p-2" readonly></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cancel All Request -->
    <div id="cancelModal" class="fixed inset-0 items-center justify-center z-50 bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-4xl w-auto h-auto">
            <form id="cancelForm" action="{{ route('designRequest.cancelAll', $request->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="headerId">
                <h2>Cancel All Design Requests?</h2>
                <hr>
                <div class="flex p-3 gap-4 max-h-72 overflow-y-scroll" id="request-1">
                    <div class="flex flex-col">
                        <h2 class="font-semibold text-lg my-3 underline">Detail</h2>
                        <h2 class="mt-1 font-light text-base text-gray-600">Customer</h2>
                        <h2 class="mt-7 font-light text-base text-gray-600">Requested at</h2>
                        <h2 class="font-light text-base text-gray-600">Designs</h2>
                    </div>
                    <div class="mt-11">
                        <div class="flex items-center gap-4 mb-4">:
                            <img id="customerPic" alt="Profile Picture" class="object-cover w-10 h-10 rounded-full">
                            <div>
                                <span id="customerName"
                                    class="block font-semibold text-gray-800 text-lg">Jajang</span>
                                <span id="customerEmail"
                                    class="block text-sm font-light text-gray-500">jajaaang@gmail.com</span>
                            </div>
                        </div>
                        <h2 id="requestedDate">: 27 Oct, 2024</h2>
                        <div class="flex gap-1">:
                            <ol type="a" id="designLists" class="list-inside list-decimal text-gray-700">
                                <li id="designName">Bang</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <hr>
                <h2 class="mt-3">This Cannot be Undone</h2>
                <div class="flex justify-end gap-3">
                    <div class="flex justify-end mt-6">
                        <button type="button"
                            class="bg-gray-500 text-white font-bold py-2 px-6 rounded hover:bg-gray-600 transition duration-200">Back</button>
                    </div>
                    <div class="flex justify-end mt-6">
                        <button type="submit"
                            class="bg-red-500 text-white font-bold py-2 px-6 rounded hover:bg-red-600 transition duration-200">Confirm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Cancel Request --}}
    <div id="cancelRequestModal" class="fixed inset-0 items-center justify-center z-50 bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full" style="height: auto">
            <h2 class="text-lg font-semibold mb-4">Cancel Confirmation</h2>
            <p>Are you sure you want to cancel this request? This action cannot be undone.</p>
            <form id="cancelRequestForm" method="POST" action="{{ route('designRequest.cancel') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="requestId" id="designRequestId" value="">
                <div class="flex justify-end mt-4">
                    <button type="button" id="closeModalCancel"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2" onclick="closeModal()">Back</button>
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Confirm</button>
                </div>
            </form>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    {{-- Script Modal Cancel --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cancelButton = document.querySelectorAll('.cancel-button');
            const cancelRequestModal = document.getElementById('cancelRequestModal');
            const closeModalCancel = document.getElementById('closeModalCancel');
            const addUserForm = document.getElementById('addUserForm');

            // Menampilkan modal saat tombol "add User" diklik
            cancelButton.forEach(button => {
                button.addEventListener('click', function() {
                    const requestId = this.getAttribute('data-id');
                    cancelRequestModal.querySelector('input[name="requestId"').value = requestId;
                    cancelRequestModal.classList.remove('hidden');
                    cancelRequestModal.classList.add(
                        'flex');
                });
            });

            closeModalCancel.addEventListener('click', function() {
                cancelRequestModal.classList.add('hidden');
                cancelRequestModal.classList.remove('flex');
            });

            cancelRequestModal.addEventListener('click', function(e) {
                if (e.target === cancelRequestModal) {
                    cancelRequestModal.classList.add('hidden');
                    cancelRequestModal.classList.remove('flex');
                }
            });
        });
    </script>


    {{-- Script Open sub Table --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('tr[data-header]');
            const rows2 = document.querySelectorAll('tr[data-header2]');

            rows.forEach(row => {
                const arrowIcon = row.querySelector('.bx');
                const addRequests = row.querySelector('.addRequests-button');
                const cancelAllRequests = row.querySelector('.cancelAllRequests-button');


                row.addEventListener('click', function() {
                    const nestedTable = this
                        .nextElementSibling;

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
                addRequests.addEventListener('click', function(event) {
                    event.stopPropagation();
                });
                cancelAllRequests.addEventListener('click', function(event) {
                    event.stopPropagation();
                });
            });
            rows2.forEach(row => {
                const arrowIcon = row.querySelector('.bx');

                row.addEventListener('click', function() {
                    const nestedTable = row.nextElementSibling;

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



    {{-- Script Add Requests Loop --}}
    <script>
        let requestCount = 1;

        document.getElementById('addRequest').addEventListener('click', function() {
            requestCount++;

            const requestContainer = document.querySelector('.request-container');

            const newRequestDiv = document.createElement('div');
            newRequestDiv.classList.add('flex', 'p-3');
            newRequestDiv.innerHTML = `
        <div class="flex justify-center mt-3">
            <input type="file" name="referenceImage[]" id="userProfileImageInput-${requestCount}" accept="image/*" class="hidden"
                onchange="previewImage(event, 'userProfileImage-${requestCount}')">
            <label for="userProfileImageInput-${requestCount}" class="cursor-pointer">
                <img id="userProfileImage-${requestCount}" src="{{ asset('assets/images/placeHolder.png') }}"
                    alt="User Profile" class="w-48 h-48 rounded-xl object-cover">
            </label>
        </div>
        <div class="p-3">
            <!-- Name -->
            <div>
                <label for="name-${requestCount}" class="text-gray-600 font-light text-sm">Name</label>
                <input type="text" id="name-${requestCount}" name="name[]" placeholder="Enter Name"
                    class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required>
            </div>

            <!-- Total Pieces -->
            <div>
                <label for="total_pieces-${requestCount}" class="text-gray-600 font-light text-sm">Total Pieces</label>
                <input type="number" id="total_pieces-${requestCount}" name="total_pieces[]" placeholder="Enter Total Pieces"
                    class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required>
            </div>

            <!-- Size Input (Width and Height) -->
            <div class="bg-white rounded-lg shadow-md w-full max-w-lg">
                <div class="mb-4">
                    <label for="sizeWH-${requestCount}" class="block text-gray-600 font-light text-sm mt-1">Size (CM)</label>
                    <div class="flex space-x-2">
                        <div class="flex items-center w-full">
                            <input type="number" id="sizeW-${requestCount}" name="sizeW[]" placeholder="Width"
                                class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required>
                            <span class="mx-2 text-gray-600">X</span>
                            <input type="number" id="sizeH-${requestCount}" name="sizeH[]" placeholder="Height"
                                class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Color Input -->
            <div class="mb-4">
                <input type="hidden" name="colors[]" id="hidden-color-input-${requestCount}">
                <label for="color-${requestCount}" class="block text-gray-600 font-light text-sm mb-2">Colors (Max 12)</label>
                <div class="content">
                    <ul id="tag-list-${requestCount}"
                        class="flex flex-wrap p-2 border border-gray-300 rounded-lg space-y-2">
                        <!-- Place for tags -->
                        <li class="flex w-full items-center input-li">
                            <input type="text" id="color-input-${requestCount}" placeholder="Enter color"
                                class="flex-1 border rounded p-2 focus:outline-none focus:border-blue-500 text-sm">
                            <button id="add-tag-btn-${requestCount}"
                                class="ml-2 p-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none">
                                Enter
                            </button>
                        </li>
                    </ul>
                </div>
                <p class="text-sm text-gray-500 mt-1" id="tag-count-${requestCount}">12 colors remaining</p>
                </div>
                <!-- Description -->
                <div>
                    <label for="description-${requestCount}" class="text-gray-600 font-light text-sm">Description</label>
                    <textarea id="description-${requestCount}" name="description[]" placeholder="Enter Description" rows="4"
                    class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required></textarea>
                    </div>
                    </div>`;

            requestContainer.appendChild(newRequestDiv);
            const separator = document.createElement('hr');
            requestContainer.appendChild(separator);

            initColorInput1(requestCount);
        });

        function previewImage(event, imgId) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                const imgElement = document.getElementById(imgId);
                imgElement.src = e.target.result;
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        function initColorInput1(index) {
            const ul = document.querySelector(`#tag-list-${index}`),
                input = document.querySelector(`#color-input-${index}`),
                addTagBtn = document.querySelector(`#add-tag-btn-${index}`),
                hiddenInput = document.querySelector(`#hidden-color-input-${index}`),
                tagCountDisplay = document.querySelector(`#tag-count-${index}`);

            let maxTags = 12;
            let tags = [];

            function updateHiddenInput() {
                hiddenInput.value = tags.join(',');
            }

            function countTags() {
                tagCountDisplay.innerText = `${maxTags - tags.length} colors remaining`;
            }

            function createTag() {
                ul.querySelectorAll("li:not(.input-li)").forEach(li => li.remove());
                tags.slice().reverse().forEach(tag => {
                    const liTag = document.createElement("li");
                    liTag.className = "flex items-center bg-gray-200 rounded-lg px-3 py-1 m-1 text-sm";
                    liTag.innerHTML = `${tag} <button type="button" class="ml-2 text-red-500 font-bold">x</button>`;

                    liTag.querySelector("button").addEventListener("click", (e) => {
                        e.stopPropagation();
                        removeTag(tag);
                    });

                    ul.insertAdjacentElement("afterbegin", liTag);
                });

                updateHiddenInput();
                countTags();
            }

            function addTag() {
                const tag = input.value.trim().toLowerCase().replace(/\s+/g, ' ');
                if (tag.length > 0 && !tags.includes(tag)) {
                    if (tags.length < maxTags) {
                        tags.push(tag);
                        createTag();
                    } else {
                        alert("Maximum 12 colors allowed.");
                    }
                }
                input.value = "";
                input.focus();
            }

            function removeTag(tag) {
                tags = tags.filter(t => t !== tag);
                createTag();
            }

            input.addEventListener("keyup", (e) => {
                if (e.key === "Enter") {
                    e.preventDefault();
                    addTag();
                }
            });

            addTagBtn.addEventListener("click", (e) => {
                e.preventDefault();
                addTag();
            });
        }
        initColorInput1(1);
    </script>


    {{-- Script Insert Requests Loop --}}
    <script>
        let requestCount2 = 1;

        document.getElementById('addRequest2').addEventListener('click', function() {
            requestCount2++;

            const requestContainer = document.querySelector('.request-container2');

            // Create new form elements
            const newRequestDiv = document.createElement('div');
            newRequestDiv.classList.add('flex', 'p-3');
            newRequestDiv.innerHTML = `
            <div class="flex justify-center mt-3">
                <input type="file" name="referenceImage[]" id="userProfileImageInput2-${requestCount2}" accept="image/*" class="hidden"
                    onchange="previewImage2(event, 'userProfileImage2-${requestCount2}')">
                <label for="userProfileImageInput2-${requestCount2}" class="cursor-pointer">
                    <img id="userProfileImage2-${requestCount2}" src="{{ asset('assets/images/placeHolder.png') }}"
                        alt="User Profile" class="w-48 h-48 rounded-xl object-cover">
                </label>
            </div>
            <div class="p-3">
                <!-- Name -->
                <div>
                    <label for="name-${requestCount2}" class="text-gray-600 font-light text-sm">Name</label>
                    <input type="text" id="name-${requestCount2}" name="name[]" placeholder="Enter Name"
                        class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required>
                </div>

                <!-- Total Pieces -->
                <div>
                    <label for="total_pieces-${requestCount2}" class="text-gray-600 font-light text-sm">Total Pieces</label>
                    <input type="number" id="total_pieces-${requestCount2}" name="total_pieces[]" placeholder="Enter Total Pieces"
                        class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required>
                </div>

                <!-- Size Input (Width and Height) -->
                <div class="bg-white rounded-lg shadow-md w-full max-w-lg">
                    <div class="mb-4">
                        <label for="sizeWH-${requestCount2}" class="block text-gray-600 font-light text-sm mt-1">Size (CM)</label>
                        <div class="flex space-x-2">
                            <div class="flex items-center w-full">
                                <input type="number" id="sizeW-${requestCount2}" name="sizeW[]" placeholder="Width"
                                    class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required>
                                <span class="mx-2 text-gray-600">X</span>
                                <input type="number" id="sizeH-${requestCount2}" name="sizeH[]" placeholder="Height"
                                    class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Color Input -->
                <div class="mb-4">
                    <input type="hidden" name="colors[]" id="hidden-color-input2-${requestCount2}">
                    <label for="color2-${requestCount2}" class="block text-gray-600 font-light text-sm mb-2">Colors (Max 12)</label>
                    <div class="content">
                        <ul id="tag-list2-${requestCount2}"
                            class="flex flex-wrap p-2 border border-gray-300 rounded-lg space-y-2">
                            <!-- Place for tags -->
                            <li class="flex w-full items-center input-li">
                                <input type="text" id="color-input2-${requestCount2}" placeholder="Enter color"
                                    class="flex-1 border rounded p-2 focus:outline-none focus:border-blue-500 text-sm">
                                <button id="add-tag-btn2-${requestCount2}"
                                    class="ml-2 p-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none">
                                    Enter
                                </button>
                            </li>
                        </ul>
                    </div>
                    <p class="text-sm text-gray-500 mt-1" id="tag-count2-${requestCount2}">12 colors remaining</p>
                </div>

                <!-- Description -->
                <div>
                    <label for="description-${requestCount2}" class="text-gray-600 font-light text-sm">Description</label>
                    <textarea id="description-${requestCount2}" name="description[]" placeholder="Enter Description" rows="4"
                        class="w-full border rounded p-2 focus:outline-none focus:border-blue-500" required></textarea>
                </div>
            </div>
        `;

            requestContainer.appendChild(newRequestDiv);
            const separator = document.createElement('hr');
            requestContainer.appendChild(separator);

            initColorInput2(requestCount2);
        });

        function previewImage2(event, imgId) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                const imgElement = document.getElementById(imgId);
                imgElement.src = e.target.result;
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        function initColorInput2(index) {
            const ul = document.querySelector(`#tag-list2-${index}`);
            const input = document.querySelector(`#color-input2-${index}`);
            const addTagBtn = document.querySelector(`#add-tag-btn2-${index}`);
            const hiddenInput = document.querySelector(`#hidden-color-input2-${index}`);
            const tagCountDisplay = document.querySelector(`#tag-count2-${index}`);

            let maxTags = 12;
            let tags = [];

            function updateHiddenInput() {
                hiddenInput.value = tags.join(',');
            }

            function countTags() {
                tagCountDisplay.innerText = `${maxTags - tags.length} colors remaining`;
            }

            function createTag() {
                ul.querySelectorAll("li:not(.input-li)").forEach(li => li.remove());

                tags.slice().reverse().forEach(tag => {
                    const liTag = document.createElement("li");
                    liTag.className = "flex items-center bg-gray-200 rounded-lg px-3 py-1 m-1 text-sm";
                    liTag.innerHTML = `${tag} <button type="button" class="ml-2 text-red-500 font-bold">x</button>`;

                    liTag.querySelector("button").addEventListener("click", (e) => {
                        e.stopPropagation();
                        removeTag(tag);
                    });

                    ul.insertAdjacentElement("afterbegin", liTag);
                });

                updateHiddenInput();
                countTags();
            }

            function addTag() {
                const tag = input.value.trim().toLowerCase().replace(/\s+/g, ' ');
                if (tag.length > 0 && !tags.includes(tag)) {
                    if (tags.length < maxTags) {
                        tags.push(tag);
                        createTag();
                    } else {
                        alert("Maximum 12 colors allowed.");
                    }
                }
                input.value = "";
                input.focus();
            }

            function removeTag(tag) {
                tags = tags.filter(t => t !== tag);
                createTag();
            }

            input.addEventListener("keyup", (e) => {
                if (e.key === "Enter") {
                    e.preventDefault();
                    addTag();
                }
            });

            addTagBtn.addEventListener("click", (e) => {
                e.preventDefault();
                addTag();
            });
        }
        initColorInput2(1);
    </script>


    {{-- Script Modal Cancel All --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cancelAllRequestsButton = document.querySelectorAll('.cancelAllRequests-button');
            const cancelModal = document.getElementById('cancelModal');
            const closeApproveModal = document.getElementById('closeApproveModal');
            const cancelForm = document.getElementById('cancelForm');
            const designLists = document.getElementById('designLists');

            cancelAllRequestsButton.forEach(button => {
                button.addEventListener('click', function() {
                    cancelModal.classList.remove('hidden');
                    cancelModal.classList.add('flex');
                    const headerId = this.getAttribute('data-headerId');
                    const customerPic = this.getAttribute('data-customerPic');
                    const customerName = this.getAttribute('data-customerName');
                    const customerEmail = this.getAttribute('data-customerEmail');
                    const requestedDate = this.getAttribute('data-requestedDate');
                    const designNames = this.getAttribute('data-designNames');
                    cancelForm.querySelector('input[name="headerId"]').value = headerId;
                    cancelForm.querySelector('img#customerPic').src = `/${customerPic}`;
                    cancelForm.querySelector('#customerName').textContent = customerName;
                    cancelForm.querySelector('#customerEmail').textContent = customerEmail;
                    cancelForm.querySelector('#requestedDate').textContent = ': ' + requestedDate;


                    designLists.innerHTML = '';

                    fetch(`/design-requests/getDesigns/${headerId}`)
                        .then(response => {
                            if (!response.ok) throw new Error('Network response was not ok');
                            return response.json();
                        })
                        .then(data => {
                            console.log('Data received:',
                                data);

                            if (!Array.isArray(data) || data.length === 0) {
                                designLists.innerHTML = `<li>No design found.</li>`;
                                return;
                            }

                            designLists.innerHTML = data
                                .map(designRequest => {
                                    return designRequest ?
                                        `<li>${designRequest.name}</li>` : '';
                                })
                                .join('');
                        })
                        .catch(error => {
                            console.error('Error fetching design requests:', error);
                            alert('Failed to retrieve design requests.');
                        });


                });
            });
            closeApproveModal.addEventListener('click', function() {
                cancelModal.classList.add('hidden');
                cancelModal.classList.remove('flex');
            });

            cancelModal.addEventListener('click', function(e) {
                if (e.target === cancelModal) {
                    cancelModal.classList.add('hidden');
                    cancelModal.classList.remove('flex');
                }
            });
        });
    </script>

    {{-- Script Modal Approved --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const approveButtons = document.querySelectorAll('.approve-button');
            const approveModal = document.getElementById('approveModal');
            const closeApproveModal = document.getElementById('closeApproveModal');
            const approveForm = document.getElementById('approveForm');

            approveButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const designRequestId = this.getAttribute('data-designRequest-id');
                    const customer = this.getAttribute('data-designRequest-customer');
                    const referenceImage = this.getAttribute('data-designRequest-referenceImage');
                    const name = this.getAttribute('data-requestHeader-name');
                    const size = this.getAttribute('data-designRequest-size');
                    const [width, height] = size.split('X');
                    const totalPieces = this.getAttribute('data-requestHeader-totalPieces');
                    const description = this.getAttribute('data-requestHeader-description');

                    approveForm.action =
                        `/design-requests/approve/${designRequestId}`; // Set action form
                    approveForm.querySelector('input[name="customer"]').value = customer;
                    approveForm.querySelector('img#userProfileImage-1').src = `/${referenceImage}`;
                    approveForm.querySelector('input[name="name"]').value = name;
                    approveForm.querySelector('input[name="sizeW"]').value = "Width : " + width;
                    approveForm.querySelector('input[name="sizeH"]').value = "Height : " + height;
                    approveForm.querySelector('input[name="total_pieces"]').value = totalPieces;
                    approveForm.querySelector('textarea[name="description"]').value = description;

                    approveModal.classList.remove('hidden');
                    approveModal.classList.add('flex');
                    console.log(referenceImage); // Debugging
                });
            });

            closeApproveModal.addEventListener('click', function() {
                approveModal.classList.add('hidden');
                approveModal.classList.remove('flex');
            });

            approveModal.addEventListener('click', function(e) {
                if (e.target === approveModal) {
                    approveModal.classList.add('hidden');
                    approveModal.classList.remove('flex');
                }
            });
        });
    </script>

    {{-- Script Modal Edit Request --}}
    <script>
        function prevImage(event, imgId) {
            const file = event.target.files[0];
            if (!file) {
                console.warn("No file selected.");
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgElement = document.getElementById(imgId);
                imgElement.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        let tags = [];

        function initColorInput() {
            const ul = document.querySelector(`#tag-list3`);
            const input = document.querySelector(`#color-input3`);
            const addTagBtn = document.querySelector(`#add-tag-btn3`);
            const hiddenInput = document.querySelector(`#hidden-color-input3`);
            const tagCountDisplay = document.querySelector(`#tag-count3`);

            let maxTags = 12;

            function updateHiddenInput() {
                hiddenInput.value = tags.join(',');
            }

            function countTags() {
                tagCountDisplay.innerText = `${maxTags - tags.length} colors remaining`;
            }

            function createTag() {
                ul.querySelectorAll("li:not(.input-li)").forEach(li => li.remove());

                tags.slice().reverse().forEach(tag => {
                    const liTag = document.createElement("li");
                    liTag.className = "flex items-center bg-gray-200 rounded-lg px-3 py-1 m-1 text-sm";
                    liTag.innerHTML = `${tag} <button type="button" class="ml-2 text-red-500 font-bold">x</button>`;

                    liTag.querySelector("button").addEventListener("click", (e) => {
                        e.stopPropagation();
                        removeTag(tag);
                    });

                    ul.insertAdjacentElement("afterbegin", liTag);
                });

                updateHiddenInput();
                countTags();
            }

            function addTag() {
                const tag = input.value.trim().toLowerCase().replace(/\s+/g, ' ');
                if (tag.length > 0 && !tags.includes(tag)) {
                    if (tags.length < maxTags) {
                        tags.push(tag);
                        createTag();
                    } else {
                        alert("Maximum 12 colors allowed.");
                    }
                }
                input.value = "";
                input.focus();
            }

            function removeTag(tag) {
                tags = tags.filter(t => t !== tag);
                createTag();
            }

            input.addEventListener("keyup", (e) => {
                if (e.key === "Enter") {
                    e.preventDefault();
                    addTag();
                }
            });

            addTagBtn.addEventListener("click", (e) => {
                e.preventDefault();
                addTag();
            });
        }

        function autoColorInput(color) {
            const ul = document.querySelector(`#tag-list3`);
            const addTagBtn = document.querySelector(`#add-tag-btn3`);
            const hiddenInput = document.querySelector(`#hidden-color-input3`);
            const tagCountDisplay = document.querySelector(`#tag-count3`);

            let maxTags = 12;

            function updateHiddenInput() {
                hiddenInput.value = tags.join(',');
            }

            function countTags() {
                tagCountDisplay.innerText = `${maxTags - tags.length} colors remaining`;
            }

            function createTag() {
                ul.querySelectorAll("li:not(.input-li)").forEach(li => li.remove());

                tags.slice().reverse().forEach(tag => {
                    const liTag = document.createElement("li");
                    liTag.className = "flex items-center bg-gray-200 rounded-lg px-3 py-1 m-1 text-sm";
                    liTag.innerHTML = `${tag} <button type="button" class="ml-2 text-red-500 font-bold">x</button>`;

                    // Tambahkan event listener untuk tombol "x"
                    liTag.querySelector("button").addEventListener("click", (e) => {
                        e.stopPropagation();
                        removeTag(tag);
                    });

                    ul.insertAdjacentElement("afterbegin", liTag);
                });

                updateHiddenInput();
                countTags();
            }

            function addTag() {
                const tag = color;
                if (tag.length > 0 && !tags.includes(tag)) {
                    if (tags.length < maxTags) {
                        tags.push(tag);
                        createTag();
                    } else {
                        alert("Maximum 12 colors allowed.");
                    }
                }
            }

            function removeTag(tag) {
                tags = tags.filter(t => t !== tag);
                createTag();
            }

            addTag();
        }
        document.addEventListener('DOMContentLoaded', function() {
            const updateReqButtons = document.querySelectorAll('.update-button');
            const updateRequestModal = document.getElementById('updateRequestModal');
            const closeUpdateRequestModal = document.getElementById('closeUpdateRequestModal');
            const updateRequestForm = document.getElementById('updateRequestForm');
            const designerInput = document.getElementById('designerInput');

            updateReqButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const designRequestId = this.getAttribute('data-designRequest-id');
                    const customer = this.getAttribute('data-designRequest-customer');
                    const referenceImage = this.getAttribute('data-designRequest-referenceImage');
                    const name = this.getAttribute('data-requestHeader-name');
                    const size = this.getAttribute('data-designRequest-size');
                    const [width, height] = size.split('x');
                    const totalPieces = this.getAttribute('data-requestHeader-totalPieces');
                    const colors = this.getAttribute('data-requestHeader-colors');
                    const description = this.getAttribute('data-requestHeader-description');
                    const designerId = this.getAttribute('data-requestHeader-designerId');
                    const designerName = this.getAttribute('data-requestHeader-designerName');
                    const payPerPiece = this.getAttribute('data-requestHeader-payPerPiece');
                    const payDesigner = this.getAttribute('data-requestHeader-payDesigner');
                    const payMachineOps = this.getAttribute('data-requestHeader-payMachineOps');
                    const payQcOps = this.getAttribute('data-requestHeader-payQcOps');

                    updateRequestForm.querySelector('input[name="designRequestId"]').value =
                        designRequestId;
                    updateRequestForm.querySelector('input[name="customer"]').value = customer;
                    updateRequestForm.querySelector('img#referenceImageUpdate').src =
                        `/${referenceImage}`;
                    updateRequestForm.querySelector('input[name="name"]').value = name;
                    updateRequestForm.querySelector('input[name="sizeW"]').value = "Width : " +
                        width;
                    updateRequestForm.querySelector('input[name="sizeH"]').value = "Height : " +
                        height;
                    updateRequestForm.querySelector('input[name="total_pieces"]').value =
                        totalPieces;
                    updateRequestForm.querySelector('textarea[name="description"]').value =
                        description;
                    updateRequestForm.querySelector('input[name="pay_per_piece"]').value =
                        payPerPiece;
                    updateRequestForm.querySelector('input[name="pay_designer"]').value =
                        payDesigner;
                    updateRequestForm.querySelector('input[name="pay_per_operator"]').value =
                        payMachineOps;
                    updateRequestForm.querySelector('input[name="pay_per_qc"]').value = payQcOps;

                    designerInput.innerHTML = '';
                    designerInput.innerHTML += `
                                            <label for="designer" class="text-gray-600 font-light text-sm">designer</label>
                                            <select name="designer" id="designer"
                                                class="w-full border rounded text-gray-600 p-2" required>
                                                <option selected value="${designerId}" id="selectedDesigner">${designerName}</option>
                                                @foreach ($designers as $designer)
                                                    <option value="{{ $designer->id }}">{{ $designer->name }}</option>
                                                @endforeach
                                            </select>
                                            `;
                    updateRequestModal.classList.remove('hidden');
                    updateRequestModal.classList.add('flex');
                    const colorArray = colors.split(",");
                    colorArray.forEach(color => {
                        autoColorInput(color);
                    })
                    initColorInput();
                });
            });

            closeUpdateRequestModal.addEventListener('click', function() {
                updateRequestModal.classList.add('hidden');
                updateRequestModal.classList.remove('flex');
            });

            updateRequestModal.addEventListener('click', function(e) {
                if (e.target === updateRequestModal) {
                    updateRequestModal.classList.add('hidden');
                    updateRequestModal.classList.remove('flex');
                }
            });
        });
    </script>

    {{-- Script Modal Add --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mendapatkan elemen-elemen yang dibutuhkan
            const addButton = document.querySelectorAll('.add-button');
            const addModal = document.getElementById('addModal');
            const closeModal = document.getElementById('closeAddModal');
            const addUserForm = document.getElementById('addUserForm');

            addButton.forEach(button => {
                button.addEventListener('click', function() {
                    addModal.classList.remove('hidden');
                    addModal.classList.add('flex');
                });
            });

            closeModal.addEventListener('click', function() {
                addModal.classList.add('hidden');
                addModal.classList.remove('flex');
            });

            addModal.addEventListener('click', function(e) {
                if (e.target === addModal) {
                    addModal.classList.add('hidden');
                    addModal.classList.remove('flex');
                }
            });
        });
    </script>

    {{-- Script View Request --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewButton = document.querySelectorAll('.view-button');
            const requestModal = document.getElementById('requestModal');
            const closeModal = document.getElementById('closeRequestModal');

            viewButton.forEach(button => {
                button.addEventListener('click', function() {
                    const requestPic = this.getAttribute('data-requestPic');
                    const requestCustomer = this.getAttribute('data-requestCustomer');
                    const requestName = this.getAttribute('data-requestName');
                    const requestPiece = this.getAttribute('data-requestPiece');
                    const requestSizeW = this.getAttribute('data-requestSizeW');
                    const requestSizeH = this.getAttribute('data-requestSizeH');
                    const requestDescription = this.getAttribute('data-requestDescription');
                    const requestPicElement = requestModal.querySelector('#requestPic');
                    requestPicElement.src = `/${requestPic}`;
                    requestModal.querySelector('input[name="customer"]').value = requestCustomer;
                    requestModal.querySelector('input[name="name').value = requestName;
                    requestModal.querySelector('input[name="total_pieces').value = requestPiece;
                    requestModal.querySelector('input[name="sizeW').value = "Width : "+requestSizeW;
                    requestModal.querySelector('input[name="sizeH').value = "Height : "+requestSizeH;
                    requestModal.querySelector('textarea[name="description').value =
                        requestDescription;

                    requestModal.classList.remove('hidden');
                    requestModal.classList.add(
                        'flex');
                });
            });

            closeModal.addEventListener('click', function() {
                requestModal.classList.add('hidden');
                requestModal.classList.remove('flex');
            });

            requestModal.addEventListener('click', function(e) {
                if (e.target === requestModal) {
                    requestModal.classList.add('hidden');
                    requestModal.classList.remove('flex');
                }
            });
        });
    </script>

    {{-- Script add request --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addRequests = document.querySelectorAll('.addRequests-button');
            const addModal = document.getElementById('addRequestsModal');
            const closeModal = document.getElementById('closeInsertModal');
            const insertRequestsForm = document.getElementById('insertRequestsForm');

            addRequests.forEach(button => {
                button.addEventListener('click', function() {
                    const headerId = this.getAttribute('data-requestHeaderId');
                    addModal.classList.remove('hidden');
                    addModal.classList.add('flex');
                    insertRequestsForm.querySelector('input[name="headerId"]').value = headerId;;
                });
            });

            closeModal.addEventListener('click', function() {
                addModal.classList.add('hidden');
                addModal.classList.remove('flex');
            });

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
            const underLine = document.getElementById('underline');
            const workButton = document.getElementById('work');
            const doneButton = document.getElementById('done');
            const workTable = document.getElementById('uncompletedRequests');
            const doneTable = document.getElementById('completedRequests');

            function setActiveButton(activeButton, inactiveButton) {
                activeButton.classList.add('text-blue-500', 'font-semibold');
                inactiveButton.classList.remove('text-blue-500', 'font-semibold');

                const activeButtonRect = activeButton.getBoundingClientRect();
                underLine.style.width = `${activeButtonRect.width}px`;
                underLine.style.marginLeft =
                    `${activeButtonRect.left - workButton.getBoundingClientRect().left + 3}px`;
            }

            function switchTables(showTable, hideTable) {
                hideTable.style.opacity = '0';
                hideTable.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    hideTable.classList.add('hidden');
                    showTable.classList.remove('hidden');

                    void showTable.offsetWidth;

                    showTable.style.opacity = '1';
                    showTable.style.transform = 'translateY(0)';
                }, 300);
            }

            doneButton.addEventListener('click', function() {
                setActiveButton(doneButton, workButton);
                switchTables(doneTable, workTable);
            });

            workButton.addEventListener('click', function() {
                setActiveButton(workButton, doneButton);
                switchTables(workTable, doneTable);
            });

            setActiveButton(workButton, doneButton);
        });
    </script>


    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            document.getElementById(modalId).classList.add('flex');

        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>

    <!-- Approved Modal Show -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const approveButtons = document.querySelectorAll('.approved-button');
            const updateBlockButtons = document.querySelectorAll('.updateBlock-button');

            approveButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    const rowId = this.getAttribute('data-id');

                    const approvalModal = document.getElementById(`approvalModal-${rowId}`);
                    const approvalText = document.getElementById(`approvalText-${rowId}`);

                    approvalText.classList.remove('opacity-0', '-translate-y-4');
                    approvalText.classList.add('opacity-100', 'translate-y-0');
                    approvalModal.classList.remove('hidden');
                    approvalModal.classList.add('flex');

                    setTimeout(() => {
                        approvalText.classList.remove('opacity-100', 'translate-y-0');
                        approvalText.classList.add('opacity-0', '-translate-y-4');
                        setTimeout(() => {
                            approvalModal.classList.remove('flex');
                            approvalModal.classList.add('hidden');
                        }, 1000);
                    }, 1500);
                });
            });
            updateBlockButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    const rowId = this.getAttribute('data-id');

                    const updateBlockModal = document.getElementById(`updateBlockModal-${rowId}`);
                    const updateBlockText = document.getElementById(`updateBlockText-${rowId}`);

                    updateBlockText.classList.remove('opacity-0', '-translate-y-4');
                    updateBlockText.classList.add('opacity-100', 'translate-y-0');
                    updateBlockModal.classList.remove('hidden');
                    updateBlockModal.classList.add('flex');

                    setTimeout(() => {
                        updateBlockText.classList.remove('opacity-100', 'translate-y-0');
                        updateBlockText.classList.add('opacity-0', '-translate-y-4');
                        setTimeout(() => {
                            updateBlockModal.classList.remove('flex');
                            updateBlockModal.classList.add('hidden');
                        }, 1000);
                    }, 1500);
                });
            });
        });
    </script>
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
