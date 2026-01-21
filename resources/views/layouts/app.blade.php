<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ZeroMeal') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>
        /* Custom Font override */
        body {
            font-family: 'Instrument Sans', sans-serif;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50 text-gray-900 min-h-screen flex flex-col">

    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 relative">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('images/logo.png') }}" alt="ZeroMeal Logo" class="h-10 w-auto">
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div
                        class="hidden sm:flex space-x-8 absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2">
                        <a href="{{ route('home') }}#services"
                            class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-green-600 transition">Layanan</a>

                        <a href="{{ route('home') }}#contact-us"
                            class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-green-600 transition">Hubungi
                            Kami</a>
                        <a href="{{ route('home') }}#about-us"
                            class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-green-600 transition">About
                            Us</a>
                    </div>

                    <div class="flex items-center space-x-4">
                        @if(Session::has('api_token'))
                            <a href="{{ route('dashboard') }}"
                                class="text-sm font-medium text-gray-700 hover:text-green-600 transition">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit"
                                    class="text-sm font-medium text-red-600 hover:text-red-800 transition cursor-pointer">
                                    Log Out
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                                class="px-5 py-2.5 bg-green-600 text-white text-sm font-medium rounded-full hover:bg-green-700 transition shadow-sm">Log
                                In</a>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

    <!-- Page Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900" aria-labelledby="footer-heading">
        <h2 id="footer-heading" class="sr-only">Footer</h2>
        <div class="mx-auto max-w-7xl px-6 pb-8 pt-16 sm:pt-24 lg:px-8 lg:pt-32">
            <div class="xl:grid xl:grid-cols-3 xl:gap-8">
                <div class="space-y-8">
                    <img class="h-9" src="{{ asset('images/logo.png') }}" alt="ZeroMeal">
                    <p class="text-sm leading-6 text-gray-300">
                        Cook Smart. Waste Less. <br>
                        Helping you manage your kitchen efficiently and sustainably.
                    </p>
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-gray-300">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-300">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772 4.902 4.902 0 011.772-1.153c.636-.247 1.363-.416 2.427-.465 1.067-.047 1.407-.06 3.808-.06h.63zm1.14 2H7.29c-2.26 0-3.35.37-4.11.66-.67.26-1.2.65-1.58 1.03-.38.38-.77.91-1.03 1.58-.29.76-.66 1.85-.66 4.11v4.71c0 2.26.37 3.35.66 4.11.26.67.65 1.2 1.03 1.58.38.38.91.77 1.58 1.03.76.29 1.85.66 4.11.66h4.71c2.26 0 3.35-.37 4.11-.66.67-.26 1.2-.65 1.58-1.03.38-.38.77-.91 1.03-1.58.29-.76.66-1.85.66-4.11v-4.71c0-2.26-.37-3.35-.66-4.11-.26-.67-.65-1.2-1.03-1.58-.38-.38-.91-.77-1.58-1.03-.76-.29-1.85-.66-4.11-.66h-4.71zM11.63 7h1.44a5 5 0 11-1.44 0zm0 2h1.44a3 3 0 11-1.44 0zM17.44 5.5a1.1 1.1 0 110 2.2 1.1 1.1 0 010-2.2z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-300">
                            <span class="sr-only">GitHub</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.004c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="mt-16 grid grid-cols-2 gap-8 xl:col-span-2 xl:mt-0">
                    <div class="md:grid md:grid-cols-2 md:gap-8">
                        <div>
                            <h3 class="text-sm font-semibold leading-6 text-white">Product</h3>
                            <ul role="list" class="mt-6 space-y-4">
                                <li><a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">Features</a></li>
                                <li><a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">Security</a></li>
                                <li><a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">Pricing</a></li>
                            </ul>
                        </div>
                        <div class="mt-10 md:mt-0">
                            <h3 class="text-sm font-semibold leading-6 text-white">Company</h3>
                            <ul role="list" class="mt-6 space-y-4">
                                <li><a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">About Us</a></li>
                                <li><a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">Blog</a></li>
                                <li><a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">Careers</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="md:grid md:grid-cols-2 md:gap-8">
                        <div>
                            <h3 class="text-sm font-semibold leading-6 text-white">Resources</h3>
                            <ul role="list" class="mt-6 space-y-4">
                                <li><a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">Documentation</a></li>
                                <li><a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">Help Center</a></li>
                                <li><a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">Community</a></li>
                            </ul>
                        </div>
                        <div class="mt-10 md:mt-0">
                            <h3 class="text-sm font-semibold leading-6 text-white">Legal</h3>
                            <ul role="list" class="mt-6 space-y-4">
                                <li><a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">Privacy Policy</a></li>
                                <li><a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">Terms of Service</a></li>
                                <li><a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">Cookie Policy</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-16 border-t border-white/10 pt-8 sm:mt-20 lg:mt-24">
                <p class="text-xs leading-5 text-gray-400">&copy; {{ date('Y') }} ZeroMeal. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>

</html>