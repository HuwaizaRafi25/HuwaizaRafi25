<x-guest-layout>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #eaeaea
        }

        .overlay {
            background-color: rgba(99, 99, 99, 0.226);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60%;
            height: 85%;
            border-radius: 0.5rem;
            padding: 3rem 2rem 3rem 2rem;
        }

        .content {
            background-color: white;
            border-radius: 1rem;
            height: 100%;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 20;
        }

        .image-side {
            background: url('{{ asset('assets/images/daftar.jpg') }}') no-repeat center center;
            background-size: cover;
        }
    </style>

    <div class="overlay">
        <div class="content flex overflow-hidden">
            <!-- Image Side -->
            <div class="image-side hidden lg:block lg:w-1/2">
                <!-- Background image is set via CSS -->
            </div>

            <!-- Form Side -->
            <div class="form-side w-full lg:w-1/2 p-8">
                <a href="/"><img src="{{ asset('assets/images/icons8-back-32 (1).png') }}"
                        class="opacity-25 -mt-6 -ml-6"></a>
                <div class="flex w-full justify-center my-2 -mt-2">
                    <img src="{{ asset('assets/images/LO-1.png') }}" class="w-8 h-8">
                    <h2 class="mt-3 ml-1">Embroidery</h2>
                </div>

                <h2 class="text-2xl font-bold my-6">DAFTAR</h2>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-text-input id="username" class="block mt-1 w-full" type="text" name="username"
                            :value="old('username')" required autofocus autocomplete="username" placeholder="Username" />
                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div class="mt-4">
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email')" required autocomplete="email" placeholder="Email Address" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                            autocomplete="new-password" placeholder="Password" />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="my-4">
                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password" />

                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-800 text-white py-3 rounded-lg font-semibold transition-colors">Register</button>

                    <div class="flex mt-2 justify-center">
                        <h5>Already have an account?</h5>
                        <a href="register" class="block text-center ml-2 text-blue-600 hover:underline">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
