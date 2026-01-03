<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ZeroMeal') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
        /* Custom Scrollbar for horizontal scrolling */
        .hide-scroll::-webkit-scrollbar {
            display: none;
        }
        .hide-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 min-h-screen flex flex-col">

    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                         <img src="{{ asset('images/logo.png') }}" alt="ZeroMeal" class="h-8 w-auto">
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-medium text-gray-900">{{ Session::get('user')['name'] ?? 'User' }}</div>
                        <div class="text-xs text-gray-500">Free Plan</div>
                    </div>
                    <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-bold">
                        {{ substr(Session::get('user')['name'] ?? 'U', 0, 1) }}
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex-grow flex max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 gap-6">
        
        <aside class="w-64 hidden md:flex flex-col h-fit sticky top-24 space-y-4">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4">
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition font-medium group {{ request()->routeIs('dashboard') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-green-50 hover:text-green-600' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-green-600' : 'text-gray-400 group-hover:text-green-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('inventory') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition font-medium group {{ request()->routeIs('inventory') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-green-50 hover:text-green-600' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('inventory') ? 'text-green-600' : 'text-gray-400 group-hover:text-green-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        Inventory & Pantry
                    </a>

                    <a href="{{ route('shopping.index') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition font-medium group {{ request()->routeIs('shopping.index') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-green-50 hover:text-green-600' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('shopping.index') ? 'text-green-600' : 'text-gray-400 group-hover:text-green-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        Shopping List
                    </a>

                    <a href="{{ route('recipes') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition font-medium group {{ request()->routeIs('recipes') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-green-50 hover:text-green-600' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('recipes') ? 'text-green-600' : 'text-gray-400 group-hover:text-green-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Recipes
                    </a>

                    <a href="#" class="flex items-center px-4 py-3 rounded-lg transition font-medium group text-gray-600 hover:bg-green-50 hover:text-green-600">
                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profile
                    </a>
                </nav>
            </div>
            
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition font-medium">
                        <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Log Out
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 w-full space-y-6">
            
            <section>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <span class="w-2 h-6 bg-red-500 rounded-full mr-2"></span>
                        Segera Kadaluarsa
                    </h3>
                    <a href="{{ route('inventory') }}" class="text-sm text-green-600 font-medium hover:underline">Lihat Semua</a>
                </div>
                
                <div class="flex space-x-4 overflow-x-auto hide-scroll pb-2">
                    @forelse($expiringItems as $item)
                    <div class="min-w-[160px] bg-white border border-red-100 rounded-xl p-4 shadow-sm flex flex-col relative group hover:shadow-md transition">
                        <div class="absolute top-3 right-3 w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                        <div class="text-3xl mb-2">⚠️</div>
                        <h4 class="font-bold text-gray-800 text-sm mb-1 leading-snug break-words">
                            {{ $item['name'] }}
                        </h4>
                        <p class="text-xs text-gray-500 mb-2">{{ $item['qty'] }}</p>
                        <div class="mt-auto bg-red-50 text-red-700 text-xs font-bold px-2 py-1 rounded-md inline-block text-center border border-red-200">
                            {{ $item['days_left'] }} Hari Lagi
                        </div>
                    </div>
                    @empty
                    <div class="w-full bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 text-center text-sm">
                        Aman! Tidak ada bahan yang hampir kadaluarsa.
                    </div>
                    @endforelse
                </div>
            </section>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <section class="lg:col-span-1">
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm h-full flex flex-col">
                        <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800">Daftar Belanja</h3>
                            <button class="text-green-600 hover:bg-green-50 p-1.5 rounded-full">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </button>
                        </div>
                        <div class="p-2 flex-1">
                            <ul class="space-y-1">
                                @forelse($shoppingList as $list)
                                <li class="flex items-center p-3 hover:bg-gray-50 rounded-lg group transition cursor-pointer">
                                    <input type="checkbox" class="w-5 h-5 text-green-600 rounded border-gray-300 focus:ring-green-500" {{ $list['checked'] ? 'checked' : '' }}>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-800 {{ $list['checked'] ? 'line-through text-gray-400' : '' }}">{{ $list['name'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $list['qty'] }}</p>
                                    </div>
                                </li>
                                @empty
                                <li class="text-center text-gray-400 text-sm py-8">Belum ada daftar belanja.</li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="p-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                            <a href="{{ route('shopping.index') }}" class="block w-full text-center text-sm text-gray-600 font-medium hover:text-green-600">
    Kelola Belanjaan →
</a>
                        </div>
                    </div>
                </section>

                <section class="lg:col-span-2">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-bold text-gray-800">Mau Masak Apa?</h3>
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Berdasarkan stok kamu</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($recipes as $recipe)
                        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition flex flex-col">
                            <div class="h-32 w-full bg-gray-200 relative">
                                <img src="{{ $recipe['image'] }}" alt="{{ $recipe['title'] }}" class="w-full h-full object-cover">
                                <div class="absolute top-2 right-2 bg-white/90 backdrop-blur px-2 py-1 rounded-md shadow-sm">
                                    <div class="text-xs font-bold text-green-700 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        {{ $recipe['match'] }}% Match
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 flex-1 flex flex-col">
                                <h4 class="font-bold text-gray-900 mb-1">{{ $recipe['title'] }}</h4>
                                <div class="text-xs text-gray-500 mb-4 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $recipe['time'] }}
                                </div>
                                <button class="mt-auto w-full bg-green-600 text-white text-sm font-semibold py-2 rounded-lg hover:bg-green-700 transition">
                                    Lihat Resep
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-2 text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                            <p class="text-gray-500">Belum ada rekomendasi. Isi inventaris kamu dulu!</p>
                        </div>
                        @endforelse
                    </div>
                </section>
            </div>
        </main>
    </div>

</body>
</html>