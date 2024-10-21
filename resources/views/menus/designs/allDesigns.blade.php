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

        #uploadModal {
            z-index: 101;
        }

        #uploadModal.show {
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
                            Design Requests
                        @else
                            My Jobs
                        @endif
                    </button>
                    <button id="done" class="mr-4">
                        @if (Auth()->User()->hasRole('admin'))
                            Designs
                        @else
                            My Designs
                        @endif
                    </button>
                </div>
                <!-- Tambahkan hr setelah button pertama -->
                <hr id="underline" class="ml-1 w-0 border-blue-500 mb-3"
                    style="border-width: 1.5px; transition: 0.5s ease;">
            </div>
            <div id="designReqTable" class="max-w-7xl mx-auto sm:px-6 lg:px-8 transition-all duration-500 opacity-100">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                    <div class="flex justify-between items-center mb-4">
                        <div class="flex">
                            <h3 class="text-lg font-medium pt-1 mr-3">
                                @if (Auth()->User()->hasRole('admin'))
                                    Design Requests
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
                                    @if (Auth()->User()->hasRole('admin'))
                                        <th class="py-3 px-6 text-left">Assigned Designer</th>
                                    @endif
                                    {{-- <th class="py-3 px-6 text-left">Details</th> --}}
                                    <th class="py-3 px-6 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light">
                                @if (Auth::user()->hasRole('admin'))
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
                                                @if ($designReq->assignedDesigner)
                                                    {{ $designReq->assignedDesigner->name }}
                                                @else
                                                    No Designer Assigned
                                                @endif
                                            </td>
                                            <td class="py-3 px-6 text-left">
                                                @php
                                                    $size = explode('x', $designReq->size);
                                                    $width = $size[0] ?? 'N/A';
                                                    $height = $size[1] ?? 'N/A';

                                                    $colors = explode(',', $designReq->color);
                                                @endphp
                                                <div class="flex flex-col max-w-12">
                                                    <p class="font-semibold">Size:</p>
                                                    W: {{ $width }} <br>
                                                    H: {{ $height }} <br>
                                                    <p class="font-semibold">Colors:</p>
                                                    <div
                                                        class="overflow-hidden text-ellipsis whitespace-nowrap max-w-md">
                                                        {{ $designReq->color }}
                                                    </div>
                                                </div>
                                            </td>
                                            {{-- <td class="py-3 px-6 text-left">
                                                    Requested by:
                                                    <br><strong>
                                                        @if ($designReq->designRequestHeader->customer)
                                                            {{ $designReq->designRequestHeader->customer->name }}
                                                        @else
                                                            <p>No Requester assigned</p>
                                                        @endif
                                                    </strong><br>
                                                    Created on:
                                                    <br>{{ $designReq->created_at->format('d-m-Y H:i') }}<br>
                                                    Last updated on:
                                                    <br>{{ $designReq->updated_at->format('d-m-Y H:i') }}
                                                </td> --}}
                                            <td class="py-3 px-6 text-center">
                                                <div class="flex item-center justify-center">
                                                    <a href="{{ route('designReference.download', ['reference_image' => basename($designReq->reference_image), 'name' => $designReq->name]) }}"
                                                        class="download-button w-4 mr-2 scale-125 transform hover:text-green-500 hover:scale-150 transition duration-75">
                                                        <i class="bx bx-download"></i>
                                                    </a>
                                                    <a href="#" data-target="#uploadModal-{{ $designReq->id }}"
                                                        class="upload-button
                                                            w-4 mr-2 scale-125 transform hover:text-teal-500
                                                            hover:scale-150 transition duration-75">
                                                        <i class="bx bx-upload"></i>
                                                    </a>
                                                    <a href="#"
                                                        class="view-button w-4 mr-2 scale-125 transform hover:text-green-500 hover:scale-150 transition duration-75">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                    <a href="#"
                                                        class="update-button w-4 mr-2 scale-125 transform hover:text-indigo-500 hover:scale-150 transition duration-75">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                    <a href="#"
                                                        class="cancel-button w-4 mr-2 scale-125 transform hover:text-red-500 hover:scale-150 transition duration-75">
                                                        <i class="bx bx-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
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
                                                <img src="{{ asset($designReq->reference_image) }}"
                                                    alt="Reference Image" class="w-full h-auto mt-2 rounded-md">
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
                                                            <i
                                                                class="bx bxs-cloud-upload text-4xl text-gray-400 mb-2"></i>
                                                            <p class="text-gray-400">Drag & Drop your file
                                                                here, or
                                                                click
                                                                to
                                                                upload</p>
                                                            <input type="file" name="design_file"
                                                                id="design_file_{{ $designReq->id }}"
                                                                accept=".rar,.zip" class="hidden">
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
                                @elseif (Auth::user()->hasRole('designer'))
                                    @foreach ($designReqsDesigner as $designReq)
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
                                            @if (Auth()->User()->hasRole('admin'))
                                                <td class="py-3 px-6 text-left">
                                                    @if ($designReq->assignedDesigner)
                                                        {{ $designReq->assignedDesigner->name }}
                                                    @else
                                                        No Designer Assigned
                                                    @endif
                                                </td>
                                            @endif
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
                                            <td class="py-3 px-6 text-center">
                                                <div class="flex item-center justify-center">

                                                    <a href="{{ route('designReference.download', ['reference_image' => basename($designReq->reference_image), 'name' => $designReq->name]) }}"
                                                        class="view-button w-4 mr-2 scale-125 transform hover:text-green-500 hover:scale-150 transition duration-75">
                                                        <i class="bx bx-download"></i>
                                                    </a>
                                                    <a href="#" data-target="#uploadModal-{{ $designReq->id }}"
                                                        class="upload-button w-4 mr-2 scale-125 transform hover:text-teal-500 hover:scale-150 transition duration-75">
                                                        <i class="bx bx-upload"></i>
                                                    </a>
                                                    <a href="#"
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
                                                <img src="{{ asset($designReq->reference_image) }}"
                                                    alt="Reference Image" class="w-full h-auto mt-2 rounded-md">
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
                                                            <i
                                                                class="bx bxs-cloud-upload text-4xl text-gray-400 mb-2"></i>
                                                            <p class="text-gray-400">Drag & Drop your file
                                                                here, or
                                                                click
                                                                to
                                                                upload</p>
                                                            <input type="file" name="design_file"
                                                                id="design_file_{{ $designReq->id }}"
                                                                accept=".rar,.zip" class="hidden">
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
                                @endif
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>


            <div id="designTable"
                class="max-w-7xl mx-auto sm:px-6 lg:px-8 transition-all transform ease-in-out duration-500 opacity-0 hidden">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex">
                            <h3 class="text-lg font-medium pt-1 mr-3">
                                @if (Auth()->User()->hasRole('admin'))
                                    Designs
                                @else
                                    My Designs
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

                    @if ($designs->isEmpty())
                        <p>No designs found.</p>
                    @else
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">NO</th>
                                    <th class="py-3 px-6 text-left">Reference</th>
                                    <th class="py-3 px-6 text-left">Name</th>
                                    <th class="py-3 px-6 text-left">Status</th>
                                    <th class="py-3 px-6 text-left">Details</th>
                                    <th class="py-3 px-6 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light">
                                @if (Auth::user()->hasRole('admin'))
                                    @foreach ($designs as $design)
                                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                                            {{-- data-profile-image="{{ $user->profile_picture }}" data-name="{{ $user->name }}"
                                        data-email="{{ $user->email }}" data-contacts="{{ $user->contact_info }}"
                                        data-address="{{ $user->address }}" data-id="{{ $user->id }}"> --}}

                                            <td class="py-3 px-6 text-left whitespace-nowrap">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td class="py-3 px-6 text-left">
                                                <button class="text-blue-500"
                                                    onclick="openModal('modal-{{ $design->id }}')">
                                                    <img src="{{ asset($design->designRequest->reference_image) }}"
                                                        alt="Reference Image" class="w-24 h-auto">
                                                </button>
                                            </td>
                                            <td class="py-3 px-6 text-left">{{ $design->design_name }}</td>
                                            <td class="py-3 px-6 text-center">
                                                <div class="w-full bg-gray-200 rounded-full h-2.5 mb-1">
                                                    @php
                                                        $progressPercentage = match ($design->status) {
                                                            'in_design' => 33,
                                                            'revision' => 33,
                                                            'approved' => 66,
                                                            'completed' => 100,
                                                            'cancelled' => 100,
                                                            default => 0,
                                                        };

                                                        $progressColor = match ($design->status) {
                                                            'in_design' => 'bg-orange-500',
                                                            'revision' => 'bg-red-500',
                                                            'approved' => 'bg-yellow-500',
                                                            'completed' => 'bg-green-500',
                                                            'cancelled' => 'bg-gray-500',
                                                            default => 'bg-red-500',
                                                    }; @endphp
                                                    <div class="h-2.5 rounded-full {{ $progressColor }}"
                                                        style="width: {{ $progressPercentage }}%;"></div>
                                                </div>
                                                <span
                                                    class="text-sm text-gray-700">{{ ucfirst($design->status) }}</span>
                                            </td>
                                            <td class="py-3 px-6 text-left">
                                                Created by: <br><strong>{{ $design->designer->name }}</strong><br>
                                                Created on: <br>{{ $design->created_at->format('d-m-Y H:i') }}<br>
                                                Last updated on: <br>{{ $design->updated_at->format('d-m-Y H:i') }}
                                            </td>
                                            <td class="py-3 px-6 text-center">
                                                <div class="flex item-center justify-center">
                                                    @if ($design->status == 'in_design' || $design->status == 'revision')
                                                        <a href="#"
                                                            data-target="#revisionModal-{{ $design->id }}"
                                                            class="revision-button
                                                            w-4 mr-2 scale-125 transform hover:text-teal-500
                                                            hover:scale-150 transition duration-75">
                                                            <i class="bx bx-upload"></i>
                                                        </a>
                                                    @else
                                                        <a href="#"
                                                            class="revisioned-button w-4 mr-2 scale-125 transform hover:text-red-500 hover:scale-150 transition duration-75">
                                                            <i class="bx bx-redo"></i>
                                                        </a>
                                                    @endif
                                                    @if (Auth()->user()->hasRole('admin'))
                                                        <a href="{{ route('design.download', basename($design->design_files)) }}"
                                                            class="download-button w-4 mr-2 scale-125 transform hover:text-teal-500 hover:scale-150 transition duration-75">
                                                            <i class="bx bx-download"></i>
                                                        </a>
                                                    @endif
                                                    <a href="#"
                                                        class="view-button w-4 mr-2 scale-125 transform hover:text-green-500 hover:scale-150 transition duration-75">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                    @if (Auth()->User()->hasRole('admin'))
                                                        <a href="#"
                                                            class="approve-button w-4 mr-2 scale-125 transform hover:text-indigo-500 hover:scale-150 transition duration-75"
                                                            data-design-id="{{ $design->id }}"
                                                            data-design-requestId="{{ $design->request_id }}"
                                                            data-design-pic="{{ $design->designRequest->reference_image }}"
                                                            data-design-name="{{ $design->design_name }}"
                                                            data-design-designer="{{ $design->designer->name }}"
                                                            data-design-designerPic="{{ $design->designer->profile_picture }}"
                                                            data-design-customer="{{ $design->designRequest->designRequestHeader->customer->name }}"
                                                            data-design-customerPic="{{ $design->designRequest->designRequestHeader->customer->profile_picture }}"
                                                            data-design-file="{{ $design->design_files }}">
                                                            <i class="bx bx-check-circle"></i>
                                                        </a>
                                                        <a href="#"
                                                            class="cancel-button w-4 mr-2 scale-125 transform hover:text-red-500 hover:scale-150 transition duration-75">
                                                            <i class="bx bx-revision"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Modal -->
                                        <div id="modal-{{ $design->id }}"
                                            class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center"
                                            style="z-index: 999">
                                            <div class="bg-white p-4 rounded-lg w-96 max-w-sm">
                                                <!-- width 80 (320px) dengan max width 640px -->
                                                <div class="flex justify-between">
                                                    <h5 class="text-lg font-bold">Reference Image</h5>
                                                    <button class="text-gray-500"
                                                        onclick="closeModal('modal-{{ $design->id }}')">&times;</button>
                                                </div>
                                                <img src="{{ asset($design->designRequest->reference_image) }}"
                                                    alt="Reference Image" class="w-full h-auto mt-2 rounded-md">
                                            </div>
                                        </div>
                                        <!-- Modal Upload -->
                                        <div id="revisionModal-{{ $design->id }}"
                                            class="fixed inset-0 items-center justify-center bg-black z-50 bg-opacity-50 hidden">
                                            <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl w-full">
                                                <div class="flex items-center justify-between">
                                                    <i id="closeUploadModal"
                                                        class="bx bx-arrow-back scale-125 font-extrabold mb-4 cursor-pointer hover:scale-150"></i>
                                                    <h2 class="text-xl font-bold mb-4 pr-4 mx-auto underline">
                                                        Revision
                                                        Design
                                                        File
                                                    </h2>
                                                </div>
                                                <form id="uploadForm" action="{{ route('allUsers.store') }}"
                                                    method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="request_id"
                                                        value="{{ $design->designRequest->id }}">
                                                    <div class="mt-4">
                                                        <!-- Label -->
                                                        <label for="design_file"
                                                            class="text-gray-600 font-light text-sm">Upload File
                                                            (RAR/ZIP)
                                                        </label>

                                                        <!-- Drag and Drop Area -->
                                                        <div id="drop-area"
                                                            class="w-full h-40 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                                                            <i
                                                                class="bx bxs-cloud-upload text-4xl text-gray-400 mb-2"></i>
                                                            <p class="text-gray-400">Drag & Drop your file here, or
                                                                click
                                                                to
                                                                upload</p>
                                                            <input type="file" name="design_file" id="design_file"
                                                                accept=".rar,.zip" class="hidden">
                                                        </div>
                                                    </div>

                                                    <!-- File Name Display -->
                                                    <div id="file-name-container" class="mt-4 hidden">
                                                        <p class="text-gray-600 font-light text-sm">Selected File:
                                                        </p>
                                                        <p id="file-name" class="text-gray-800 font-medium"></p>
                                                    </div>
                                                    <!-- Design Name -->
                                                    <div class="mt-3">
                                                        <label for="name"
                                                            class="text-gray-600 font-light text-sm">Design
                                                            Name</label>
                                                        <input type="text" name="name" id="name"
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
                                @elseif (Auth::user()->hasRole('designer'))
                                    @foreach ($designDesigner as $design)
                                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                                            {{-- data-profile-image="{{ $user->profile_picture }}" data-name="{{ $user->name }}"
                                    data-email="{{ $user->email }}" data-contacts="{{ $user->contact_info }}"
                                    data-address="{{ $user->address }}" data-id="{{ $user->id }}"> --}}

                                            <td class="py-3 px-6 text-left whitespace-nowrap">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td class="py-3 px-6 text-left">
                                                <button class="text-blue-500"
                                                    onclick="openModal('modal-{{ $design->id }}')">
                                                    <img src="{{ asset($design->designRequest->reference_image) }}"
                                                        alt="Reference Image" class="w-24 h-auto">
                                                </button>
                                            </td>
                                            <td class="py-3 px-6 text-left">{{ $design->design_name }}</td>
                                            <td class="py-3 px-6 text-center">
                                                <div class="w-full bg-gray-200 rounded-full h-2.5 mb-1">
                                                    @php
                                                        $progressPercentage = match ($design->status) {
                                                            'in_design' => 33,
                                                            'revision' => 33,
                                                            'approved' => 66,
                                                            'completed' => 100,
                                                            'cancelled' => 100,
                                                            default => 0,
                                                        };

                                                        $progressColor = match ($design->status) {
                                                            'in_design' => 'bg-orange-500',
                                                            'revision' => 'bg-red-500',
                                                            'approved' => 'bg-yellow-500',
                                                            'completed' => 'bg-green-500',
                                                            'cancelled' => 'bg-gray-500',
                                                            default => 'bg-red-500',
                                                    }; @endphp
                                                    <div class="h-2.5 rounded-full {{ $progressColor }}"
                                                        style="width: {{ $progressPercentage }}%;"></div>
                                                </div>
                                                <span
                                                    class="text-sm text-gray-700">{{ ucfirst($design->status) }}</span>
                                            </td>
                                            <td class="py-3 px-6 text-left">
                                                Created by: <br><strong>{{ $design->designer->name }}</strong><br>
                                                Created on: <br>{{ $design->created_at->format('d-m-Y H:i') }}<br>
                                                Last updated on: <br>{{ $design->updated_at->format('d-m-Y H:i') }}
                                            </td>
                                            <td class="py-3 px-6 text-center">
                                                <div class="flex item-center justify-center">
                                                    @if ($design->status == 'in_design')
                                                        <a href="#"
                                                            data-target="#revisionModal-{{ $design->id }}"
                                                            class="revision-button
                                                        w-4 mr-2 scale-125 transform hover:text-teal-500
                                                        hover:scale-150 transition duration-75">
                                                            <i class="bx bx-upload"></i>
                                                        </a>
                                                    @else
                                                        <a href="#"
                                                            class="revision-button w-4 mr-2 scale-125 transform hover:text-teal-500 hover:scale-150 transition duration-75">
                                                            <i class="bx bx-upload"></i>
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('design.download', basename($design->design_files)) }}"
                                                        class="download-button w-4 mr-2 scale-125 transform hover:text-teal-500 hover:scale-150 transition duration-75">
                                                        <i class="bx bx-download"></i>
                                                    </a>
                                                    <a href="#"
                                                        class="view-button w-4 mr-2 scale-125 transform hover:text-green-500 hover:scale-150 transition duration-75">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                    @if (Auth()->User()->hasRole('admin'))
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
                                                            class="cancel-button w-4 mr-2 scale-125 transform hover:text-red-500 hover:scale-150 transition duration-75">
                                                            <i class="bx bx-trash"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Modal -->
                                        <div id="modal-{{ $design->id }}"
                                            class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center"
                                            style="z-index: 999">
                                            <div class="bg-white p-4 rounded-lg w-96 max-w-sm">
                                                <!-- width 80 (320px) dengan max width 640px -->
                                                <div class="flex justify-between">
                                                    <h5 class="text-lg font-bold">Reference Image</h5>
                                                    <button class="text-gray-500"
                                                        onclick="closeModal('modal-{{ $design->id }}')">&times;</button>
                                                </div>
                                                <img src="{{ asset($design->designRequest->reference_image) }}"
                                                    alt="Reference Image" class="w-full h-auto mt-2 rounded-md">
                                            </div>
                                        </div>
                                        <!-- Modal Upload -->
                                        <div id="revisionModal-{{ $design->id }}"
                                            class="fixed inset-0 items-center justify-center bg-black z-50 bg-opacity-50 hidden">
                                            <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl w-full">
                                                <div class="flex items-center justify-between">
                                                    <i id="closeUploadModal"
                                                        class="bx bx-arrow-back scale-125 font-extrabold mb-4 cursor-pointer hover:scale-150"></i>
                                                    <h2 class="text-xl font-bold mb-4 pr-4 mx-auto underline">
                                                        Revision
                                                        Design
                                                        File
                                                    </h2>
                                                </div>
                                                <form id="uploadForm" action="{{ route('allUsers.store') }}"
                                                    method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="request_id"
                                                        value="{{ $design->designRequest->id }}">
                                                    <div class="mt-4">
                                                        <!-- Label -->
                                                        <label for="design_file"
                                                            class="text-gray-600 font-light text-sm">Upload File
                                                            (RAR/ZIP)
                                                        </label>

                                                        <!-- Drag and Drop Area -->
                                                        <div id="drop-area"
                                                            class="w-full h-40 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                                                            <i
                                                                class="bx bxs-cloud-upload text-4xl text-gray-400 mb-2"></i>
                                                            <p class="text-gray-400">Drag & Drop your file here, or
                                                                click
                                                                to
                                                                upload</p>
                                                            <input type="file" name="design_file" id="design_file"
                                                                accept=".rar,.zip" class="hidden">
                                                        </div>
                                                    </div>

                                                    <!-- File Name Display -->
                                                    <div id="file-name-container" class="mt-4 hidden">
                                                        <p class="text-gray-600 font-light text-sm">Selected File:
                                                        </p>
                                                        <p id="file-name" class="text-gray-800 font-medium"></p>
                                                    </div>
                                                    <!-- Design Name -->
                                                    <div class="mt-3">
                                                        <label for="name"
                                                            class="text-gray-600 font-light text-sm">Design
                                                            Name</label>
                                                        <input type="text" name="name" id="name"
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
                                @endif
                            </tbody>
                        </table>
                    @endif

                </div>
            </div>

            <!-- Modal Approve Design -->
            @if (Auth()->user()->hasRole('admin'))
                <div id="approveModal"
                    class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 z-50 hidden">
                    <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full" style="height: 600px;">
                        <div class="flex items-center justify-between shadow-md">
                            <i id="closeApproveModal"
                                class="bx bx-arrow-back scale-125 font-extrabold mb-4 cursor-pointer hover:scale-150"></i>
                            <h2 class="text-xl font-bold mb-4 pr-4 mx-auto underline">Approve Design</h2>
                        </div>
                        <form id="approveForm" action="{{ route('design.approve', $design->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="referenceImage">
                            <div class="request-container overflow-y-scroll" style="max-height: 420px">
                                <input type="hidden" name="designId">
                                <input type="hidden" name="designRequestId">
                                <div class="flex flex-col p-3" id="request-1">
                                    <div class="flex justify-center mt-3 mx-2">
                                        <label for="userProfileImageInput-1">
                                            <img id="designReference" src="{{ asset('assets/images/rpl.png') }}"
                                                alt="User Profile" class="w-48 h-48 rounded-xl object-contain">
                                        </label>
                                    </div>
                                    <div class="p-3">
                                        <!-- Customer -->
                                        <div class="w-full">
                                            <label class="text-gray-500 font-light text-sm">Design Name
                                                :</label>
                                            <div class="p-2 text-black font-semibold">
                                                <h3 id="designName">Design Name</h3>
                                            </div>
                                        </div>

                                        <!-- Name and Total Pieces -->
                                        <div class="w-full">
                                            <label for="name-1" class="text-gray-600 font-light text-sm">Designed By
                                                :</label>
                                            <div class="p-2 text-black font-semibold flex">
                                                <div class="w-8 h-8">
                                                    <img id="designerPic"
                                                        src="{{ asset('images/profiles/sigue.jpg') }}"
                                                        alt="profilePic"
                                                        class="w-full h-full object-cover rounded-full">
                                                </div>
                                                <h2 id="designer" class="ml-2 mt-0.5">Designer Name</h2>
                                            </div>
                                        </div>

                                        <div class="w-full">
                                            <label for="total_pieces-1"
                                                class="text-gray-600 font-light text-sm">Requested
                                                By :</label>
                                            <div class="p-2 text-black font-semibold flex">
                                                <div class="w-8 h-8">
                                                    <img id="customerPic"
                                                        src="{{ asset('images/profiles/sigue.jpg') }}"
                                                        alt="profilePic"
                                                        class="w-full h-full object-cover rounded-full">
                                                </div>
                                                <h2 id="customer" class="ml-2 mt-0.5">Requester Name</h2>
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
                                    class="bg-blue-500 text-white font-bold py-2 px-6 rounded hover:bg-blue-600 transition duration-200">Approve</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif


            <script>
                console.log(localStorage.getItem('data-navLink-id'));

                function openModal(modalId) {
                    document.getElementById(modalId).classList.remove('hidden');
                    document.getElementById(modalId).classList.add('flex');

                }

                function closeModal(modalId) {
                    document.getElementById(modalId).classList.add('hidden');
                }
            </script>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const underLine = document.getElementById('underline');
                    const workButton = document.getElementById('work');
                    const doneButton = document.getElementById('done');
                    const workTable = document.getElementById('designReqTable');
                    const doneTable = document.getElementById('designTable');

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

            {{-- Script Modal Approved --}}
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const approveButtons = document.querySelectorAll('.approve-button');
                    const approveModal = document.getElementById('approveModal');
                    const closeApproveModal = document.getElementById('closeApproveModal');
                    const approveForm = document.getElementById('approveForm');

                    approveButtons.forEach(button => {
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


            <!-- Modal View -->
            {{-- <div id="userModal" class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 hidden">
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

            {{-- Script Modal Add --}}
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Get all upload buttons
                    const uploadButtons = document.querySelectorAll('.upload-button');

                    // Add click event listener to each upload button
                    uploadButtons.forEach(button => {
                        button.addEventListener('click', function(e) {
                            e.preventDefault();
                            const modalId = this.getAttribute('data-target');
                            const modal = document.querySelector(modalId);

                            if (modal) {
                                modal.classList.remove('hidden');
                                modal.classList.add('flex');
                            }
                        });
                    });

                    // Get all close buttons
                    const closeButtons = document.querySelectorAll('[id^="closeUploadModal-"]');

                    // Add click event listener to each close button
                    closeButtons.forEach(button => {
                        button.addEventListener('click', function() {
                            const modal = this.closest('[id^="uploadModal-"]');
                            if (modal) {
                                modal.classList.add('hidden');
                                modal.classList.remove('flex');
                            }
                        });
                    });

                    // Close modal when clicking outside
                    window.addEventListener('click', function(e) {
                        if (e.target.matches('[id^="uploadModal-"]')) {
                            e.target.classList.add('hidden');
                            e.target.classList.remove('flex');
                        }
                    });
                });
            </script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Mendapatkan semua area drop
                    const dropAreas = document.querySelectorAll('[id^="drop-area-"]');

                    dropAreas.forEach(dropArea => {
                        const designReqId = dropArea.id.split('-').pop();
                        const input = document.getElementById(`design_file_${designReqId}`);
                        const fileNameContainer = document.getElementById(`file-name-container-${designReqId}`);
                        const fileNameDisplay = document.getElementById(`file-name-${designReqId}`);
                        const nameInput = document.getElementById(`name_${designReqId}`);

                        if (dropArea && input && fileNameContainer && fileNameDisplay && nameInput) {
                            // Klik pada drop area untuk membuka file input
                            dropArea.addEventListener('click', () => input.click());

                            // Tangani perubahan input file
                            input.addEventListener('change', () => handleFiles(input, fileNameDisplay,
                                fileNameContainer, nameInput));

                            // Tambahkan highlight saat file di-drag ke drop area
                            dropArea.addEventListener('dragover', (e) => {
                                e.preventDefault();
                                dropArea.classList.add('border-blue-500', 'bg-gray-200');
                            });

                            // Hapus highlight ketika file tidak jadi di-drop
                            dropArea.addEventListener('dragleave', () => {
                                dropArea.classList.remove('border-blue-500', 'bg-gray-200');
                            });

                            // Tangani file yang di-drop langsung ke drop area
                            dropArea.addEventListener('drop', (e) => {
                                e.preventDefault();
                                dropArea.classList.remove('border-blue-500', 'bg-gray-200');
                                input.files = e.dataTransfer.files;
                                handleFiles(input, fileNameDisplay, fileNameContainer, nameInput);
                            });
                        } else {
                            console.error(
                                `Some elements are missing for design request ${designReqId}. Please check your HTML.`
                            );
                        }
                    });

                    // Fungsi untuk menampilkan nama file dan mengisi input name
                    function handleFiles(input, fileNameDisplay, fileNameContainer, nameInput) {
                        const file = input.files[0]; // Ambil file pertama
                        if (file) {
                            fileNameDisplay.textContent = file.name; // Tampilkan nama file
                            fileNameContainer.classList.remove('hidden'); // Tampilkan container nama file

                            // Isi input name dengan nama file tanpa ekstensi
                            const fileNameWithoutExtension = file.name.split('.').slice(0, -1).join('.');
                            nameInput.value = fileNameWithoutExtension;
                        }
                    }
                });
            </script>


            <!-- Modal Update User -->
            {{-- <div id="updateModal" class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 hidden">
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
    </div> --}}

            {{-- <!-- Modal Delete -->
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
