<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black-800 leading-tight">
            {{ __('Dashboard') }}
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
        </ol>
    </nav>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-lg font-semibold">Total Orders</h2>
                    <p class="mt-2 text-3xl font-bold">124</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-lg font-semibold">Pending Orders</h2>
                    <p class="mt-2 text-3xl font-bold">24</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-lg font-semibold">Completed Orders</h2>
                    <p class="mt-2 text-3xl font-bold">100</p>
                </div>
            </div>
        </div>
    </div>
    <script>
        console.log(localStorage.getItem('data-navLink-id'));
    </script>
</x-app-layout>
