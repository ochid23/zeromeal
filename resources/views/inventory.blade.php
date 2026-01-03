<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventory - {{ config('app.name', 'ZeroMeal') }}</title>
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
                    <a href="{{ route('dashboard') }}">
                         <img src="{{ asset('images/logo.png') }}" alt="ZeroMeal" class="h-8 w-auto">
                    </a>
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
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition font-medium group text-gray-600 hover:bg-green-50 hover:text-green-600">
                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('inventory') }}" 
                       class="flex items-center px-4 py-3 bg-green-50 text-green-700 rounded-lg transition font-medium group">
                        <svg class="mr-3 h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                       class="flex items-center px-4 py-3 rounded-lg transition font-medium group text-gray-600 hover:bg-green-50 hover:text-green-600">
                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                        Log Out
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 w-full">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">Berhasil!</strong> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">Gagal!</strong> {{ session('error') }}
                </div>
            @endif

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Inventory & Pantry</h1>
                    <p class="text-sm text-gray-500">Kelola stok bahan makananmu di sini.</p>
                </div>
                <button onclick="openModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center shadow-sm cursor-pointer">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Barang
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($inventory as $item)
                    @php
                        $statusColor = 'bg-gray-100 text-gray-600 border-gray-200';
                        if($item['status_kadaluarsa'] == 'Kadaluarsa') $statusColor = 'bg-red-100 text-red-700 border-red-200';
                        elseif ($item['status_kadaluarsa'] == 'Segera Gunakan') $statusColor = 'bg-orange-100 text-orange-700 border-orange-200';
                        elseif ($item['status_kadaluarsa'] == 'Perhatian') $statusColor = 'bg-yellow-50 text-yellow-700 border-yellow-200';
                        else $statusColor = 'bg-green-50 text-green-700 border-green-200';
                    @endphp

                    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition relative group flex flex-col h-full">
                        
                        <div class="absolute top-4 right-4 z-10 flex space-x-1">
                            <button 
                                data-item="{{ json_encode($item) }}" 
                                onclick="openEditModal(this)" 
                                class="text-gray-300 hover:text-blue-500 transition p-1 rounded-full hover:bg-blue-50" 
                                title="Edit Barang">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>

                            <form action="{{ route('inventory.delete', $item['inventaris_id']) }}" method="POST" onsubmit="return confirm('Apakah barang ini sudah habis/ingin dihapus?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-300 hover:text-red-500 transition p-1 rounded-full hover:bg-red-50" title="Hapus Barang">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>

                        <div class="mb-3">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-md border {{ $statusColor }} inline-block">
                                {{ $item['status_kadaluarsa'] }}
                            </span>
                        </div>
                        
                        <h3 class="text-lg font-bold text-gray-800 mb-1 leading-tight pr-14">{{ $item['nama_barang'] }}</h3>
                        <p class="text-sm text-gray-500 mb-6">{{ $item['kategori'] }}</p>

                        <div class="flex justify-between items-end border-t border-gray-50 pt-4 mt-auto">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">Stok</p>
                                <p class="font-medium text-gray-700">{{ $item['jumlah'] }} {{ $item['satuan'] }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">Expired</p>
                                <p class="font-medium text-gray-700">
                                    {{ \Carbon\Carbon::parse($item['tanggal_kadaluarsa'])->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16 bg-white rounded-xl border border-dashed border-gray-300">
                        <div class="text-4xl mb-3 text-gray-200">📭</div>
                        <h3 class="text-lg font-medium text-gray-900">Pantry Kosong</h3>
                        <p class="text-gray-500 mt-1">Belum ada bahan makanan yang dicatat.</p>
                    </div>
                @endforelse
            </div>
        </main>
    </div>

    <div id="addModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('inventory.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tambah Barang ke Pantry</h3>
                                <div class="mt-4">
                                    <div id="selectMode">
                                        <div class="flex justify-between items-center mb-2">
                                            <label class="block text-gray-700 text-sm font-bold">Pilih Barang</label>
                                            <button type="button" onclick="toggleInputMode()" class="text-sm font-bold text-green-600 hover:text-green-800 underline focus:outline-none" id="toggleBtn">Input Manual Baru</button>
                                        </div>
                                        <select id="selectInput" name="barang_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:ring-2 focus:ring-green-500 bg-white">
                                            <option value="">-- Cari Nama Barang --</option>
                                            @foreach($masterBarang as $b)
                                                <option value="{{ $b['barang_id'] }}">{{ $b['nama_barang'] }} ({{ $b['kategori'] }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="inputMode" class="hidden space-y-3">
                                        <div class="flex justify-between items-center mb-2">
                                             <label class="block text-gray-700 text-sm font-bold">Nama Barang Baru</label>
                                        </div>
                                        <input type="text" id="newInputName" name="nama_barang_baru" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-green-500" placeholder="Contoh: Ikan Salmon">
                                        <div class="grid grid-cols-2 gap-3 mt-3">
                                            <div>
                                                <label class="block text-gray-700 text-sm font-bold mb-1">Kategori</label>
                                                <select id="newInputCat" name="kategori_baru" class="shadow border rounded w-full py-2 px-3 bg-white focus:ring-2 focus:ring-green-500">
                                                    <option value="Protein">Protein</option>
                                                    <option value="Sayuran">Sayuran</option>
                                                    <option value="Karbohidrat">Karbohidrat</option>
                                                    <option value="Bumbu">Bumbu</option>
                                                    <option value="Bahan Pokok">Bahan Pokok</option>
                                                    <option value="Buah">Buah</option>
                                                    <option value="Lainnya">Lainnya</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-gray-700 text-sm font-bold mb-1">Satuan</label>
                                                <select id="newInputUnit" name="satuan_baru" class="shadow border rounded w-full py-2 px-3 bg-white focus:ring-2 focus:ring-green-500">
                                                    <option value="kg">Kilogram (kg)</option>
                                                    <option value="gram">Gram (gr)</option>
                                                    <option value="liter">Liter (L)</option>
                                                    <option value="ml">Mililiter (ml)</option>
                                                    <option value="pcs">Pcs / Buah</option>
                                                    <option value="ikat">Ikat</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 mt-4 border-t border-gray-100 pt-4">
                                        <div>
                                            <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah</label>
                                            <input type="number" name="jumlah" required min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500">
                                        </div>
                                        <div>
                                            <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi Simpan</label>
                                            <select name="lokasi" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500 bg-white">
                                                <option value="Kulkas">Kulkas</option>
                                                <option value="Freezer">Freezer</option>
                                                <option value="Rak Dapur">Rak Dapur</option>
                                                <option value="Lemari">Lemari</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                        <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeEditModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Edit Data Barang</h3>
                            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500">X</button>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Barang</label>
                                <select id="edit_barang_id" name="barang_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 bg-white">
                                    @foreach($masterBarang as $b)
                                        <option value="{{ $b['barang_id'] }}">{{ $b['nama_barang'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah</label>
                                    <input type="number" id="edit_jumlah" name="jumlah" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi</label>
                                    <select id="edit_lokasi" name="lokasi" class="shadow border rounded w-full py-2 px-3 text-gray-700 bg-white">
                                        <option value="Kulkas">Kulkas</option>
                                        <option value="Freezer">Freezer</option>
                                        <option value="Rak Dapur">Rak Dapur</option>
                                        <option value="Lemari">Lemari</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Kadaluarsa</label>
                                <input type="date" id="edit_tanggal" name="tanggal_kadaluarsa" class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:ring-2 focus:ring-green-500">
                                <p class="text-xs text-gray-500 mt-1">Ubah manual jika barang alami (buah/sayur) tidak sesuai estimasi 7 hari.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Update Data
                        </button>
                        <button type="button" onclick="closeEditModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // --- LOGIKA MODAL TAMBAH & TOGGLE INPUT ---
        function openModal() { document.getElementById('addModal').classList.remove('hidden'); }
        function closeModal() { document.getElementById('addModal').classList.add('hidden'); }

        let isManualMode = false;
        function toggleInputMode() {
            isManualMode = !isManualMode;
            const selectMode = document.getElementById('selectMode');
            const inputMode = document.getElementById('inputMode');
            const toggleBtn = document.getElementById('toggleBtn');
            const selectInput = document.getElementById('selectInput');
            const newInputName = document.getElementById('newInputName');

            if (isManualMode) {
                selectMode.classList.add('hidden');
                inputMode.classList.remove('hidden');
                // Pindahkan tombol ke samping label "Nama Barang Baru"
                const inputModeLabelContainer = inputMode.querySelector('.flex');
                inputModeLabelContainer.appendChild(toggleBtn);
                
                toggleBtn.innerText = "Kembali ke Pilihan List";
                selectInput.removeAttribute('required'); 
                newInputName.setAttribute('required', 'required'); 
                selectInput.value = ""; 
            } else {
                selectMode.classList.remove('hidden');
                inputMode.classList.add('hidden');
                // Kembalikan tombol ke samping label "Pilih Barang"
                const selectModeLabelContainer = selectMode.querySelector('.flex');
                selectModeLabelContainer.appendChild(toggleBtn);

                toggleBtn.innerText = "Input Manual Baru";
                selectInput.setAttribute('required', 'required'); 
                newInputName.removeAttribute('required'); 
                newInputName.value = "";
            }
        }

        // --- LOGIKA MODAL EDIT ---
        function openEditModal(element) {
            // Ambil data JSON dari atribut tombol yang diklik
            const item = JSON.parse(element.getAttribute('data-item'));

            // Isi Form Edit
            document.getElementById('edit_barang_id').value = item.barang_id;
            document.getElementById('edit_jumlah').value = item.jumlah;
            document.getElementById('edit_lokasi').value = item.lokasi_penyimpanan;
            
            // Format tanggal (ambil YYYY-MM-DD)
            const dateStr = item.tanggal_kadaluarsa.split(' ')[0]; 
            document.getElementById('edit_tanggal').value = dateStr;

            // Set Action URL Form
            const form = document.getElementById('editForm');
            form.action = "/inventory/" + item.inventaris_id;

            // Tampilkan Modal
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>

</body>
</html>