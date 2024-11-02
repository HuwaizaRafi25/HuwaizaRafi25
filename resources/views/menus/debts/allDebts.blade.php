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
                            Ongoing Debts
                        @else
                            My Debts
                        @endif
                    </button>
                    <button id="done" class="mr-4">
                        @if (Auth()->User()->hasRole('admin'))
                            Accomplished Debts
                        @else
                            Debts History
                        @endif
                    </button>
                </div>
                <!-- Tambahkan hr setelah button pertama -->
                <hr id="underline" class="ml-1 w-0 border-blue-500 mb-3"
                    style="border-width: 1.5px; transition: 0.5s ease;">
            </div>
            <div id="debts" class="max-w-7xl mx-auto sm:px-6 lg:px-8 transition-all duration-500 opacity-100">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                    <div class="flex justify-between items-center mb-4">
                        <div class="flex">
                            <h3 class="text-lg font-medium pt-1 mr-3">
                                @if (Auth()->User()->hasRole('admin'))
                                    Customer Debts
                                @else
                                    My Debts
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

                    @if ($debts->isEmpty())
                        <p>No Debts found.</p>
                    @else
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-11 text-left">NO</th>
                                    <th class="py-3 px-6 text-left">Customer</th>
                                    <th class="py-3 px-6 text-left">Dates</th>
                                    <th class="py-3 px-6 text-left">Status</th>
                                    <th class="py-3 px-6 text-left">Details</th>
                                    <th class="py-3 px-6 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($debts as $debt)
                                    <tr class="border-b border-gray-200 hover:bg-gray-100" data-expense>
                                        <td class="py-3 px-6 text-left">
                                            <i class="bx bx-chevron-right cursor-pointer"></i>
                                            <i class="bx bx-archive mr-3 opacity-50"></i>
                                            {{ $loop->iteration }}.
                                        </td>
                                        <td class="py-3 px-6 text-left">{{ $debt->customer->name }}</td>
                                        <td class="py-3 px-6 text-left">
                                            <div>
                                                <p class="text-sm opacity-65">Transaction Date :</p>
                                                {{ $debt->created_at->format('d M, Y') }}
                                            </div>
                                            <div>
                                                <p class="text-sm opacity-65">Due Date :</p>
                                                {{ $debt->due_date->format('d M, Y') }}
                                            </div>
                                        </td>
                                        <td class="py-3 px-6 text-left">
                                            <div class="gap-2 flex flex-col">
                                                <div>
                                                    <p class="text-sm opacity-65">Total Debt :</p>
                                                    {{ 'Rp' . number_format($debt->total_debt, 2, ',', '.') }}
                                                </div>
                                                <div>
                                                    <p class="text-sm opacity-65">Paid Debt :</p>
                                                    {{ 'Rp' . number_format($debt->debtPayment->sum('payment_amount'), 2, ',', '.') }}
                                                </div>
                                                <div>
                                                    <p class="text-sm opacity-65">Remaining Debt :</p>
                                                    {{ 'Rp' . number_format($debt->total_debt - $debt->debtPayment->sum('payment_amount'), 2, ',', '.') }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6 text-left">{{ $debt->status }}</td>
                                        <td class="py-3 px-6 text-center">
                                            <div class="flex item-center justify-center">
                                                <a href="#" data-id="{{ $debt->id }}"
                                                    data-customerName="{{ $debt->customer->name }}"
                                                    data-customerEmail="{{ $debt->customer->email }}"
                                                    data-customerPic="{{ $debt->customer->profile_picture }}"
                                                    data-maxValue="{{ $debt->total_debt - $debt->debtPayment->sum('payment_amount') }}"
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
                                                            Item Name</th>
                                                        <th class="py-3 px-6 text-left text-sm font-bold opacity-60">
                                                            Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($debt->debtPayment as $payment)
                                                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                                                            <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                                <ol>
                                                                    <li>{{ chr(97 + $loop->index) }}.</li>
                                                                </ol>
                                                            </td>
                                                            <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                                {{ $payment->created_at }}</td>
                                                            <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                                {{ 'Rp' . number_format($payment->payment_amount, 2, ',', '.') }}
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
            <div id="accomplishedDebts"
                class="max-w-7xl mx-auto sm:px-6 lg:px-8 transition-all transform ease-in-out duration-500 opacity-0 hidden">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex">

                            <h3 class="text-lg font-medium pt-1 mr-3">
                                @if (Auth()->User()->hasRole('admin'))
                                    Accomplished Debts
                                @else
                                    Debts History
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

                    @if ($paidDebts->isEmpty())
                        <p>No Debts found.</p>
                    @else
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-11 text-left">NO</th>
                                    <th class="py-3 px-6 text-left">Customer</th>
                                    <th class="py-3 px-6 text-left">Details</th>
                                    <th class="py-3 px-6 text-left">Dates</th>
                                    <th class="py-3 px-6 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($paidDebts as $debt)
                                    <tr class="border-b border-gray-200 hover:bg-gray-100" data-expense>
                                        <td class="py-3 px-6 text-left">
                                            <i class="bx bx-chevron-right cursor-pointer"></i>
                                            <i class="bx bx-archive mr-3 opacity-50"></i>
                                            {{ $loop->iteration }}.
                                        </td>
                                        <td class="py-3 px-6 text-left">{{ $debt->customer->name }}</td>
                                        <td class="py-3 px-6 text-left">
                                            <div>
                                                <p class="text-sm opacity-65">Total Debt :</p>
                                                {{ 'Rp' . number_format($debt->total_debt, 2, ',', '.') }}
                                            </div>
                                            <div>
                                                <p class="text-sm opacity-65">Paid Debt :</p>
                                                {{ 'Rp' . number_format($debt->debtPayment->sum('payment_amount'), 2, ',', '.') }}
                                            </div>
                                        </td>
                                        <td class="py-3 px-6 text-left">
                                            <div>
                                                <p class="text-sm opacity-65">Transaction Date :</p>
                                                {{ $debt->created_at->format('d M, Y') }}
                                            </div>
                                            <div>
                                                <p class="text-sm opacity-65">Repaid Date :</p>
                                                {{ $debt->updated_at->format('d M, Y') }}
                                            </div>
                                        </td>
                                        <td class="py-3 px-6 text-left">{{ $debt->status }}</td>
                                    </tr>
                                    <tr style="display: none;">
                                        <td colspan="5">
                                            <table class="nested-table w-full mt-2 ml-12 ">
                                                <thead>
                                                    <tr class="nested-expense">
                                                        <th class="py-3 px-6 text-left text-sm font-bold opacity-60">No
                                                        </th>
                                                        <th class="py-3 px-6 text-left text-sm font-bold opacity-60">
                                                            Item Name</th>
                                                        <th class="py-3 px-6 text-left text-sm font-bold opacity-60">
                                                            Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($debt->debtPayment as $payment)
                                                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                                                            <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                                <ol>
                                                                    <li>{{ chr(97 + $loop->index) }}.</li>
                                                                </ol>
                                                            </td>
                                                            <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                                {{ $payment->created_at }}</td>
                                                            <td class="py-3 px-6 text-left text-gray-600 text-sm">
                                                                {{ 'Rp' . number_format($payment->payment_amount, 2, ',', '.') }}
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
    </div>

    <!-- Modal Work Qc Ops -->
    <div id="workModal" class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full h-auto">
            <div class="flex items-center justify-between shadow-md">
                <i id="closeWorkModal"
                    class="bx bx-arrow-back scale-125 font-extrabold mb-4 cursor-pointer hover:scale-150"></i>
                <h2 class="text-xl font-bold mb-4 pr-4 mx-auto underline">Debt Payment</h2>
            </div>
            <form id="workForm" action="{{ route('debt.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="request-container overflow-y-scroll h-auto">
                    <input type="hidden" name="debtId">
                    <div class="flex flex-col p-3" id="request-1">
                        <!-- Customer -->
                        <label for="customer" class="text-gray-600 font-light text-sm">Customer</label>
                        <div class="mt-4 flex items-center gap-2">
                            <div>
                                <img id="customerPic" src="{{ asset('images/profiles/sigue.jpg') }}"
                                    alt="customerPic" class="w-8 h-8 object-cover rounded-full">
                            </div>
                            <div>
                                <h4 class="font-semibold" id="customerName">Inyod</h4>
                                <h6 class="text-sm font-medium text-gray-600" id="customerEmail">inyod25@gmail.com
                                </h6>
                            </div>
                        </div>

                        <!-- Remaining Debt -->
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mt-4">
                            <div>
                                <label for="pay_Amount" class="text-gray-600 font-light text-sm">Pay Amount</label>
                                <input type="number" min="1" name="pay_Amount"
                                    placeholder="Enter Pay Amount"
                                    class="w-full border rounded p-2 focus:outline-none focus:border-blue-500"
                                    required>
                            </div>
                        </div>
                        <div class="flex">
                            <p class="font-light">Remaining Debt : </p>
                            <p class="pl-2" id="maxValue"></p>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Menangkap semua baris yang berisi data expense
            const rows = document.querySelectorAll('tr[data-expense]');

            // Menambahkan event listener untuk setiap baris
            rows.forEach(row => {
                const arrowIcon = row.querySelector('.bx');
                const viewButton = row.querySelector('.pay-button');

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
                viewButton.addEventListener('click', function(event) {
                    event.stopPropagation();
                    // Add payment handling logic here, if needed
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
            const workTable = document.getElementById('debts');
            const doneTable = document.getElementById('accomplishedDebts');

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
            const approveButtons = document.querySelectorAll('.pay-button');
            const workModal = document.getElementById('workModal');
            const closeWorkModal = document.getElementById('closeWorkModal');
            const workForm = document.getElementById('workForm');

            approveButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const debtId = this.getAttribute('data-id');
                    const customerName = this.getAttribute('data-customerName');
                    const customerEmail = this.getAttribute('data-customerEmail');
                    const customerPic = this.getAttribute('data-customerPic');
                    const maxValue = this.getAttribute('data-maxValue');
                    console.log(maxValue);

                    workForm.querySelector('img#customerPic').src = `/${customerPic}`;
                    workForm.querySelector('#customerName').textContent = customerName;
                    workForm.querySelector('#customerEmail').textContent = customerEmail;
                    workForm.querySelector('input[name="debtId"]').value = debtId;
                    workForm.querySelector('input[name="pay_Amount"]').max = maxValue;
                    workForm.querySelector('#maxValue').textContent = maxValue;
                    workModal.classList.remove('hidden');
                    workModal.classList.add('flex');
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
</x-app-layout>
