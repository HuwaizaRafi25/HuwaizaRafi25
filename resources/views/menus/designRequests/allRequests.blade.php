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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex">
                        <h3 class="text-lg font-medium pt-1 mr-3">
                            @if (Auth()->user()->hasRole('admin') || Auth()->user()->hasRole('supervisor'))
                                Design Requests
                            @else
                                My Design Requests
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
                            Add Request
                        </button>
                    </div>
                </div>


                @if ($designRequestHeaders->isEmpty())
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
                                <th class="py-3 px-6 text-center text-sm font-bold opacity-60">Actions</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($designRequestHeaders as $header)
                                <tr class="border-b border-gray-200 hover:bg-gray-100" data-header>
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
                                    <td class="py-3 px-6 text-left">{{ $header->created_at->format('Y-m-d H:i') }}</td>
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
                                                <tr class="nested-header">
                                                    <th class="py-3 px-6 text-left text-sm font-bold opacity-60">No
                                                    </th>
                                                    <th class="py-3 px-6 text-left text-sm font-bold opacity-60">
                                                        Reference</th>
                                                    <th class="py-3 px-6 text-left text-sm font-bold opacity-60">
                                                        Name</th>
                                                    <th class="py-3 px-6 text-left text-sm font-bold opacity-60">
                                                        Designer</th>
                                                    <th class="py-3 px-6 text-left text-sm font-bold opacity-60">Total
                                                        Pieces</th>
                                                    <th class="py-3 px-6 text-left text-sm font-bold opacity-60">Status
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
                                                                @if ($request->status == 'pending')
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
                                                                @else
                                                                    <a class="approved-button w-4 mr-2 scale-125 transform hover:text-teal-500 hover:scale-150 transition duration-75"
                                                                        data-id="{{ $request->id }}">
                                                                        <i class="bx bx-check-circle"></i>
                                                                        <!-- Modal untuk pesan persetujuan -->
                                                                    </a>
                                                                @endif
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
                                                                    data-user-address="{{ $user->address }}"> --}} <i
                                                                            class="bx bx-edit"></i>
                                                                    </a>

                                                                    <a href="#"
                                                                        class="delete-button w-4 mr-2 scale-125 transform hover:text-red-500 hover:scale-150 transition duration-75">
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
                                                                <br><b>{{ $request->name }}</b><br> has been approved
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
    </div>

<!-- Modal Add User -->
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
                            <input type="number" id="total_pieces-1" name="total_pieces[0]" placeholder="Enter Total Pieces"
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
                                        <input type="number" id="sizeW-1" name="sizeW[0]" placeholder="Width"
                                            class="w-full border rounded p-2 focus:outline-none focus:border-blue-500"
                                            required>
                                        <span class="mx-2 text-gray-600">X</span>
                                        <input type="number" id="sizeH-1" name="sizeH[0]" placeholder="Height"
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

            <script>
                let requestCount = 1; // Initialize counter

                document.getElementById('addRequest').addEventListener('click', function() {
                    requestCount++; // Increment counter for new request

                    const requestContainer = document.querySelector('.request-container');

                    // Create new form elements
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
                        </div>
                    `;

                    // Append new request form after hr
                    requestContainer.appendChild(newRequestDiv);
                    const separator = document.createElement('hr');
                    requestContainer.appendChild(separator);

                    // Initialize color input functionality for the new request
                    initColorInput(requestCount);
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

                function initColorInput(index) {
                    const ul = document.querySelector(`#tag-list-${index}`),
                        input = document.querySelector(`#color-input-${index}`),
                        addTagBtn = document.querySelector(`#add-tag-btn-${index}`),
                        hiddenInput = document.querySelector(`#hidden-color-input-${index}`),
                        tagCountDisplay = document.querySelector(`#tag-count-${index}`);

                    let maxTags = 12,
                        tags = [];

                    function updateHiddenInput() {
                        hiddenInput.value = tags.join(',');
                    }

                    function countTags() {
                        tagCountDisplay.innerText = `${maxTags - tags.length} colors remaining`;
                    }

                    function createTag() {
                        ul.querySelectorAll("li:not(.input-li)").forEach(li => li.remove());
                        tags.slice().reverse().forEach(tag => {
                            let liTag = `<li class="flex items-center bg-gray-200 rounded-lg px-3 py-1 m-1 text-sm">${tag} <i class="uil uil-multiply ml-2 cursor-pointer" onclick="removeTag('${tag}', ${index})"></i></li>`;
                            ul.insertAdjacentHTML("afterbegin", liTag);
                        });
                        updateHiddenInput();
                        countTags();
                    }

                    function addTag() {
                        let tag = input.value.trim().toLowerCase().replace(/\s+/g, ' ');
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

                    // Initialize removeTag function in global scope
                    window.removeTag = function(tag, requestIndex) {
                        const tagList = document.querySelector(`#tag-list-${requestIndex}`);
                        const hiddenInput = document.querySelector(`#hidden-color-input-${requestIndex}`);
                        const tagCountDisplay = document.querySelector(`#tag-count-${requestIndex}`);

                        const tags = hiddenInput.value.split(',');
                        const updatedTags = tags.filter(t => t !== tag);

                        hiddenInput.value = updatedTags.join(',');
                        tagList.querySelector(`li:contains('${tag}')`).remove();
                        tagCountDisplay.innerText = `${maxTags - updatedTags.length} colors remaining`;
                    };
                }

                // Initialize the first color input
                initColorInput(1);
            </script>
            <div class="flex justify-end mt-6">
                <button type="submit"
                    class="bg-blue-500 text-white font-bold py-2 px-6 rounded hover:bg-blue-600 transition duration-200">Submit</button>
            </div>
        </form>
    </div>
</div>    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Menangkap semua baris yang berisi data header
            const rows = document.querySelectorAll('tr[data-header]');

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
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            document.getElementById(modalId).classList.add('flex');

        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
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
                                <input list="designer-options" name="designer" placeholder="Enter Name"
                                    class="w-full border rounded text-gray-600 p-2 bg-slate-200" required>
                                <datalist id="designer-options">
                                    @foreach ($designers as $designer)
                                        <option value="{{ $designer->id }}">{{ $designer->name }}</option>
                                    @endforeach
                                </datalist>
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
                                    <label for="pay_per_operator-1" class="text-gray-600 font-light text-sm">Pay per
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

    {{-- Script Modal Update --}}
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

                    // Mengisi form dengan data dari tombol yang diklik
                    approveForm.action =
                        `/design-requests/approve/${designRequestId}`; // Set action form
                    approveForm.querySelector('input[name="customer"]').value = customer;
                    approveForm.querySelector('img#userProfileImage-1').src = `/${referenceImage}`;
                    approveForm.querySelector('input[name="name"]').value = name;
                    approveForm.querySelector('input[name="sizeW"]').value = "Width : " + width;
                    approveForm.querySelector('input[name="sizeH"]').value = "Height : " + height;
                    approveForm.querySelector('input[name="total_pieces"]').value = totalPieces;
                    approveForm.querySelector('textarea[name="description"]').value = description;

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

    {{-- Script Modal Add --}}
    <script>
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
    </script>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const approveButtons = document.querySelectorAll('.approved-button');

            approveButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Ambil ID dari tombol yang diklik
                    const rowId = this.getAttribute('data-id');

                    // Temukan modal dan teks yang sesuai dengan ID row
                    const approvalModal = document.getElementById(`approvalModal-${rowId}`);
                    const approvalText = document.getElementById(`approvalText-${rowId}`);

                    // Tampilkan modal dengan animasi fade-in dan translate dari bawah ke atas
                    approvalText.classList.remove('opacity-0', '-translate-y-4');
                    approvalText.classList.add('opacity-100', 'translate-y-0');
                    approvalModal.classList.remove('hidden');
                    approvalModal.classList.add('flex');

                    // Hilangkan modal setelah 2 detik dengan efek naik ke atas dan fade-out
                    setTimeout(() => {
                        approvalText.classList.remove('opacity-100', 'translate-y-0');
                        approvalText.classList.add('opacity-0', '-translate-y-4');
                        setTimeout(() => {
                            approvalModal.classList.remove('flex');
                            approvalModal.classList.add('hidden');
                        }, 1000); // Durasi animasi transisi 500ms
                    }, 1500); // Modal akan ditampilkan selama 2 detik
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
