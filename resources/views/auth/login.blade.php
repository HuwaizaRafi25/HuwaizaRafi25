<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />


    <div class="overlay">
        <div class="content flex overflow-hidden">
            <!-- Form Side -->
            <div class="form-side w-full lg:w-1/2 p-8">
                <a href="/"><img src="{{ asset('assets/images/icons8-back-32 (1).png') }}"
                        class="opacity-25 -mt-6 -ml-6"></a>
                <div class="flex w-full justify-center my-4">
                    <img src="{{ asset('assets/images/LO-1.png') }}" class="w-8 h-8">
                    <h2 class="mt-3 ml-1">Embroidery</h2>
                </div>

                <h2 class="text-2xl font-bold my-6">LOGIN</h2>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <input id="email" type="email" name="email" autocomplete="email" placeholder="Email"
                        class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />

                    <div class="relative">
                        <input id="password"
                            class="block mt-1 w-full mb-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            type="password" name="password" required autocomplete="current-password"
                            placeholder="Password" />

                        <!-- Tombol untuk mengubah visibilitas password -->
                        <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                            <!-- Icon mata terbuka (untuk melihat password) -->
                            <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-.021.101-.051.201-.089.3m-2.01 3.007A9.96 9.96 0 0112 19c-4.477 0-8.267-2.943-9.542-7a9.955 9.955 0 011.933-3.993" />
                            </svg>

                            <!-- Icon mata tertutup (untuk menyembunyikan password) -->
                            <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-.021.101-.051.201-.089.3m-2.01 3.007A9.96 9.96 0 0112 19c-4.477 0-8.267-2.943-9.542-7a9.955 9.955 0 011.933-3.993" />
                                <!-- Garis penutup -->
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                            </svg>

                        </button>
                    </div>

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />

                    <div class="flex justify-between">
                        <label for="remember" class="inline-flex items-center mb-6">
                            <input id="remember" type="checkbox"
                                class="rounded bg-slate-100 text-indigo-600 shadow-sm"
                                name="remember">
                            <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>
                    <button type="submit"
                        class="w-full bg-blue-800 text-white py-3 rounded-lg font-semibold transition-colors">Login</button>

                    <div class="flex mt-2 justify-center">
                        <h5>Belum memiliki akun?</h5>
                        <a href="register" class="block text-center ml-2 text-blue-600 hover:underline">Daftar</a>
                    </div>
                </form>
            </div>

            <!-- Image Side -->
            <div class="image-side hidden lg:block lg:w-1/2"
                style="background: url('assets/images/pattern.jpg') no-repeat center center; background-size: cover">
                <!-- Background image is set via CSS -->
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');

            togglePassword.addEventListener('click', function() {
                // Toggle the type attribute
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Toggle eye icon visibility
                eyeOpen.classList.toggle('hidden');
                eyeClosed.classList.toggle('hidden');
            });
        });
    </script>
</x-guest-layout>
