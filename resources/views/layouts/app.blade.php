<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    @notifyCss
    <style>
        ::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari, dan Edge */
        }

        /* Custom Scrollbar for request-container */
        .request-container::-webkit-scrollbar {
            display: block;
            width: 8px;
        }

        .request-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .request-container::-webkit-scrollbar-thumb {
            background-color: #888;
            border-radius: 10px;
        }

        .request-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        .request-container2::-webkit-scrollbar {
            display: block;
            width: 8px;
        }

        .request-container2::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .request-container2::-webkit-scrollbar-thumb {
            background-color: #888;
            border-radius: 10px;
        }

        .request-container2::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        .machineOps::-webkit-scrollbar {
            display: block;
            width: 8px;
        }

        .machineOps::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .machineOps::-webkit-scrollbar-thumb {
            background-color: #888;
            border-radius: 10px;
        }

        .machineOps::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Custom Scrollbar for request-container */
        .notifCard::-webkit-scrollbar {
            display: block;
            width: 8px;
        }

        .notifCard::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .notifCard::-webkit-scrollbar-thumb {
            background-color: #aaaaaa;
            border-radius: 10px;
        }

        .notifCard::-webkit-scrollbar-thumb:hover {
            background: #929292;
        }


        .notify {
            margin-top: 64px;
            /* Atau bisa menggunakan 8rem sesuai dengan ukuran yang diinginkan */
            z-index: 1000;
            /* Pastikan z-index cukup tinggi agar tidak tertutup */
        }
    </style>


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="icon" href="{{ asset('assets/images/lg.png') }}" type="image/x-icon">


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <!-- Sidebar -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <div id="notif"
        class="fixed inset-0 items-start pt-[74px] pr-24 bg-black bg-opacity-15 justify-end hidden z-50">
        <div class="bg-white opacity-100 w-96 rounded-md h-auto p-4">
            <div class="flex justify-between">
                <h2 class="font-semibold">Notifications</h2>
                <i id="closeNotif" class="bx bx-x scale-150 p-1 cursor-pointer"></i>
            </div>
            <hr class="mt-1">
            <div class="notifCard max-h-44 overflow-y-scroll ">
                @foreach ($allNotifications as $notification)
                    <a href="{{ route($notification->route) }}">
                        <div
                            class="flex py-3 px-4 mr-1 rounded-lg mt-2 justify-between hover:bg-blue-50 cursor-pointer">
                            <div class="flex">
                                <i
                                    class="{{ $notification->icon }} scale-125 mr-2 bg-blue-400 bg-opacity-50 rounded-lg p-1"></i>
                                <p>{{ $notification->massage }}</p>
                            </div>
                            @if ($notification->is_read == 0)
                                <div class="bg-red-500 h-2 w-2 rounded-full"></div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
            <hr class="my-1">
            <div class="flex justify-end items-center text-blue-500 mt-2 cursor-pointer">
                <i class="bx bx-check-double scale-150"></i>
                <h2 class="font-semibold pl-2">Mark all as read</h2>
            </div>
        </div>
    </div>


    <nav class="sidebar">
        <header class="pt-2 pb-4">
            <div class="image-text">
                <span class="image">
                    <img src="{{ asset('assets/images/LO-1.png') }}" alt="">
                </span>

                <div class="text logo-text">
                    <span class="name">Embroidery</span>
                    {{-- <span class="profession">Web developer</span> --}}
                </div>
                <i class="bx bx-menu toggle -mr-8 -mt-1 scale-150"></i>
            </div>
        </header>
        <hr>

        <div class="menu-bar">

            <!-- Dashboard -->
            <div class="menu">
                <li class="nav-link" data-navLink="1">
                    <div class="navhead">
                        <i class='bx bx-home-alt icon'></i>
                        <span class="text nav-text">Dashboard</span>
                    </div>
                </li>
            </div>


            @if (auth()->user()->hasRole('admin'))
                <!-- User Management -->
                <div class="menu">
                    <li class="nav-link">
                        <div class="navhead">
                            <i class='bx bx-user-circle icon'></i>
                            <div class="flex justify-between items-center">
                                <span class="text nav-text">User Management</span>
                                <i class="bx bx-chevron-down scale-125 opacity-85 icon transition-transform"></i>
                            </div>
                        </div>
                    </li>
                    <div class="submenu">
                        <ul class="submenu-links">
                            <li class="sub-nav-link">
                                <span class="text sub-nav-text" data-subNavText="1">All Users</span>
                            </li>
                            <li class="sub-nav-link">
                                <span class="text sub-nav-text" data-subNavText="2">Roles & Permissions</span>
                            </li>
                        </ul>
                    </div>
                </div>
            @endif

            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('customer'))
                <!-- Design Requests -->
                <div class="menu">
                    <li class="nav-link" data-navLink="2">
                        <div class="navhead">
                            <i class='bx bx-receipt icon'></i>
                            <span class="text nav-text">Design Requests</span>
                        </div>
                    </li>
                </div>
            @endif

            @if (auth()->user()->hasRole('designer'))
                <!-- Designs -->
                <div class="menu">
                    <li class="nav-link" data-navLink="3">
                        <div class="navhead">
                            <i class='bx bx-task icon'></i>
                            <span class="text nav-text">My Jobs</span>
                        </div>
                    </li>
                </div>
            @elseif (auth()->user()->hasRole('admin'))
                <!-- Designs -->
                <div class="menu">
                    <li class="nav-link" data-navLink="3">
                        <div class="navhead">
                            <i class='bx bx-paint icon'></i>
                            <span class="text nav-text">Designs</span>
                        </div>
                    </li>
                </div>
            @endif

            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('machineOps'))
                <!-- Machine Operations -->
                <div class="menu">
                    <li class="nav-link" data-navLink="4">
                        <div class="navhead">
                            @if (auth()->user()->hasRole('admin'))
                                <i class='bx bx-wrench icon'></i>
                                <span class="text nav-text">
                                    Machine Ops
                                </span>
                            @elseif (auth()->user()->hasRole('machineOps'))
                                <i class='bx bx-task icon'></i>
                                <span class="text nav-text">
                                    My Jobs
                                </span>
                            @endif
                        </div>
                    </li>
                </div>
            @endif

            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('qcOps'))
                <!-- Machine Operations -->
                <div class="menu">
                    <li class="nav-link" data-navLink="5">
                        <div class="navhead">
                            @if (auth()->user()->hasRole('admin'))
                                <i class='bx bx-check-circle icon'></i>
                                <span class="text nav-text">QC Ops</span>
                            @elseif (auth()->user()->hasRole('qcOps'))
                                <i class='bx bx-wrench icon'></i>
                                <span class="text nav-text">My Jobs</span>
                            @endif
                        </div>
                    </li>
                </div>
            @endif

            @if (auth()->user()->hasrole('admin') || auth()->user()->hasrole('supervisor'))
                <!-- Expenses -->
                <div class="menu">
                    <li class="nav-link" data-navLink="6">
                        <div class="navhead">
                            <i class='bx bx-wallet icon'></i>
                            <span class="text nav-text">Expenses</span>
                        </div>
                    </li>
                </div>
            @endif

            @if (auth()->user()->hasrole('admin') || auth()->user()->hasRole('customer'))
                <!-- Transactions -->
                <div class="menu">
                    <li class="nav-link" data-navLink="7">
                        <div class="navhead">
                            <i class='bx bx-transfer-alt icon'></i>
                            <span class="text nav-text">Transactions</span>
                        </div>
                    </li>
                </div>
            @endif
            @if (auth()->user()->hasrole('admin') || auth()->user()->hasRole('customer'))
                <!-- Transactions -->
                <div class="menu">
                    <li class="nav-link" data-navLink="8">
                        <div class="navhead">
                            <i class='bx bx-money-withdraw icon'></i>
                            <span class="text nav-text">Debts</span>
                        </div>
                    </li>
                </div>
            @endif

            @if (auth()->user()->hasrole('admin'))
                <!-- Payroll -->
                <div class="menu">
                    <li class="nav-link">
                        <div class="navhead">
                            <i class='bx bx-dollar-circle icon'></i>
                            <div class="flex justify-between items-center">
                                <span class="text nav-text">Payroll</span>
                                <i class="bx bx-chevron-down scale-125 opacity-85 icon transition-transform"
                                    style="margin-left: 102px"></i>
                            </div>
                        </div>
                    </li>
                    <div class="submenu">
                        <ul class="submenu-links">
                            <li class="sub-nav-link">
                                <span class="text sub-nav-text" data-subNavText="3">Payroll Jobs</span>
                            </li>
                            <li class="sub-nav-link">
                                <span class="text sub-nav-text" data-subNavText="4">Daily Payroll</span>
                            </li>
                            <li class="sub-nav-link">
                                <span class="text sub-nav-text" data-subNavText="5">Weekly Payroll</span>
                            </li>
                        </ul>
                    </div>
                </div>
            @endif

            @if (auth()->user()->hasrole('admin'))
                <!-- Reports -->
                <div class="menu">
                    <li class="nav-link">
                        <div class="navhead">
                            <i class='bx bx-bar-chart-square icon'></i>
                            <div class="flex justify-between items-center">
                                <span class="text nav-text">Reports</span>
                                <i class="bx bx-chevron-down scale-125 opacity-85 icon transition-transform"
                                    style="margin-left: 94px"></i>
                            </div>
                        </div>
                    </li>
                    <div class="submenu">
                        <ul class="submenu-links">
                            <li class="sub-nav-link">
                                <span class="text sub-nav-text" data-subNavText="6">Financial Reports</span>
                            </li>
                            <li class="sub-nav-link">
                                <span class="text sub-nav-text" data-subNavText="7">Operational Reports</span>
                            </li>
                            <li class="sub-nav-link">
                                <span class="text sub-nav-text" data-subNavText="8">Design Reports</span>
                            </li>
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Settings -->
            <div class="menu">
                <li class="nav-link">
                    <div class="navhead">
                        <i class='bx bx-cog icon'></i>
                        <div class="flex justify-between items-center">
                            <span class="text nav-text">Settings</span>
                            <i class="bx bx-chevron-down scale-125 opacity-85 icon transition-transform"
                                style="margin-left: 90px"></i>
                        </div>
                    </div>
                </li>
                <div class="submenu">
                    <ul class="submenu-links">
                        <li class="sub-nav-link">
                            <span class="text sub-nav-text" data-subNavText="9">General Settings</span>
                        </li>
                        <li class="sub-nav-link">
                            <span class="text sub-nav-text" data-subNavText="10">Notification Settings</span>
                        </li>
                        <li class="sub-nav-link">
                            <span class="text sub-nav-text" data-subNavText="11">System Logs</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Support -->
            <div class="menu">
                <li class="nav-link">
                    <div class="navhead">
                        <i class='bx bx-support icon'></i>
                        <div class="flex justify-between items-center">
                            <span class="text nav-text">Support</span>
                            <i class="bx bx-chevron-down scale-125 opacity-85 icon transition-transform"
                                style="margin-left: 93px"></i>
                        </div>
                    </div>
                </li>
                <div class="submenu">
                    <ul class="submenu-links">
                        <li class="sub-nav-link">
                            <span class="text sub-nav-text" data-subNavText="12">Help Center</span>
                        </li>
                        <li class="sub-nav-link">
                            <span class="text sub-nav-text" data-subNavText="13">Contact Support</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

        <br>

        {{-- Profile --}}
        {{-- <div class="bottom-content">
                    <li class="mode">
                        <div class="sun-moon">
                            <i class='bx bx-moon icon moon'></i>
                            <i class='bx bx-sun icon sun'></i>
                        </div>
                        <span class="mode-text text">Dark mode</span>
                        <div class="toggle-switch">
                            <span class="switch"></span>
                        </div>
                    </li>
                </div> --}}
        </div>
    </nav>

    <section class="home">
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            @include('profile.partials.header')
            <!-- Page Content -->
            <main class="p-6">
                {{ $slot }}
            </main>
        </div>
    </section>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notifButton = document.getElementById('notifButton');
            const notifCard = document.getElementById('notif');
            const closeNotif = document.getElementById('closeNotif');

            notifButton.addEventListener('click', function() {
                notifCard.classList.remove('hidden');
                notifCard.classList.add('flex');
            });

            closeNotif.addEventListener('click', function() {
                notifCard.classList.add('hidden');
                notifCard.classList.remove('flex');
            });

            notifCard.addEventListener('click', function(e) {
                if (e.target === notifCard) {
                    notifCard.classList.add('hidden');
                    notifCard.classList.remove('flex');
                }
            });
        });
    </script>
    <!-- Alpine.js -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        // Pilih semua nav-link dan sub-nav-text
        const navLinks = document.querySelectorAll('.nav-link');
        const subNavLinks = document.querySelectorAll('.sub-nav-text');

        // Objek untuk menyimpan rute
        const routes = {
            navLinks: {
                1: '/dashboard',
                2: '/design-requests/all-requests',
                3: '/designs',
                4: '/machine-operations',
                5: '/qc-operations',
                6: '/expenses',
                7: '/transactions',
                8: '/debt'
            },
            subNavLinks: {
                1: '/user-management/all-users',
                2: '/user-management/roles-permissions',
                3: '/payroll/payroll-jobs',
                4: '/payroll/daily-payroll',
                5: '/payroll/weekly-payroll',
                6: '/reports/financial',
                7: '/reports/operational',
                8: '/reports/design',
                9: '/settings/general',
                10: '/settings/notifications',
                11: '/settings/system-logs',
                12: '/support/help-center',
                13: '/support/contact'
            }
        };

        // Event listener untuk nav-link
        navLinks.forEach(navLink => {
            navLink.addEventListener('click', (e) => {
                e.preventDefault();
                const navLinkId = navLink.getAttribute('data-navLink');
                const submenu = navLink.nextElementSibling;

                if (submenu && submenu.classList.contains('submenu')) {
                    // Jika navlink memiliki submenu, toggle submenu
                    toggleSubmenu(submenu, navLink.querySelector('.bx-chevron-down'));
                } else {
                    // Jika tidak, navigasi ke rute
                    const route = routes.navLinks[navLinkId];
                    if (route) {
                        navigateToRoute(route);
                    }
                }
            });
        });

        // Event listener untuk sub-nav-text
        subNavLinks.forEach(subNavText => {
            subNavText.addEventListener('click', (e) => {
                e.preventDefault();
                const subNavTextId = subNavText.getAttribute('data-subNavText');
                const route = routes.subNavLinks[subNavTextId];
                if (route) {
                    navigateToRoute(route);
                }
            });
        });

        function toggleSubmenu(submenu, iconChevron) {
            const isActive = submenu.classList.contains('submenu-active');
            closeAllSubmenus();
            if (!isActive) {
                submenu.classList.add('submenu-active');
                submenu.style.maxHeight = submenu.scrollHeight + 'px';
                iconChevron.classList.add('-rotate-90');
            }
        }

        function navigateToRoute(route) {
            if (route) {
                window.location.href = route;
            } else {
                console.error('Route not found');
            }
        }

        function setActiveState() {
            const currentPath = window.location.pathname;

            // Reset all active states
            removeActiveClasses(navLinks);
            removeSubNavLinkActiveClasses(subNavLinks);
            closeAllSubmenus();

            // Check navLinks
            for (const [id, route] of Object.entries(routes.navLinks)) {
                if (currentPath === route) {
                    const navLink = document.querySelector(`.nav-link[data-navLink="${id}"]`);
                    if (navLink) {
                        navLink.classList.add('nav-link-active');
                        const submenu = navLink.nextElementSibling;
                        if (submenu && submenu.classList.contains('submenu')) {
                            openSubmenu(submenu, navLink.querySelector('.bx-chevron-down'));
                        }
                    }
                    break;
                }
            }

            // Check subNavLinks
            for (const [id, route] of Object.entries(routes.subNavLinks)) {
                if (currentPath === route) {
                    const subNavText = document.querySelector(`.sub-nav-text[data-subNavText="${id}"]`);
                    if (subNavText) {
                        subNavText.classList.add('sub-nav-text-active');
                        const parentNavLink = subNavText.closest('.submenu').previousElementSibling;
                        if (parentNavLink) {
                            parentNavLink.classList.add('nav-link-active');
                            const submenu = parentNavLink.nextElementSibling;
                            const iconChevron = parentNavLink.querySelector('.bx-chevron-down');
                            if (submenu && iconChevron) {
                                openSubmenu(submenu, iconChevron);
                            }
                        }
                    }
                    break;
                }
            }
        }

        function openSubmenu(submenu, iconChevron) {
            submenu.classList.add('submenu-active');
            submenu.style.maxHeight = submenu.scrollHeight + 'px';
            iconChevron.classList.add('-rotate-90');
        }

        function closeAllSubmenus() {
            const allSubmenus = document.querySelectorAll('.submenu-active');

            allSubmenus.forEach(submenu => {
                submenu.classList.remove('submenu-active');
                submenu.style.maxHeight = null; // Reset tinggi submenu

                // Temukan ikon chevron yang terkait dengan submenu ini
                const iconChevron = submenu.previousElementSibling.querySelector('.bx-chevron-down');
                if (iconChevron) {
                    iconChevron.classList.remove('-rotate-90'); // Kembalikan posisi chevron
                }
            });
        }

        function removeActiveClasses(links) {
            links.forEach(link => link.classList.remove('nav-link-active'));
        }

        function removeSubNavLinkActiveClasses(links) {
            links.forEach(link => link.classList.remove('sub-nav-text-active'));
        }

        // Call this function when the page loads
        document.addEventListener('DOMContentLoaded', setActiveState);
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.5.1/dist/cdn.min.js"></script>
    <script>
        const body = document.querySelector('body'),
            sidebar = body.querySelector('nav'),
            toggle = body.querySelector(".toggle"),
            modeSwitch = body.querySelector(".toggle-switch"),
            modeText = body.querySelector(".mode-text");



        toggle.addEventListener("click", () => {
            sidebar.classList.toggle("close");
        })


        // modeSwitch.addEventListener("click", () => {
        //     body.classList.toggle("dark");

        //     if (body.classList.contains("dark")) {
        //         modeText.innerText = "Light mode";
        //     } else {
        //         modeText.innerText = "Dark mode";

        //     }
        // });
    </script>
    <script>
        function navigateTo(url) {
            // Hapus kelas aktif dari semua menu
            document.querySelectorAll('.sub-nav-link').forEach(link => {
                link.classList.remove('active'); // Atau kelas yang sesuai
            });

            // Tambahkan kelas aktif hanya pada link yang diklik
            event.currentTarget.classList.add('active'); // event.currentTarget adalah li yang diklik

            // Arahkan ke URL
            window.location.href = url;
        }
    </script>
    <x-notify::notify />
    @notifyJs

</body>

</html>
