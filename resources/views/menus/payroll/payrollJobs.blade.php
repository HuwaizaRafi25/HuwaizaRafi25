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
            {{ __('All Users') }}
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
                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">All Users</span>
                    </div>
                </li>
            </ol>
        </nav>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex">
                        <h3 class="text-lg font-medium pt-1 mr-3">
                            Expense Management
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

                @if ($payrollJobs->isEmpty())
                    <p>No users found.</p>
                @else
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">NO</th>
                                <th class="py-3 px-6 text-left">Design Request</th>
                                <th class="py-3 px-6 text-left">Pay Designer</th>
                                <th class="py-3 px-6 text-left">Pay Machine Ops</th>
                                <th class="py-3 px-6 text-left">Pay QC</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @foreach ($payrollJobs as $payrollJob)
                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                    {{-- data-profile-image="{{ $payrollJob->profile_picture }}" data-name="{{ $user->name }}"
                                    data-email="{{ $user->email }}" data-contacts="{{ $user->contact_info }}"
                                    data-address="{{ $user->address }}" data-id="{{ $user->id }}"> --}}
                                    <td class="py-3 px-6 text-left whitespace-nowrap">{{ $loop->iteration }}</td>
                                    <td class="py-3 px-6 text-left">
                                        {{ $payrollJob->designRequest->name }}
                                    </td>
                                    <td class="py-3 px-6 text-left">{{ $payrollJob->pay_designer }}</td>
                                    <td class="py-3 px-6 text-left">{{ $payrollJob->pay_machine_operator }}</td>
                                    <td class="py-3 px-6 text-left">{{ $payrollJob->pay_qc }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
