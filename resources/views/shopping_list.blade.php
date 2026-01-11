@extends('layouts.dashboard')

@section('content')


    <h1 class="text-2xl font-bold text-gray-900 mb-2">Daftar Belanja</h1>
    <p class="text-gray-500 mb-6">Kelola anggaran dan stok dengan tabel belanja terintegrasi.</p>

    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6">
        <form action="{{ route('shopping.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
            @csrf

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-500 mb-1">Nama Barang</label>
                <select name="barang_id" required
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 px-3 py-2 border bg-white">
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
                <input type="number" name="jumlah_produk" min="1" value="1" required
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 px-3 py-2 border">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">Estimasi Harga (Rp)</label>
                <input type="number" name="harga" placeholder="0"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 px-3 py-2 border">
            </div>

            <div class="md:col-span-4 flex justify-end mt-2">
                <button type="submit"
                    class="bg-green-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-green-700 transition flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
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
                                <a href="{{ route('shopping.toggle', $list['belanja_id']) }}"
                                    class="flex items-center justify-center w-6 h-6 rounded border {{ $list['status_beli'] ? 'bg-green-500 border-green-500 text-white' : 'border-gray-300 text-transparent hover:border-green-500' }} transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                                        </path>
                                    </svg>
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="{{ $list['status_beli'] ? 'opacity-50 line-through' : '' }}">
                                    <p class="font-medium text-gray-900">
                                        {{ $list['nama_barang'] ?? 'Barang Dihapus' }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $list['kategori'] ?? '-' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <!-- Data: daftar_belanja.jumlah_produk -->
                                {{ $list['jumlah_produk'] }} {{ $list['satuan_standar'] ?? 'pcs' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                Rp {{ number_format($list['harga'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button
                                    onclick='openEditModal(@json($list))'
                                    class="text-gray-300 hover:text-blue-500 transition p-1 rounded-full hover:bg-blue-50 mr-1"
                                    title="Edit Item">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <form action="{{ route('shopping.delete', $list['belanja_id']) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="text-gray-300 hover:text-red-500 p-1 rounded-full hover:bg-red-50 transition" title="Hapus Item">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
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

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" onclick="closeEditModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900" id="modal-title">Edit Item Belanja</h3>
                            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500">X</button>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm font-bold text-gray-700 mb-1">Nama Barang</p>
                            <p id="edit_nama_barang" class="text-gray-900 text-lg font-semibold">-</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah</label>
                                <div class="flex items-center">
                                    <input type="number" id="edit_jumlah" name="jumlah_produk" min="1" class="shadow appearance-none border rounded-l w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <span id="edit_satuan" class="bg-gray-100 border border-l-0 border-gray-300 py-2 px-3 rounded-r text-gray-600 text-sm font-bold">pcs</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Harga (Rp)</label>
                                <input type="number" id="edit_harga" name="harga" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan Perubahan
                        </button>
                        <button type="button" onclick="closeEditModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </main>

    <script>
        function openEditModal(item) {
            document.getElementById('edit_nama_barang').innerText = item.nama_barang || item.barang?.nama_barang || 'Item';
            document.getElementById('edit_jumlah').value = item.jumlah_produk;
            document.getElementById('edit_harga').value = item.harga;
            document.getElementById('edit_satuan').innerText = item.satuan_standar || 'pcs';
            
            // Set Form Action URL
            document.getElementById('editForm').action = "/shopping-list/" + item.belanja_id;
            
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
@endsection