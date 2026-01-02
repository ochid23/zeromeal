<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
        body { font-family: 'Instrument Sans', sans-serif; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 min-h-screen flex flex-col">

    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 relative">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-green-600 tracking-tighter">
                        ZeroMeal
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex space-x-8 absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2">
                    <a href="#" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-green-600 transition">Layanan</a>
                    <a href="#" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-green-600 transition">Harga</a>
                    <a href="#" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-green-600 transition">Hubungi Kami</a>
                    <a href="#" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-green-600 transition">About Us</a>
                </div>

                <div class="flex items-center space-x-4">
                    @if(Session::has('api_token'))
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-green-600 transition">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800 transition cursor-pointer">
                                Log Out
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-green-600 transition">Log In</a>
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
    <footer class="bg-white border-t border-gray-100 py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} ZeroMeal. All rights reserved.
        </div>
    </footer>

</body>
</html>
