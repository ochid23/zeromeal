<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Belanja - {{ config('app.name', 'ZeroMeal') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>body { font-family: 'Instrument Sans', sans-serif; }</style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 min-h-screen flex flex-col">

    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}"><img src="{{ asset('images/logo.png') }}" alt="ZeroMeal" class="h-8 w-auto"></a>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-medium text-gray-900">{{ Session::get('user')['nama'] ?? 'User' }}</div>
                        <div class="text-xs text-gray-500">Free Plan</div>
                    </div>
                    <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-bold">
                        {{ substr(Session::get('user')['nama'] ?? 'U', 0, 1) }}
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex-grow flex max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 gap-6">
        
        <aside class="w-64 hidden md:flex flex-col h-fit sticky top-24 space-y-4">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4">
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-lg transition font-medium text-gray-600 hover:bg-green-50 hover:text-green-600">
                        <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('inventory') }}" class="flex items-center px-4 py-3 rounded-lg transition font-medium text-gray-600 hover:bg-green-50 hover:text-green-600">
                        <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                        Inventory & Pantry
                    </a>
                    <a href="{{ route('shopping.index') }}" class="flex items-center px-4 py-3 bg-green-50 text-green-700 rounded-lg transition font-medium">
                        <svg class="mr-3 h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        Shopping List
                    </a>
                    <a href="{{ route('recipes') }}" class="flex items-center px-4 py-3 rounded-lg transition font-medium text-gray-600 hover:bg-green-50 hover:text-green-600">
                        <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
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
                    <button type="submit" class="w-full flex items-center px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition font-medium">Log Out</button>
                </form>
            </div>
        </aside>

        <main class="flex-1 w-full max-w-4xl">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Daftar Belanja</h1>
            <p class="text-gray-500 mb-6">Kelola anggaran dan stok dengan tabel belanja terintegrasi.</p>

            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6">
                <form action="{{ route('shopping.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                    @csrf
                    
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 mb-1">Nama Barang</label>
                        <select name="barang_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 px-3 py-2 border bg-white">
                            <option value="">-- Pilih Barang --</option>
                            @foreach($masterBarang as $b)
                                <option value="{{ $b['barang_id'] }}">
                                    {{ $b['nama_barang'] }} ({{ $b['satuan_standar'] }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Jml (Pcs/Kg)</label>
                        <input type="number" name="jumlah_produk" min="1" value="1" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 px-3 py-2 border">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Estimasi Harga (Rp)</label>
                        <input type="number" name="harga" placeholder="0" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 px-3 py-2 border">
                    </div>

                    <div class="md:col-span-4 flex justify-end mt-2">
                        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-green-700 transition flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah ke Daftar
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                @if(count($shoppingList) > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">Cek</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($shoppingList as $list)
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('shopping.toggle', $list['belanja_id']) }}" class="flex items-center justify-center w-6 h-6 rounded border {{ $list['status_beli'] ? 'bg-green-500 border-green-500 text-white' : 'border-gray-300 text-transparent hover:border-green-500' }} transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="{{ $list['status_beli'] ? 'opacity-50 line-through' : '' }}">
                                            <p class="font-medium text-gray-900">
                                                {{ $list['barang']['nama_barang'] ?? 'Barang Dihapus' }}
                                            </p>
                                            <p class="text-xs text-gray-500">{{ $list['barang']['kategori'] ?? '-' }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $list['jumlah_produk'] }} {{ $list['barang']['satuan_standar'] ?? 'pcs' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        Rp {{ number_format($list['harga'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <form action="{{ route('shopping.delete', $list['belanja_id']) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button class="text-gray-300 hover:text-red-500 p-1 rounded-full hover:bg-red-50 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-12">
                        <div class="text-4xl mb-3">🛒</div>
                        <h3 class="text-gray-900 font-medium">Daftar belanja kosong</h3>
                        <p class="text-gray-500 text-sm">Pilih barang dari master data untuk mulai belanja.</p>
                    </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>