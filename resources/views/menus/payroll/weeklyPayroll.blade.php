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
        <div>
            <div class="flex flex-col ml-8">
                <div class="flex">
                    <button id="work" class="ml-1.5 mr-4 text-blue-500 font-semibold">
                        @if (Auth()->User()->hasRole('admin'))
                            Unpaid Payroll
                        @else
                            My Salary
                        @endif
                    </button>
                    <button id="done" class="mr-4">
                        @if (Auth()->User()->hasRole('admin'))
                            Paid Payroll
                        @else
                            History Salaries
                        @endif
                    </button>
                </div>
                <!-- Tambahkan hr setelah button pertama -->
                <hr id="underline" class="ml-1 w-0 border-blue-500 mb-3"
                    style="border-width: 1.5px; transition: 0.5s ease;">
            </div>
            <div id="unpaidPayroll" class="max-w-7xl mx-auto sm:px-6 lg:px-8 transition-all duration-500 opacity-100">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                    <div class="flex justify-between items-center mb-4">
                        <div class="flex">
                            <h3 class="text-lg font-medium pt-1 mr-3">
                                @if (Auth()->User()->hasRole('admin'))
                                    Weekly Payrolls
                                @else
                                    My Salary
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
                    @if ($unpaidWeeklyPayrolls->isEmpty())
                        <p>No design request found.</p>
                    @else
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-11 text-left">NO</th>
                                    <th class="py-3 px-6 text-left">Employee</th>
                                    <th class="py-3 px-6 text-left">Week Date Range</th>
                                    <th class="py-3 px-6 text-left">Total Pay</th>
                                    <th class="py-3 px-6 text-left">Status</th>
                                    <th class="py-3 px-6 text-center text-sm font-bold opacity-60">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($unpaidWeeklyPayrolls as $weeklyPayroll)
                                    <tr class="border-b border-gray-200 hover:bg-gray-100" data-expense>
                                        <td class="flex-nowrap text-nowrap py-3 px-6 text-left">
                                            <i class="bx bx-chevron-right cursor-pointer"></i>
                                            <!-- Tombol chevron di sini -->
                                            <i class="bx bx-archive mr-3 opacity-50"></i>
                                            {{ $loop->iteration }}.
                                        </td>
                                        <td class="py-3 px-6 text-left">{{ $weeklyPayroll->employee->name }}</td>
                                        <td class="py-3 px-6 text-left">
                                            {{ $weeklyPayroll->week_start_date->format('d M Y') }} -
                                            {{ $weeklyPayroll->week_end_date->format('d M Y') }}
                                        </td>
                                        <td class="py-3 px-6 text-left">{{ $weeklyPayroll->weekly_total_pay }}</td>
                                        <td class="py-3 px-6 text-left">
                                            {{ $weeklyPayroll->paid ? 'Paid' : 'Unpaid' }}
                                        </td>
                                        <td class="py-3 px-6 text-center">
                                            <div class="flex item-center justify-center">
                                                <a href="#" data-id="{{ $weeklyPayroll->id }}"
                                                    data-employeeName="{{ $weeklyPayroll->employee->name }}"
                                                    data-employeeRole="{{ $weeklyPayroll->employee->roles && $weeklyPayroll->employee->roles->first() ? $weeklyPayroll->employee->roles->first()->name : 'Unset' }}"
                                                    data-total="{{ 'Rp'.number_format($weeklyPayroll->weekly_total_pay, 0, ',', '.') }}"
                                                    class="pay-button w-4 mr-2 scale-125 opacity-75 transform hover:text-green-500 hover:scale-150 transition duration-75">
                                                    <i class="bx bx-money-withdraw"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr style="display: none;">
                                        <td colspan="5">
                                            <table class="nested-table w-full mt-2 ml-12 ">
                                                <thead>
                                                    <tr class="nested-expense">
                                                        <th class="py-3 px-6 text-left text-sm font-bold opacity-60">No
                                                        </th>
                                                        <th class="py-3 px-6 text-left text-sm font-bold opacity-60">
                                                            Daily Payroll</th>
                                                        <th class="py-3 px-6 text-left text-sm font-bold opacity-60">
                                                            Subtotal
                                                            Pay</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($weeklyPayroll->weeklyPayrollDetail as $detail)
                                                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                                                            <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                                <ol>
                                                                    <li>{{ chr(97 + $loop->index) }}.</li>
                                                                </ol>
                                                            </td>
                                                            <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                                {{ $detail->dailyPayrollHeader->created_at->format('d M Y') }}
                                                            </td>
                                                            <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                                {{ $detail->subtotal_pay }}</td>
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
            <div id="paidPayroll"
                class="max-w-7xl mx-auto sm:px-6 lg:px-8 transition-all duration-500 opacity-100 hidden">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                    <div class="flex justify-between items-center mb-4">
                        <div class="flex">
                            <h3 class="text-lg font-medium pt-1 mr-3">
                                @if (Auth()->User()->hasRole('admin'))
                                    Weekly Payrolls
                                @else
                                    History Salary
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
                    @if ($paidWeeklyPayrolls->isEmpty())
                        <p>No design request found.</p>
                    @else
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-11 text-left">NO</th>
                                    <th class="py-3 px-6 text-left">Employee</th>
                                    <th class="py-3 px-6 text-left">Week Date Range</th>
                                    <th class="py-3 px-6 text-left">Total Paid</th>
                                    <th class="py-3 px-6 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($paidWeeklyPayrolls as $weeklyPayroll)
                                    <tr class="border-b border-gray-200 hover:bg-gray-100" data-expense>
                                        <td class="py-3 px-6 text-left">
                                            <i class="bx bx-chevron-right cursor-pointer"></i>
                                            <!-- Tombol chevron di sini -->
                                            <i class="bx bx-archive mr-3 opacity-50"></i>
                                            {{ $loop->iteration }}.
                                        </td>
                                        <td class="py-3 px-6 text-left">{{ $weeklyPayroll->employee->name }}</td>
                                        <td class="py-3 px-6 text-left">
                                            {{ $weeklyPayroll->week_start_date->format('d M Y') }} -
                                            {{ $weeklyPayroll->week_end_date->format('d M Y') }}
                                        </td>
                                        <td class="py-3 px-6 text-left">{{ $weeklyPayroll->weekly_total_pay }}</td>
                                        <td class="py-3 px-6 text-left">
                                            {{ $weeklyPayroll->paid ? 'Paid' : 'Unpaid' }}
                                        </td>
                                    </tr>
                                    <tr style="display: none;">
                                        <td colspan="5">
                                            <table class="nested-table w-full mt-2 ml-12 ">
                                                <thead>
                                                    <tr class="nested-expense">
                                                        <th class="py-3 px-6 text-left text-sm font-bold opacity-60">No
                                                        </th>
                                                        <th class="py-3 px-6 text-left text-sm font-bold opacity-60">
                                                            Daily Payroll</th>
                                                        <th class="py-3 px-6 text-left text-sm font-bold opacity-60">
                                                            Subtotal
                                                            Pay</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($weeklyPayroll->weeklyPayrollDetail as $detail)
                                                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                                                            <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                                <ol>
                                                                    <li>{{ chr(97 + $loop->index) }}.</li>
                                                                </ol>
                                                            </td>
                                                            <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                                {{ $detail->dailyPayrollHeader->created_at->format('d M Y') }}
                                                            </td>
                                                            <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                                {{ $detail->subtotal_pay }}</td>
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
    </div>

    {{-- Modal Pay Salary --}}
    <div id="payModal" class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-4xl w-auto h-auto">
            <div class="flex items-center justify-between">
                <i id="closeWorkModal"
                    class="bx bx-arrow-back scale-125 font-extrabold mb-4 cursor-pointer hover:scale-150"></i>
                <h2 class="text-xl font-bold mb-4 pr-4 mx-auto">Salary Payment</h2>
            </div>
            <form id="workForm" action="{{ route('weeklypayroll.pay') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="weeklyHeaderId">
                <div class="request-container" style="max-height: 86vh">
                    <div class="flex p-3 pb-5" id="request-1">
                        <img id="requestPic" src="{{ asset('assets/images/webcam-toy-photo2 (1).jpg') }}"
                            alt="User Profile" class="w-48 h-48 mr-3 rounded-2xl object-cover">
                        <div class="p-3">
                            <div class="w-full">
                                <label for="employeeName" class="text-gray-700 font-normal text-sm">Employee Name :
                                </label>
                                <p id="employeeName" class="text-black text-lg font-semibold">Huwa Keren</p>
                                <hr>
                            </div>
                            <div class="w-full">
                                <label for="employeeRole" class="text-gray-700 font-normal text-sm">Employee Role :
                                </label>
                                <p id="employeeRole" class="text-black text-lg font-semibold">Designer</p>
                                <hr>
                            </div>
                            <div class="w-full">
                                <label for="requestDesc" class="text-gray-700 font-normal text-sm">Payroll :</label>
                                <div class="max-h-64 overflow-y-scroll">
                                    <table class="w-full text-left my-1 max-h-4 overflow-y-scroll">
                                        <tbody>
                                            <tr class="border-b">
                                                <td class="px-2 py-1 text-gray-600"><i
                                                        class="bx bx-chevron-right scale-150 mr-2 cursor-pointer"></i><i
                                                        class="bx bx-spreadsheet scale-150"></i></td>
                                                <td class="text-black text-lg font-normal">Sunday</td>
                                                <td class="text-black text-lg font-light pl-6" id="dateSunday">1 Nov,
                                                    2024</td>
                                                <td class="text-black text-lg font-semibold pl-6" id="subtotalSunday">
                                                    Rp100.000,00</td>
                                            </tr>
                                            <tr style="display: none">
                                                <td colspan="3" class="px-4 py-2">
                                                    <div class="flex flex-col">
                                                        <div id="">
                                                            <div class="flex text-gray-700 text-sm my-1">
                                                                <div class="flex">
                                                                    <div class="flex items-center">
                                                                        <span class="font-semibold mr-2">1)</span>
                                                                    </div>
                                                                    <div class="flex flex-col">
                                                                        <span class="font-semibold mr-2">Request</span>
                                                                        <span class="font-semibold mr-2">Pay/Pcs</span>
                                                                        <span class="font-semibold mr-2">Qty</span>
                                                                    </div>
                                                                </div>
                                                                <div class="flex flex-col">
                                                                    <span>: Logo blablabla</span>
                                                                    <span>: Rp10.000</span>
                                                                    <span>: 10 Pcs</span>
                                                                </div>
                                                            </div>
                                                            <hr class="w-60">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="border-b">
                                                <td class="px-2 py-1 text-gray-600"><i
                                                        class="bx bx-chevron-right scale-150 mr-2 cursor-pointer"></i><i
                                                        class="bx bx-spreadsheet scale-150"></i></td>
                                                <td class="text-black text-lg font-normal">Monday</td>
                                                <td class="text-black text-lg font-light pl-6" id="dateMonday">2 Nov,
                                                    2024</td>
                                                <td class="text-black text-lg font-semibold pl-6" id="subtotalMonday">
                                                    Rp100.000,00</td>
                                            </tr>
                                            <tr style="display: none">
                                                <td colspan="3" class="px-4 py-2">
                                                    <div class="flex flex-col">
                                                        <div id="">
                                                            <div class="flex text-gray-700 text-sm my-1">
                                                                <div class="flex">
                                                                    <div class="flex items-center">
                                                                        <span class="font-semibold mr-2">1)</span>
                                                                    </div>
                                                                    <div class="flex flex-col">
                                                                        <span class="font-semibold mr-2">Request</span>
                                                                        <span class="font-semibold mr-2">Pay/Pcs</span>
                                                                        <span class="font-semibold mr-2">Qty</span>
                                                                    </div>
                                                                </div>
                                                                <div class="flex flex-col">
                                                                    <span>: Logo blablabla</span>
                                                                    <span>: Rp10.000</span>
                                                                    <span>: 10 Pcs</span>
                                                                </div>
                                                            </div>
                                                            <hr class="w-60">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="border-b">
                                                <td class="px-2 py-1 text-gray-600"><i
                                                        class="bx bx-chevron-right scale-150 mr-2 cursor-pointer"></i><i
                                                        class="bx bx-spreadsheet scale-150"></i></td>
                                                <td class="text-black text-lg font-normal">Tuesday</td>
                                                <td class="text-black text-lg font-light pl-6" id="dateTuesday">3 Nov,
                                                    2024</td>
                                                <td class="text-black text-lg font-semibold pl-6"
                                                    id="subtotalTuesday">Rp100.000,00</td>
                                            </tr>
                                            <tr style="display: none">
                                                <td colspan="3" class="px-4 py-2">
                                                    <div class="flex flex-col">
                                                        <div id="">
                                                            <div class="flex text-gray-700 text-sm my-1">
                                                                <div class="flex">
                                                                    <div class="flex items-center">
                                                                        <span class="font-semibold mr-2">1)</span>
                                                                    </div>
                                                                    <div class="flex flex-col">
                                                                        <span class="font-semibold mr-2">Request</span>
                                                                        <span class="font-semibold mr-2">Pay/Pcs</span>
                                                                        <span class="font-semibold mr-2">Qty</span>
                                                                    </div>
                                                                </div>
                                                                <div class="flex flex-col">
                                                                    <span>: Logo blablabla</span>
                                                                    <span>: Rp10.000</span>
                                                                    <span>: 10 Pcs</span>
                                                                </div>
                                                            </div>
                                                            <hr class="w-60">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="border-b">
                                                <td class="px-2 py-1 text-gray-600"><i
                                                        class="bx bx-chevron-right scale-150 mr-2 cursor-pointer"></i><i
                                                        class="bx bx-spreadsheet scale-150"></i></td>
                                                <td class="text-black text-lg font-normal">Wednesday</td>
                                                <td class="text-black text-lg font-light pl-6" id="dateWednesday">4
                                                    Nov, 2024</td>
                                                <td class="text-black text-lg font-semibold pl-6"
                                                    id="subtotalWednesday">Rp100.000,00</td>
                                            </tr>
                                            <tr style="display: none">
                                                <td colspan="3" class="px-4 py-2">
                                                    <div class="flex flex-col">
                                                        <div id="">
                                                            <div class="flex text-gray-700 text-sm my-1">
                                                                <div class="flex">
                                                                    <div class="flex items-center">
                                                                        <span class="font-semibold mr-2">1)</span>
                                                                    </div>
                                                                    <div class="flex flex-col">
                                                                        <span class="font-semibold mr-2">Request</span>
                                                                        <span class="font-semibold mr-2">Pay/Pcs</span>
                                                                        <span class="font-semibold mr-2">Qty</span>
                                                                    </div>
                                                                </div>
                                                                <div class="flex flex-col">
                                                                    <span>: Logo blablabla</span>
                                                                    <span>: Rp10.000</span>
                                                                    <span>: 10 Pcs</span>
                                                                </div>
                                                            </div>
                                                            <hr class="w-60">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="border-b">
                                                <td class="px-2 py-1 text-gray-600"><i
                                                        class="bx bx-chevron-right scale-150 mr-2 cursor-pointer"></i><i
                                                        class="bx bx-spreadsheet scale-150"></i></td>
                                                <td class="text-black text-lg font-normal">Thursday</td>
                                                <td class="text-black text-lg font-light pl-6" id="dateThursday">5
                                                    Nov, 2024</td>
                                                <td class="text-black text-lg font-semibold pl-6"
                                                    id="subtotalThursday">Rp100.000,00</td>
                                            </tr>
                                            <tr style="display: none">
                                                <td colspan="3" class="px-4 py-2">
                                                    <div class="flex flex-col">
                                                        <div id="">
                                                            <div class="flex text-gray-700 text-sm my-1">
                                                                <div class="flex">
                                                                    <div class="flex items-center">
                                                                        <span class="font-semibold mr-2">1)</span>
                                                                    </div>
                                                                    <div class="flex flex-col">
                                                                        <span class="font-semibold mr-2">Request</span>
                                                                        <span class="font-semibold mr-2">Pay/Pcs</span>
                                                                        <span class="font-semibold mr-2">Qty</span>
                                                                    </div>
                                                                </div>
                                                                <div class="flex flex-col">
                                                                    <span>: Logo blablabla</span>
                                                                    <span>: Rp10.000</span>
                                                                    <span>: 10 Pcs</span>
                                                                </div>
                                                            </div>
                                                            <hr class="w-60">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="border-b">
                                                <td class="px-2 py-1 text-gray-600"><i
                                                        class="bx bx-chevron-right scale-150 mr-2 cursor-pointer"></i><i
                                                        class="bx bx-spreadsheet scale-150"></i></td>
                                                <td class="text-black text-lg font-normal">Friday</td>
                                                <td class="text-black text-lg font-light pl-6" id="dateFriday">6 Nov,
                                                    2024</td>
                                                <td class="text-black text-lg font-semibold pl-6" id="subtotalFriday">
                                                    Rp100.000,00</td>
                                            </tr>
                                            <tr style="display: none">
                                                <td colspan="3" class="px-4 py-2">
                                                    <div class="flex flex-col">
                                                        <div id="">
                                                            <div class="flex text-gray-700 text-sm my-1">
                                                                <div class="flex">
                                                                    <div class="flex items-center">
                                                                        <span class="font-semibold mr-2">1)</span>
                                                                    </div>
                                                                    <div class="flex flex-col">
                                                                        <span class="font-semibold mr-2">Request</span>
                                                                        <span class="font-semibold mr-2">Pay/Pcs</span>
                                                                        <span class="font-semibold mr-2">Qty</span>
                                                                    </div>
                                                                </div>
                                                                <div class="flex flex-col">
                                                                    <span>: Logo blablabla</span>
                                                                    <span>: Rp10.000</span>
                                                                    <span>: 10 Pcs</span>
                                                                </div>
                                                            </div>
                                                            <hr class="w-60">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="border-b">
                                                <td class="px-2 py-1 text-gray-600"><i
                                                        class="bx bx-chevron-right scale-150 mr-2 cursor-pointer"></i><i
                                                        class="bx bx-spreadsheet scale-150"></i></td>
                                                <td class="text-black text-lg font-normal">Saturday</td>
                                                <td class="text-black text-lg font-light pl-6" id="dateSaturday">7
                                                    Nov, 2024</td>
                                                <td class="text-black text-lg font-semibold pl-6"
                                                    id="subtotalSaturday">Rp100.000,00</td>
                                            </tr>
                                            <tr style="display: none">
                                                <td colspan="3" class="px-4 py-2">
                                                    <div class="flex flex-col">
                                                        <div id="">
                                                            <div class="flex text-gray-700 text-sm my-1">
                                                                <div class="flex">
                                                                    <div class="flex items-center">
                                                                        <span class="font-semibold mr-2">1)</span>
                                                                    </div>
                                                                    <div class="flex flex-col">
                                                                        <span class="font-semibold mr-2">Request</span>
                                                                        <span class="font-semibold mr-2">Pay/Pcs</span>
                                                                        <span class="font-semibold mr-2">Qty</span>
                                                                    </div>
                                                                </div>
                                                                <div class="flex flex-col">
                                                                    <span>: Logo blablabla</span>
                                                                    <span>: Rp10.000</span>
                                                                    <span>: 10 Pcs</span>
                                                                </div>
                                                            </div>
                                                            <hr class="w-60">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="w-full my-1">
                                <label for="totalSalary" class="text-gray-700 font-normal text-sm">Total Salary :
                                </label>
                                <p id="totalSalary" class="text-black text-lg font-semibold">Rp700.000,00
                                </p>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="bg-blue-500 text-white font-bold py-2 px-6 rounded hover:bg-blue-600 transition duration-200">Pay</button>
                </div>
            </form>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <script>
        document.querySelectorAll('.bx-chevron-right').forEach(chevron => {
            chevron.addEventListener('click', function() {
                const nextRow = this.closest('tr').nextElementSibling;

                if (nextRow.style.display === 'none' || !nextRow.style.display) {
                    nextRow.style.display = 'table-row';
                    this.classList.replace('bx-chevron-right',
                        'bx-chevron-down');
                } else {
                    nextRow.style.display = 'none';
                    this.classList.replace('bx-chevron-down',
                        'bx-chevron-right');
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('tr[data-expense]');

            rows.forEach(row => {
                const arrowIcon = row.querySelector('.bx');
                const viewButton = row.querySelector('.pay-button');

                row.addEventListener('click', function() {
                    const nestedTable = this
                        .nextElementSibling;

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
                viewButton.addEventListener('click', function(event) {
                    event.stopPropagation();
                    console.log('Payment button clicked');
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const underLine = document.getElementById('underline');
            const workButton = document.getElementById('work');
            const doneButton = document.getElementById('done');
            const workTable = document.getElementById('unpaidPayroll');
            const doneTable = document.getElementById('paidPayroll');

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

    {{-- Script Pay Weekly Payroll --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const approveButtons = document.querySelectorAll('.pay-button');
            const payModal = document.getElementById('payModal');
            const closeWorkModal = document.getElementById('closeWorkModal');
            const workForm = document.getElementById('workForm');

            const resetDates = () => {
                const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                days.forEach(day => {
                    const dateElement = document.getElementById(`date${day}`);
                    if (dateElement) {
                        dateElement.textContent = 'd M, Y';
                    }
                });
            };

            approveButtons.forEach(button => {
                button.addEventListener('click', async function() {
                    try {
                        const weeklyHeaderId = this.getAttribute('data-id');
                        if (!weeklyHeaderId) {
                            throw new Error('Weekly header ID is missing');
                        }
                        const employeeName = this.getAttribute('data-employeeName');
                        const employeeRole = this.getAttribute('data-employeeRole');
                        const total = this.getAttribute('data-total');

                        workForm.querySelector('#employeeName').textContent = employeeName;
                        workForm.querySelector('#employeeRole').textContent = employeeRole;
                        workForm.querySelector('#totalSalary').textContent = total;

                        resetDates();

                        payModal.classList.remove('hidden');
                        payModal.classList.add('flex');

                        const response = await fetch(
                            `/payroll/weekly-payroll/getSalary/${weeklyHeaderId}`);

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const result = await response.json();

                        if (result.status === 'success') {
                            const {
                                dates
                            } = result.data;

                            Object.entries(dates).forEach(([day, dateInfo]) => {
                                const dateElement = document.getElementById(
                                    `date${day}`);
                                const subtotalPayElement = document.getElementById(
                                    `subtotal${day}`);

                                if (dateElement) {
                                    dateElement.textContent = dateInfo.formatted_date;
                                }

                                if (subtotalPayElement) {
                                    subtotalPayElement.textContent = dateInfo.subtotal_pay !== null ? dateInfo.subtotal_pay : 0;
                                }
                            });
                        } else {
                            throw new Error(result.message || 'Failed to retrieve dates');
                        }


                    } catch (error) {
                        console.error('Error:', error);
                        alert(error.message);
                        payModal.classList.add('hidden');
                        payModal.classList.remove('flex');
                    }
                });
            });

            closeWorkModal.addEventListener('click', () => {
                payModal.classList.add('hidden');
                payModal.classList.remove('flex');
            });

            payModal.addEventListener('click', (e) => {
                if (e.target === payModal) {
                    payModal.classList.add('hidden');
                    payModal.classList.remove('flex');
                }
            });
        });
    </script>

</x-app-layout>
