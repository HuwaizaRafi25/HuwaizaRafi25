<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>loembroidery</title>
    @notifyCss
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/images/lg.png') }}" class="rounded-full" type="image/x-icon">
    <style>
        *{
            scroll-behavior: smooth;
        }
        /* Tambahkan transisi untuk header */
        header {
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        /* Saat di-scroll, header akan berubah */
        .scrolled {
            background-color: rgba(0, 0, 0, 0.288);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">
    <!-- Navbar -->
    <header id="navbar" class="fixed w-full h-min top-0 left-0 p-6 bg-transparent z-50">
        <div class="flex justify-between items-center h-2">
            <div class="text-2xl font-bold flex text-white">LO<p class="text-xs pt-3 pl-0.5 font-thin">embroidery</p>
            </div>
            <nav class="space-x-6">
                <a href="#home" class="text-white hover:text-gray-300">Home</a>
                <a href="#features" class="text-white hover:text-gray-300">Features</a>
                <a href="#gallery" class="text-white hover:text-gray-300">Gallery</a>
                <a href="#blog" class="text-white hover:text-gray-300">Blog</a>
                <a href="#contact" class="text-white hover:text-gray-300">Contact</a>
            </nav>
            <div class="space-x-4">
                <a href="dashboard" class="text-white hover:text-gray-300">Sign in</a>
                <a href="register" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">Sign up</a>
            </div>
        </div>
    </header>

    <!-- Home Section -->
    <section id="home"
        class="relative h-screen bg-cover bg-center text-white text-center flex items-center justify-center"
        style="background-image: url('{{ asset('assets/images/IMG_3910.JPG') }}');">
        <div class="absolute inset-0 bg-black opacity-70 z-10"></div> <!-- Darker overlay -->
        <div class="relative z-20 p-8"> <!-- Ensure the text is above the overlay -->
            <h1 class="text-4xl font-bold mb-4">Take control of your customer support</h1>
            <p class="mb-6">Anim aute id magna aliqua ad ad non deserunt sunt. Qui irure qui lorem cupidatat commodo.
            </p>
            <div class="space-x-4">
                <a href="#features" class="bg-white text-purple-800 px-6 py-3 rounded font-medium hover:bg-gray-100">Get
                    started</a>
                <a href="#gallery"
                    class="bg-purple-600 text-white px-6 py-3 rounded font-medium hover:bg-purple-700">View Gallery</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-12 bg-gray-100">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold text-center mb-8">Features</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold mb-4">Easy Order Management</h3>
                    <p class="text-gray-600 mb-4">Manage all your embroidery orders in one place with our user-friendly
                        interface.</p>
                    <a href="#" class="text-purple-600 hover:underline">Learn more</a>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold mb-4">Customizable Designs</h3>
                    <p class="text-gray-600 mb-4">Create and customize embroidery designs that match your vision
                        perfectly.</p>
                    <a href="#" class="text-purple-600 hover:underline">Learn more</a>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold mb-4">Automated Workflow</h3>
                    <p class="text-gray-600 mb-4">Our platform helps automate your workflow, making your business more
                        efficient.</p>
                    <a href="#" class="text-purple-600 hover:underline">Learn more</a>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold mb-4">Real-Time Collaboration</h3>
                    <p class="text-gray-600 mb-4">Collaborate with your team in real-time, ensuring everyone is on the
                        same page.</p>
                    <a href="#" class="text-purple-600 hover:underline">Learn more</a>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold mb-4">Order Tracking</h3>
                    <p class="text-gray-600 mb-4">Track your orders from start to finish, ensuring timely delivery.</p>
                    <a href="#" class="text-purple-600 hover:underline">Learn more</a>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold mb-4">Customer Support</h3>
                    <p class="text-gray-600 mb-4">Our dedicated support team is here to help you every step of the way.
                    </p>
                    <a href="#" class="text-purple-600 hover:underline">Learn more</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section id="gallery" class="py-12 bg-white">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold text-center mb-8">Design Gallery</h2>
            <div class="overflow-x-auto whitespace-nowrap px-4">
                <div class="flex space-x-8">
                    <div
                        class="bg-gray-100 p-6 rounded-lg shadow-lg min-w-[300px] transform hover:scale-105 transition-transform duration-300">
                        <img src="{{ asset('assets/images/siji.PNG') }}" alt="Design 1"
                            class="w-full h-48 object-cover rounded-lg mb-4">
                        <p class="text-gray-600">Beautiful floral embroidery design.</p>
                    </div>
                    <div
                        class="bg-gray-100 p-6 rounded-lg shadow-lg min-w-[300px] transform hover:scale-105 transition-transform duration-300">
                        <img src="{{ asset('assets/images/telu.PNG') }}" alt="Design 2"
                            class="w-full h-48 object-cover rounded-lg mb-4">
                        <p class="text-gray-600">Custom logo design for corporate branding.</p>
                    </div>
                    <div
                        class="bg-gray-100 p-6 rounded-lg shadow-lg min-w-[300px] transform hover:scale-105 transition-transform duration-300">
                        <img src="{{ asset('assets/images/loro.PNG') }}" alt="Design 3"
                            class="w-full h-48 object-cover rounded-lg mb-4">
                        <p class="text-gray-600">Intricate geometric pattern embroidery.</p>
                    </div>
                    <!-- Tambahkan lebih banyak desain di sini -->
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Section -->
    <section id="blog" class="py-12 bg-gray-100">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold text-center mb-8">Our Blog</h2>
            <div class="grid grid-cols-1 gap-8">
                <div class="flex bg-white p-6 rounded-lg shadow-lg">
                    <img src="https://via.placeholder.com/150" alt="Blog 1"
                        class="w-1/3 h-auto object-cover rounded-lg mr-6">
                    <div class="flex flex-col justify-between">
                        <div>
                            <h3 class="text-xl font-semibold mb-4">Latest Trends in Embroidery</h3>
                            <p class="text-gray-600 mb-4">Discover the hottest trends in the embroidery world and how
                                you can apply them to your business.</p>
                        </div>
                        <a href="#" class="text-purple-600 hover:underline">Read more</a>
                    </div>
                </div>
                <div class="flex bg-white p-6 rounded-lg shadow-lg">
                    <img src="https://via.placeholder.com/150" alt="Blog 2"
                        class="w-1/3 h-auto object-cover rounded-lg mr-6">
                    <div class="flex flex-col justify-between">
                        <div>
                            <h3 class="text-xl font-semibold mb-4">5 Tips for Managing Bulk Orders</h3>
                            <p class="text-gray-600 mb-4">Learn how to efficiently manage bulk orders with our expert
                                tips.</p>
                        </div>
                        <a href="#" class="text-purple-600 hover:underline">Read more</a>
                    </div>
                </div>
                <div class="flex bg-white p-6 rounded-lg shadow-lg">
                    <img src="https://via.placeholder.com/150" alt="Blog 3"
                        class="w-1/3 h-auto object-cover rounded-lg mr-6">
                    <div class="flex flex-col justify-between">
                        <div>
                            <h3 class="text-xl font-semibold mb-4">Maximizing Customer Satisfaction</h3>
                            <p class="text-gray-600 mb-4">Explore strategies for keeping your customers happy and
                                coming back for more.</p>
                        </div>
                        <a href="#" class="text-purple-600 hover:underline">Read more</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-12 bg-white">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold text-center mb-8">Contact Us</h2>
            <div class="max-w-2xl mx-auto">
                <form>
                    <div class="mb-4">
                        <input type="text" placeholder="Your Name"
                            class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div class="mb-4">
                        <input type="email" placeholder="Your Email"
                            class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div class="mb-4">
                        <textarea placeholder="Your Message"
                            class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                    </div>
                    <button type="submit"
                        class="w-full bg-purple-600 text-white py-3 rounded-lg font-semibold hover:bg-purple-700 transition-colors">Send
                        Message</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-8 bg-gray-800 text-white text-center">
        <p>&copy; 2024 Loembroidery. All rights reserved.</p>
    </footer>

    <script>
        // JavaScript untuk mengubah gaya navbar saat di-scroll
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 0) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
    <x-notify::notify />
    @notifyJs
</body>

</html>
