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
        <div>
            <div class="flex flex-col ml-8">
                <div class="flex">
                    <button id="work" class="ml-1.5 mr-4 text-blue-500 font-semibold">
                        @if (Auth()->User()->hasRole('admin'))
                            Ongoing Ops
                        @else
                            My Jobs
                        @endif
                    </button>
                    <button id="done" class="mr-4">
                        @if (Auth()->User()->hasRole('admin'))
                            Accomplished Ops
                        @else
                            Jobs History
                        @endif
                    </button>
                </div>
                <!-- Tambahkan hr setelah button pertama -->
                <hr id="underline" class="ml-1 w-0 border-blue-500 mb-3"
                    style="border-width: 1.5px; transition: 0.5s ease;">
            </div>
            <div id="machineOps" class="max-w-7xl mx-auto sm:px-6 lg:px-8 transition-all duration-500 opacity-100">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                    <div class="flex justify-between items-center mb-4">
                        <div class="flex">
                            <h3 class="text-lg font-medium pt-1 mr-3">
                                @if (Auth()->User()->hasRole('admin'))
                                    Machine Ops
                                @else
                                    My Jobs
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

                    @if ($designReqsAll->isEmpty())
                        <p>No designs found.</p>
                    @else
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">NO</th>
                                    <th class="py-3 px-6 text-left">Reference</th>
                                    <th class="py-3 px-6 text-left">Request Name</th>
                                    <th class="py-3 px-6 text-left">Specifications</th>
                                    <th class="py-3 px-6 text-left">Operation Summary</th>
                                    <th class="py-3 px-6 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light">
                                @foreach ($designReqsAll as $designReq)
                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                        <td class="py-3 px-6 text-left whitespace-nowrap">
                                            {{ $loop->iteration }}</td>
                                        <td class="py-3 px-6 text-left">
                                            <button class="text-blue-500"
                                                onclick="openModal('modal-{{ $designReq->id }}')">
                                                <img src="{{ asset($designReq->reference_image) }}"
                                                    alt="Reference Image" class="w-24 h-auto">
                                            </button>
                                        </td>
                                        <td class="py-3 px-6 text-left">{{ $designReq->name }}</td>
                                        <td class="py-3 px-6 text-left">
                                            @php
                                                $size = explode('x', $designReq->size);
                                                $width = $size[0] ?? 'N/A';
                                                $height = $size[1] ?? 'N/A';
                                                $colors = explode(',', $designReq->color);
                                            @endphp
                                            <p class="font-semibold">Size:</p>
                                            W: {{ $width }} <br>
                                            H: {{ $height }} <br>
                                            <p class="font-semibold">Colors:</p> {{ $designReq->color }}
                                        </td>
                                        <td class="py-3 px-6 text-left">
                                            @php
                                                $completedOps = 0;

                                                if ($designReq->design) {
                                                    $completedOps += $designReq->design->machineOperations->sum(
                                                        'quantity',
                                                    );
                                                }

                                                $remainingOps = $designReq->total_pieces - $completedOps;
                                            @endphp

                                            <p class="font-semibold">Requested:</p> {{ $designReq->total_pieces }}
                                            <br>
                                            <p class="font-semibold">Completed:</p> {{ $completedOps }} <br>
                                            <p class="font-semibold">Remaining:</p> {{ $remainingOps }} <br>
                                        </td>
                                        <td class="py-3 px-6 text-center">
                                            <div class="flex item-center justify-center">
                                                <a href="#" data-machineOp-idReq="{{ $designReq->id }}"
                                                    data-machineOp-id="{{ $designReq->design->id }}"
                                                    data-machineOp-pic="{{ $designReq->reference_image }}"
                                                    data-machineOp-name="{{ $designReq->design->design_name }}"
                                                    data-machineOp-maxStore="{{ $remainingOps }}"
                                                    data-machineOp-operatorId="{{ Auth::id() }}"
                                                    data-machineOp-operatorName="{{ Auth::user()->name }}"
                                                    class="work-button w-4 mr-2 scale-125 transform hover:text-blue-500 hover:scale-150 transition duration-75">
                                                    <i class="bx bx-task"></i>
                                                </a>

                                                <a href="{{ route('designFile.download', ['design_file' => basename($designReq->design->design_files), 'name' => $designReq->design->design_name]) }}"
                                                    class="download-button w-4 mr-2 scale-125 transform hover:text-teal-500 hover:scale-150 transition duration-75">
                                                    <i class="bx bx-download"></i>
                                                </a>
                                                @php
                                                    $colors = $designReq->color;
                                                    $colorArray = explode(',', $colors);
                                                    $formattedColors = array_map('ucfirst', $colorArray);
                                                    $formattedColorString = implode(', ', $formattedColors);

                                                    $size = explode('x', $designReq->size);
                                                    $width = $size[0] ?? 'N/A';
                                                    $height = $size[1] ?? 'N/A';

                                                @endphp
                                                <a href="#" data-pic="{{ $designReq->reference_image }}"
                                                    data-name="{{ $designReq->name }}"
                                                    data-colors="{{ $formattedColorString }}"
                                                    data-sizeW="{{ $width }}"
                                                    data-sizeH="{{ $height }}"
                                                    data-desc="{{ $designReq->description }}"
                                                    class="view-button w-4 mr-2 scale-125 transform hover:text-green-500 hover:scale-150 transition duration-75">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <!-- Hanya designer tidak memiliki aksi update dan delete -->
                                            </div>
                                        </td>
                                    </tr> <!-- Modal -->
                                    <div id="modal-{{ $designReq->id }}"
                                        class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center"
                                        style="z-index: 999">
                                        <div class="bg-white p-4 rounded-lg w-96 max-w-sm">
                                            <!-- width 80 (320px) dengan max width 640px -->
                                            <div class="flex justify-between">
                                                <h5 class="text-lg font-bold">Reference Image</h5>
                                                <button class="text-gray-500"
                                                    onclick="closeModal('modal-{{ $designReq->id }}')">&times;</button>
                                            </div>
                                            <img src="{{ asset($designReq->reference_image) }}" alt="Reference Image"
                                                class="w-full h-auto mt-2 rounded-md">
                                        </div>
                                    </div>
                                    <!-- Modal Upload -->
                                    <div id="uploadModal-{{ $designReq->id }}"
                                        class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 z-50 hidden">
                                        <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl w-full">
                                            <div class="flex items-center justify-between">
                                                <i id="closeUploadModal"
                                                    class="bx bx-arrow-back scale-125 font-extrabold mb-4 cursor-pointer hover:scale-150"></i>
                                                <h2 class="text-xl font-bold mb-4 pr-4 mx-auto underline">
                                                    Upload Design
                                                    File
                                                </h2>
                                            </div>
                                            <form id="uploadForm-{{ $designReq->id }}"
                                                action="{{ route('design.upload') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="request_id"
                                                    value="{{ $designReq->id }}">
                                                <div class="mt-4">
                                                    <!-- Label -->
                                                    <label for="design_file_{{ $designReq->id }}"
                                                        class="text-gray-600 font-light text-sm">Upload
                                                        File
                                                        (RAR/ZIP)
                                                    </label>

                                                    <!-- Drag and Drop Area -->
                                                    <div id="drop-area-{{ $designReq->id }}"
                                                        class="w-full h-40 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                                                        <i class="bx bxs-cloud-upload text-4xl text-gray-400 mb-2"></i>
                                                        <p class="text-gray-400">Drag & Drop your file
                                                            here, or
                                                            click
                                                            to
                                                            upload</p>
                                                        <input type="file" name="design_file"
                                                            id="design_file_{{ $designReq->id }}" accept=".rar,.zip"
                                                            class="hidden">
                                                    </div>
                                                </div>

                                                <!-- File Name Display -->
                                                <div id="file-name-container-{{ $designReq->id }}"
                                                    class="mt-4 hidden">
                                                    <p class="text-gray-600 font-light text-sm">
                                                        Selected
                                                        File:
                                                    </p>
                                                    <p id="file-name-{{ $designReq->id }}"
                                                        class="text-gray-800 font-medium">
                                                    </p>
                                                </div>
                                                <!-- Design Name -->
                                                <div class="mt-3">
                                                    <label for="name"
                                                        class="text-gray-600 font-light text-sm">Design
                                                        Name</label>
                                                    <input type="text" name="name"
                                                        id="name_{{ $designReq->id }}"
                                                        placeholder="Enter Design Name"
                                                        class="w-full border rounded p-2 focus:outline-none focus:border-blue-500"
                                                        required>
                                                </div>
                                                <div class="flex justify-end mt-6">
                                                    <button type="submit"
                                                        class="bg-blue-500 text-white font-bold py-2 px-6 rounded hover:bg-blue-600 transition duration-200">Add
                                                        User</button>
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
            <div id="accomplishedJobTable"
                class="max-w-7xl mx-auto sm:px-6 lg:px-8 transition-all transform ease-in-out duration-500 opacity-0 hidden">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex">

                            <h3 class="text-lg font-medium pt-1 mr-3">
                                @if (Auth()->User()->hasRole('admin'))
                                    Accomplished Jobs
                                @else
                                    Jobs History
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

                    @if ($machineOps->isEmpty())
                        <p>No Machine Ops found.</p>
                    @else
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">NO</th>
                                    <th class="py-3 px-6 text-left">Reference</th>
                                    <th class="py-3 px-6 text-left">Design</th>
                                    <th class="py-3 px-6 text-left">Operator</th>
                                    <th class="py-3 px-6 text-left">Qty</th>
                                    <th class="py-3 px-6 text-left">Time</th>
                                    <th class="py-3 px-6 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light">
                                @foreach ($machineOps as $machineOp)
                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                        {{-- data-profile-image="{{ $user->profile_picture }}" data-name="{{ $user->name }}"
                                    data-email="{{ $user->email }}" data-contacts="{{ $user->contact_info }}"
                                    data-address="{{ $user->address }}" data-id="{{ $user->id }}"> --}}

                                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $loop->iteration }}
                                        </td>
                                        <td class="py-3 px-6 text-left">
                                            <button class="text-blue-500"
                                                onclick="openModal('modal-{{ $machineOp->design->designRequest->reference_image }}')">
                                                <img src="{{ asset($machineOp->design->designRequest->reference_image) }}"
                                                    alt="Reference Image" class="w-24 h-auto">
                                            </button>
                                        </td>
                                        <td class="py-3 px-6 text-left">{{ $machineOp->design->design_name }}</td>
                                        <td class="py-3 px-6 text-left">
                                            <p class="font-semibold">Operated By :</p>{{ $machineOp->operator->name }}
                                            <br>
                                            @if ($machineOp->assistant)
                                                <p class="font-semibold">Assisted By :</p>
                                                {{ $machineOp->assistant->name }}
                                            @endif
                                        </td>
                                        <td class="py-3 px-6 text-left">{{ $machineOp->quantity }}</td>
                                        <td class="py-3 px-6 text-left">
                                            Created on: <br>{{ $machineOp->created_at->format('d-m-Y H:i') }}<br>
                                        </td>
                                        <td class="py-3 px-6 text-center">
                                            <div class="flex item-center justify-center">
                                                <a href="#"
                                                    data-pic="{{ $machineOp->design->designRequest->reference_image }}"
                                                    data-name="{{ $machineOp->design->designRequest->name }}"
                                                    data-operator="{{ $machineOp->operator->name }}"
                                                    data-assistant="{{ $machineOp->assistant ? $machineOp->assistant->name : '-' }}"
                                                    data-worked="{{ $machineOp->quantity }}"
                                                    data-comment="{{ $machineOp->comments }}"
                                                    class="viewOps-button w-4 mr-2 scale-125 transform hover:text-green-500 hover:scale-150 transition duration-75">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Work Machine Ops -->
    <div id="workModal" class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full" style="height: 600px;">
            <div class="flex items-center justify-between shadow-md">
                <i id="closeWorkModal"
                    class="bx bx-arrow-back scale-125 font-extrabold mb-4 cursor-pointer hover:scale-150"></i>
                <h2 class="text-xl font-bold mb-4 pr-4 mx-auto underline">Store Machine Ops</h2>
            </div>
            <form id="workForm" action="{{ route('machineOperation.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="referenceImage">
                <div class="request-container overflow-y-scroll" style="max-height: 420px">
                    <input type="hidden" name="designId">
                    <input type="hidden" name="operatorId">
                    <input type="hidden" name="designReqId">
                    <div class="flex flex-col p-3" id="request-1">
                        <div class="flex justify-center mt-3 mx-2">
                            <label for="userProfileImageInput-1">
                                <img id="designReference" src="{{ asset('assets/images/rpl.png') }}"
                                    alt="User Profile" class="w-48 h-48 rounded-xl object-contain">
                            </label>
                        </div>
                        <div class="p-3">
                            <!-- Design -->
                            <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mt-4">
                                <div>
                                    <label for="designName" class="text-gray-600 font-light text-sm">Design</label>
                                    <input readonly type="text" name="designName" placeholder="Design Name"
                                        class="w-full border rounded p-2 focus:outline-none bg-slate-200 focus:border-blue-500"
                                        required>
                                </div>
                            </div>

                            <!-- Operator -->
                            <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mt-4">
                                <div>
                                    <label for="operator" class="text-gray-600 font-light text-sm">Operator</label>
                                    <input readonly type="text" name="operator" placeholder="Enter operator"
                                        class="w-full border rounded p-2 focus:outline-none bg-slate-200 focus:border-blue-500"
                                        required>
                                </div>
                            </div>

                            <!-- Assistant -->
                            <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mt-4">
                                <div>
                                    <label for="assistant" class="text-gray-600 font-light text-sm">Assistant</label>
                                    <select name="assistant" id="assistant"
                                        class="w-full border rounded p-2 focus:outline-none focus:border-blue-500">
                                        <option selected value="">No assistant</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Quantity -->
                            <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mt-4">
                                <div>
                                    <label for="quantity" class="text-gray-600 font-light text-sm">Quantity</label>
                                    <input type="number" min="1" name="quantity"
                                        placeholder="Enter quantity worked"
                                        class="w-full border rounded p-2 focus:outline-none focus:border-blue-500"
                                        required>
                                </div>
                            </div>
                            <div class="flex">
                                <p class="font-light">Remaining Ops : </p>
                                <p class="pl-2" id="maxValue"></p>
                            </div>

                            <!-- Comment -->
                            <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mt-4">
                                <div>
                                    <label for="comment" class="text-gray-600 font-light text-sm">comment</label>
                                    <textarea id="comment" name="comment" placeholder="Enter Comment" rows="4"
                                        class="w-full border rounded p-2 focus:outline-none focus:border-blue-500"></textarea>
                                </div>
                            </div>
                            <hr class="border-2">
                            <div class="flex text-blue-500 mt-2 cursor-pointer">
                                <a id="downloadDesign" class="flex">
                                    <i class="bx bx-download mt-1 mr-2"></i>
                                    <p>Download Design File</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="bg-blue-500 text-white font-bold py-2 px-6 rounded hover:bg-blue-600 transition duration-200">Store</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal View Machine Ops -->
    <div id="viewOpsModal" class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full" style="max-height: 95vh; height: auto">
            <div class="flex items-center justify-between">
                <i id="closeViewOpsModal"
                    class="bx bx-arrow-back scale-125 font-extrabold mb-4 cursor-pointer hover:scale-150"></i>
                <h2 class="text-xl font-bold mb-4 pr-4 mx-auto">View Machine Ops</h2>
            </div>
            <div class="machineOps overflow-y-scroll flex flex-col p-3" id="request-1" style="max-height: 75vh; height: auto;">
                <div class="flex flex-col items-center justify-center mt-3 mx-2">
                    <label for="userProfileImageInput-1">
                        <img id="designReference" src="{{ asset('assets/images/rpl.png') }}" alt="User Profile"
                            class="w-48 h-48 rounded-xl object-contain">
                        </label>
                        <p class="font-semibold text-lg pt-2" id="requestName">Logo Logoan</p>
                </div>
                <div class="p-3">
                    <!-- Operator -->
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mt-4">
                        <div>
                            <label for="operator" class="text-gray-600 font-light text-sm">Operated by :</label>
                            <p id="operatorName">Cahyadi</p>
                        </div>
                    </div>

                    <!-- Assistant -->
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mt-4">
                        <div>
                            <label for="assistant" class="text-gray-600 font-light text-sm">Assisted by :</label>
                            <p id="assistantName">Pamungkaz</p>
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mt-4">
                        <div>
                            <label for="quantity" class="text-gray-600 font-light text-sm">Quantity Worked :</label>
                            <p id="piecesWorked">907Pcs</p>
                        </div>
                    </div>

                    <!-- Comment -->
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mt-4">
                        <div>
                            <label for="comment" class="text-gray-600 font-light text-sm">Comment :</label>
                            <p class="w-full flex-wrap text-wrap" id="comment">
                                dua an ngerjainnya
                                dua an ngerjainnya
                                dua an ngerjainnya
                                dua an ngerjainnya
                                dua an ngerjainnya
                                dua an ngerjainnya

                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal View Request -->
    <div id="viewModal" class="fixed inset-0 items-center justify-center z-50 bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl w-auto" style="height: auto">
            <div class="flex items-center justify-between">
                <i id="closeViewModal"
                    class="bx bx-arrow-back scale-125 font-extrabold mb-4 cursor-pointer hover:scale-150"></i>
                <h2 class="text-xl font-bold mb-4 pr-4 mx-auto">View Request</h2>
            </div>
            <input type="hidden" name="referenceImage">
            <div class="request-container" style="max-height: 85vh">
                <div class="flex p-3 pb-5" id="request-1">
                    <div class="flex justify-center mt-3 -ml-4 mx-3">
                        <div class="flex flex-col items-center">
                            <div class="flex items-center">
                                <div class="flex flex-row items-center mr-2">
                                    <span id="sizeH" class="text-sm mb-1 -rotate-90">?CM</span>
                                    <div class="h-48 w-px bg-black border-black"></div>
                                </div>
                                <img id="requestPic" src="{{ asset('assets/images/mu.jpeg') }}" alt="User Profile"
                                    class="w-48 h-48 rounded-2xl object-cover">
                            </div>
                            <div class="relative w-48 mt-2 ml-10">
                                <hr class="border-black">
                                <span id="sizeW"
                                    class="absolute inset-0 flex justify-center text-sm top-2 transform -translate-y-1/2 bg-white">?CM</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-3">
                        <div class="w-full">
                            <label for="requestName" class="text-gray-700 font-normal text-sm">Request Name :
                            </label>
                            <p id="requestName" class="text-black text-lg font-semibold">MU BOBROK</p>
                            <hr>
                        </div>
                        <div class="w-full my-1">
                            <label for="requestColors" class="text-gray-700 font-normal text-sm">Colors :
                            </label>
                            <p id="requestColors" class="text-black text-lg font-semibold">Red, Green, Blue
                            </p>
                            <hr>
                        </div>
                        <div class="w-full text-wrap flex-wrap">
                            <label for="requestDesc" class="text-gray-700 font-normal text-sm">Description :
                            </label>
                            <p id="requestDesc" class="text-black text-lg font-semibold">Lorem ipsum dolor sit
                                {{-- amet, consectetur adipisicing elit. Est laboriosam, error voluptatibus ut neque
                                            nihil consectetur in ratione, reiciendis accusamus cupiditate minus iusto modi --}}
                                maxime ab repellat molestias esse dolorem!</p>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const underLine = document.getElementById('underline');
            const workButton = document.getElementById('work');
            const doneButton = document.getElementById('done');
            const workTable = document.getElementById('machineOps');
            const doneTable = document.getElementById('accomplishedJobTable');

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
                switchTables(doneTable, workTable);
            });

            workButton.addEventListener('click', function() {
                setActiveButton(workButton, doneButton);
                switchTables(workTable, doneTable);
            });

            // Initial setup
            setActiveButton(workButton, doneButton);
        });
    </script>

    {{-- Script Work --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const approveButtons = document.querySelectorAll('.work-button');
            const workModal = document.getElementById('workModal');
            const closeWorkModal = document.getElementById('closeWorkModal');
            const workForm = document.getElementById('workForm');

            approveButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const designId = this.getAttribute('data-machineOp-id');
                    const designIdReq = this.getAttribute('data-machineOp-idReq');
                    const designPic = this.getAttribute('data-machineOp-pic');
                    const designName = this.getAttribute('data-machineOp-name');
                    const designMax = this.getAttribute('data-machineOp-maxStore');
                    const operatorId = this.getAttribute('data-machineOp-operatorId');
                    const operatorName = this.getAttribute('data-machineOp-operatorName');

                    workForm.querySelector('img#designReference').src = `/${designPic}`;
                    workForm.querySelector('input[name="designName"]').value = designName;
                    workForm.querySelector('input[name="operator"]').value = operatorName;
                    workForm.querySelector('input[name="operatorId"]').value = operatorId;
                    workForm.querySelector('input[name="designId"]').value = designId;
                    workForm.querySelector('input[name="designReqId"]').value = designIdReq;
                    workForm.querySelector('#maxValue').textContent = designMax;
                    workForm.querySelector('input[name="quantity"]').max =
                        designMax; // Tampilkan modal
                    workModal.classList.remove('hidden');
                    workModal.classList.add('flex');
                    console.log(referenceImage); // Debugging
                });
            });

            // Tutup modal saat tombol "Close" diklik
            closeWorkModal.addEventListener('click', function() {
                workModal.classList.add('hidden');
                workModal.classList.remove('flex');
            });

            // Tutup modal saat area di luar modal diklik
            workModal.addEventListener('click', function(e) {
                if (e.target === workModal) {
                    workModal.classList.add('hidden');
                    workModal.classList.remove('flex');
                }
            });
        });
    </script>

    {{-- Script View Request --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewButton = document.querySelectorAll('.view-button');
            const viewModal = document.getElementById('viewModal');
            const closeModal = document.getElementById('closeViewModal');

            viewButton.forEach(button => {
                button.addEventListener('click', function() {
                    const pic = this.getAttribute('data-pic');
                    const name = this.getAttribute('data-name');
                    const sizeW = this.getAttribute('data-sizeW');
                    const sizeH = this.getAttribute('data-sizeH');
                    const colors = this.getAttribute('data-colors');
                    const desc = this.getAttribute('data-desc');
                    viewModal.querySelector('img#requestPic').src = `/${pic}`;
                    viewModal.querySelector('#requestName').textContent = name;
                    viewModal.querySelector('#sizeW').textContent = sizeW + 'CM';
                    viewModal.querySelector('#sizeH').textContent = sizeH + 'CM';
                    viewModal.querySelector('#requestColors').textContent = colors;
                    viewModal.querySelector('#requestDesc').textContent = desc;

                    viewModal.classList.remove('hidden');
                    viewModal.classList.add(
                        'flex');
                });
            });

            closeModal.addEventListener('click', function() {
                viewModal.classList.add('hidden');
                viewModal.classList.remove('flex');
            });

            viewModal.addEventListener('click', function(e) {
                if (e.target === viewModal) {
                    viewModal.classList.add('hidden');
                    viewModal.classList.remove('flex');
                }
            });
        });
    </script>

    {{-- Script View Machine Ops Request --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewOpsButton = document.querySelectorAll('.viewOps-button');
            const viewOpsModal = document.getElementById('viewOpsModal');
            const closeModal = document.getElementById('closeViewModal');

            viewOpsButton.forEach(button => {
                button.addEventListener('click', function() {
                    const pic = this.getAttribute('data-pic');
                    const name = this.getAttribute('data-name');
                    const operator = this.getAttribute('data-operator');
                    const assistant = this.getAttribute('data-assistant');
                    const worked = this.getAttribute('data-worked');
                    const comment = this.getAttribute('data-comment');
                    viewOpsModal.querySelector('img#designReference').src = `/${pic}`;
                    viewOpsModal.querySelector('#requestName').textContent = name;
                    viewOpsModal.querySelector('#operatorName').textContent = operator;
                    viewOpsModal.querySelector('#assistantName').textContent = assistant;
                    viewOpsModal.querySelector('#piecesWorked').textContent = worked;
                    viewOpsModal.querySelector('#comment').textContent = comment;

                    viewOpsModal.classList.remove('hidden');
                    viewOpsModal.classList.add(
                        'flex');
                });
            });

            closeModal.addEventListener('click', function() {
                viewOpsModal.classList.add('hidden');
                viewOpsModal.classList.remove('flex');
            });

            viewOpsModal.addEventListener('click', function(e) {
                if (e.target === viewOpsModal) {
                    viewOpsModal.classList.add('hidden');
                    viewOpsModal.classList.remove('flex');
                }
            });
        });
    </script>


</x-app-layout>
