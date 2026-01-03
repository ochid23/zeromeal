@extends('layouts.dashboard')

@section('content')


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
@endsection