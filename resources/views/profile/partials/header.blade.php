<header class="bg-white shadow-sm sticky top-0 " style="z-index: 29">
    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <!-- Greeting Section -->
        <div class="flex">
            <h1 class="text-2xl pl-6 font-semibold text-gray-900">Alo, {{ Auth::user()->name }} 👋</h1>
        </div>

        <!-- Search, Notification, Profile Section -->
        <div class="flex items-center space-x-6">
            <!-- Search Bar -->
            <div class="relative">
                <input type="text" placeholder="Search"
                    class="w-64 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                <svg class="w-5 h-5 text-gray-500 absolute right-3 top-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            <!-- Notification Icon -->
            <button class="relative">
                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9">
                    </path>
                </svg>
                <!-- Notification Badge -->
                <span
                    class="absolute top-2 right-2 inline-block w-2 h-2 transform translate-x-1 -translate-y-1 bg-red-600 rounded-full"></span>
            </button>

            <!-- Add New Button -->
            {{-- <button class="bg-gray-200 p-2 rounded-full">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </button> --}}

            <!-- Profile Dropdown with Alpine.js -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center focus:outline-none">
                    <img class="w-10 h-10 rounded-full" src="{{ asset('assets/images/IMG_3910.JPG') }}" alt="Profile">
                </button>
                <!-- Dropdown Menu -->
                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-50">
                    <a href="{{ route('profile.edit') }}"
                        class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Profile</a>
                    <a href="" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Settings</a>
                    <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
