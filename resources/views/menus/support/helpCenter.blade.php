<!-- resources/views/user-management/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('help center') }}
        </h2>
    </x-slot>

    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 20 20">
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
                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Support</span>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 9 4-4-4-4" />
                    </svg>
                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Help Center</span>
                </div>
            </li>
        </ol>
    </nav>


    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div
                class="flex flex-col bg-white overflow-hidden shadow-xl p-6 font-bold text-lg text-gray-700 sm:rounded-lg">
                <h1>
                    How Can We Help You?
                </h1>
                <div class="relative w-full py-2" style="z-index: 2">
                    <i class="bx bx-search scale-150 absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500"
                        style="z-index: 1"></i>
                    <input type="text"
                        class="w-full font-light text-base py-3 rounded-lg border border-gray-300 bg-gray-100 text-gray-700 focus:outline-none focus:border-blue-500 focus:bg-white"
                        style="padding-left: 40px; z-index: 1" placeholder="Search help articles...">
                </div>
            </div>
            <div
                class="flex flex-col bg-white overflow-hidden shadow-xl p-6 mt-4 font-bold text-base text-gray-700 sm:rounded-lg">
                <h2>
                    Popular Topics
                </h2>

                <!-- Grid untuk card-card -->
                <div class="grid grid-cols-3 gap-6 mt-6">
                    <!-- Card 1 -->
                    <div
                        class="bg-gray-100 p-4 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition duration-300 ease-in-out">
                        <img src="{{ asset('assets/images/lg.png') }}" alt="Getting Started Image" class="w-full my-4 h-24 object-contain rounded-md opacity-80">
                        <h3 class="text-lg font-semibold mt-9">Getting Started</h3>
                        <p class="text-gray-600 mt-2 text-sm font-light">Find the basics on how to sign up, log in, and
                            navigate the app's key features.</p>
                    </div>
                    <!-- Card 2 -->
                    <div
                        class="bg-gray-100 p-4 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition duration-300 ease-in-out">
                        <img src="{{ asset('assets/images/art.png') }}"
                            alt="Design Management Image" class="w-full h-28 my-4 object-contain rounded-md opacity-90">
                        <h3 class="text-lg font-semibold mt-1">Design Management</h3>
                        <p class="text-gray-600 mt-2 text-sm font-light">Learn how to upload, edit, and manage your
                            embroidery designs efficiently.</p>
                    </div>
                    <!-- Card 3 -->
                    <div
                        class="bg-gray-100 p-4 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition duration-300 ease-in-out">
                        <img src="{{ asset('assets/images/icons8-store-setting-100.png') }}"
                            alt="Order Processing Image" class="w-full h-28 my-4 object-contain rounded-md">
                        <h3 class="text-lg font-semibold mt-4">Order Processing</h3>
                        <p class="text-gray-600 mt-2 text-sm font-light">Step-by-step guide on creating and managing
                            your embroidery orders.</p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-6 mt-6">
                    <!-- Card 4 -->
                    <div
                        class="bg-gray-100 p-4 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition duration-300 ease-in-out">
                        <img src="{{ asset('assets/images/accset.png') }}"
                            alt="Account Settings Image" class="w-full h-32 object-contain rounded-md">
                        <h3 class="text-lg font-semibold mt-4">Account Settings</h3>
                        <p class="text-gray-600 mt-2 text-sm font-light">Instructions on updating your user profile and
                            customizing your account settings.</p>
                    </div>
                    <!-- Card 5 -->
                    <div
                        class="bg-gray-100 p-4 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition duration-300 ease-in-out">
                        <img src="{{ asset('assets/images/purchase.png') }}"
                            alt="Pricing & Invoices Image" class="w-full h-32 object-contain rounded-md">
                        <h3 class="text-lg font-semibold mt-4">Pricing & Invoices</h3>
                        <p class="text-gray-600 mt-2 text-sm font-light">Information on pricing structure and how to
                            manage your invoices and payments.</p>
                    </div>
                    <!-- Card 6 -->
                    <div
                        class="bg-gray-100 p-4 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition duration-300 ease-in-out">
                        <img src="{{ asset('assets/images/support.png') }}"
                            alt="Customer Support Image" class="w-full h-32 object-contain rounded-md">
                        <h3 class="text-lg font-semibold mt-4">Customer Support</h3>
                        <p class="text-gray-600 mt-2 text-sm font-light">Get help by contacting our support team or find
                            quick solutions to common issues.</p>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
