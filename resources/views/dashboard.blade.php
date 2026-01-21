@extends('layouts.dashboard')

@section('content')
    {{-- ONBOARDING MODAL --}}
    @if(isset($isEmptyInventory) && $isEmptyInventory)
    <div id="onboardingModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/90 backdrop-blur-sm transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl w-full">
                
                {{-- REMOVED STEP 1: Survey (Already done in Onboarding page) --}}

                {{-- STEP 2: Ask to Add Item --}}
                <div id="step2" class="p-8 sm:p-12">
                    <div class="text-center h-full flex flex-col justify-center items-center py-10">
                        <div class="w-24 h-24 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mb-8 animate-bounce">
                            <span class="text-4xl">📦</span>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-900 mb-4">Mau input barang pertamamu?</h3>
                        <p class="text-lg text-gray-500 max-w-lg mb-10">Pantry-mu masih kosong nih. Yuk tambahkan satu barang biar bisa langsung coba fitur ZeroMeal!</p>
                        
                        <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-6 w-full justify-center">
                            <button onclick="goToStep(3)" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-xl text-lg font-bold transition-all shadow-lg hover:shadow-xl flex items-center justify-center transform hover:-translate-y-1">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Ya, Input Sekarang
                            </button>
                            <button onclick="goToStep(4)" class="w-full sm:w-auto bg-white border-2 border-gray-200 text-gray-600 hover:text-gray-800 hover:border-gray-400 px-8 py-4 rounded-xl text-lg font-bold transition-all flex items-center justify-center">
                                Tidak, Nanti Saja
                            </button>
                        </div>
                         {{-- Back button removed since Step 1 is gone --}}
                    </div>
                </div>

                {{-- STEP 3: Form Input (Was Step 2) --}}
                <div id="step3" class="hidden p-8 sm:p-12">
                    <div class="text-left mb-6">
                        <div class="flex items-center justify-between mb-4">
                             <h3 class="text-2xl font-bold text-gray-900">Isi Data Barang</h3>
                             <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-xl">📝</span>
                             </div>
                        </div>
                        <p class="text-gray-500 border-b pb-4">Isi detail lengkap barang yang ingin kamu simpan.</p>
                    </div>

                    <form action="{{ route('inventory.store') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        {{-- Nama Barang --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Barang Baru</label>
                            <input type="text" name="nama_barang_baru" required 
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 py-2.5 px-4"
                                placeholder="Contoh: Ikan Salmon">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                             {{-- Kategori --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                                <select name="kategori_baru" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 py-2.5 px-4 bg-white">
                                    <option value="Bahan Pokok">Bahan Pokok</option>
                                    <option value="Sayuran">Sayuran</option>
                                    <option value="Protein">Protein</option>
                                    <option value="Buah">Buah</option>
                                    <option value="Bumbu">Bumbu</option>
                                    <option value="Minuman">Minuman</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                             {{-- Satuan --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Satuan</label>
                                <select name="satuan_baru" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 py-2.5 px-4 bg-white">
                                    <option value="pcs">Pcs / Buah</option>
                                    <option value="kg">Kilogram (kg)</option>
                                    <option value="gram">Gram (gr)</option>
                                    <option value="liter">Liter (L)</option>
                                    <option value="ml">Mililiter (ml)</option>
                                    <option value="ikat">Ikat</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                             {{-- Harga --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Harga (Rp)</label>
                                <input type="number" name="harga" placeholder="0" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 py-2.5 px-4">
                            </div>
                             {{-- Jumlah --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah</label>
                                <input type="number" name="jumlah" value="1" min="1" required 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 py-2.5 px-4">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                             {{-- Tanggal Pembelian --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Pembelian</label>
                                <input type="date" name="tanggal_pembelian" value="{{ date('Y-m-d') }}" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 py-2.5 px-4">
                            </div>
                             {{-- Tanggal Kadaluarsa --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Kadaluarsa</label>
                                <input type="date" name="tanggal_kadaluarsa" required 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 py-2.5 px-4">
                            </div>
                        </div>

                        {{-- Lokasi Simpan --}}
                        <div>
                             <label class="block text-sm font-bold text-gray-700 mb-2">Lokasi Simpan</label>
                             <select name="lokasi" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 py-2.5 px-4 bg-white">
                                <option value="Kulkas">Kulkas</option>
                                <option value="Freezer">Freezer</option>
                                <option value="Rak Dapur">Rak Dapur</option>
                                <option value="Lemari">Lemari</option>
                            </select>
                        </div>
                        
                        {{-- Catatan --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Catatan (Opsional)</label>
                            <textarea name="catatan" rows="2" 
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 py-2.5 px-4"
                                placeholder="Contoh: Beli di pasar, merek X..."></textarea>
                        </div>
                        
                        <div class="pt-6 flex justify-between items-center bg-gray-50 -mx-8 -mb-12 p-8 border-t mt-4 rounded-b-2xl">
                            <button type="button" onclick="goToStep(2)" class="text-gray-500 font-medium hover:text-gray-700 bg-white border border-gray-300 px-6 py-2.5 rounded-lg shadow-sm">Batal</button>
                            <button type="submit" class="bg-green-600 text-white px-8 py-2.5 rounded-lg font-bold shadow-lg hover:bg-green-700 transition-all transform hover:-translate-y-0.5">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- STEP 4: Welcome / Success (If chose No) --}}
                <div id="step4" class="hidden p-8 sm:p-12">
                     <div class="text-center h-full flex flex-col justify-center items-center py-10">
                        <div class="w-24 h-24 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-8">
                            <span class="text-4xl">🎉</span>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-900 mb-4">Selamat Bergabung!</h3>
                        <p class="text-lg text-gray-500 max-w-lg mb-10">Akunmu sudah siap. Jelajahi fitur-fitur ZeroMeal untuk mulai hidup lebih *zero waste*!</p>
                        
                        <button onclick="skipOnboarding()" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-10 py-4 rounded-xl text-lg font-bold transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            Mulai Jelajahi Dashboard 🚀
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Check if onboarding was already completed/dismissed
        document.addEventListener('DOMContentLoaded', function() {
            if (localStorage.getItem('onboarding_dismissed') === 'true') {
                const modal = document.getElementById('onboardingModal');
                if (modal) modal.remove(); // Completely remove it
            }
        });

        function selectOption(value, element) {
            // Update hidden input
            document.getElementById('sourceSelect').value = value;

            // Reset all cards
            document.querySelectorAll('.survey-card').forEach(card => {
                card.classList.remove('border-green-600', 'ring-2', 'ring-green-100');
                card.classList.add('border-gray-100');
                card.querySelector('.check-icon').classList.add('opacity-0');
            });

            // Activate clicked card
            element.classList.remove('border-gray-100');
            element.classList.add('border-green-600', 'ring-2', 'ring-green-100');
            element.querySelector('.check-icon').classList.remove('opacity-0');
        }

        function goToStep(stepNumber) {
            // Validasi Step 1
            if(stepNumber === 2) {
                const source = document.getElementById('sourceSelect').value;
                if(!source || source === "") {
                    alert('Silakan pilih salah satu opsi survei terlebih dahulu.');
                    return;
                }
            }

            // Hide all steps
            for(let i = 1; i <= 4; i++) {
                const step = document.getElementById('step' + i);
                if(step) step.classList.add('hidden');
            }

            // Show target step
            const target = document.getElementById('step' + stepNumber);
            if(target) target.classList.remove('hidden');
        }

        // Wrapper for old nextStep call just in case
        function nextStep() {
            goToStep(2);
        }

        function skipOnboarding() {
            // Set flag in localStorage so it doesn't show again
            localStorage.setItem('onboarding_dismissed', 'true');
            
            const modal = document.getElementById('onboardingModal');
            if(modal) {
                modal.classList.add('hidden');
                setTimeout(() => modal.remove(), 500); // Remove from DOM after transition
            }
        }
    </script>
    @endif
    {{-- END ONBOARDING MODAL --}}

                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Overview</h1>
                    <p class="text-gray-500">Welcome back, <span class="font-semibold text-gray-700">{{ Auth::user()->name ?? data_get(Session::get('user'), 'nama') ?? 'User' }}</span>!</p>
                </div>

                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Inventory Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-medium text-gray-500">Total Inventory</h3>
                            <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalInventory }}</p>
                        <p class="text-xs text-gray-500 mt-1">Items in pantry</p>
                    </div>

                    <!-- Expiring Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-medium text-gray-500">Expiring Soon</h3>
                            <div class="p-2 bg-amber-50 rounded-lg text-amber-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalExpiring }}</p>
                        <p class="text-xs text-amber-600 mt-1">Items expire in ≤ 7 days</p>
                    </div>

                    <!-- Shopping List Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-medium text-gray-500">To Buy</h3>
                            <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalShoppingItems }}</p>
                        <p class="text-xs text-gray-500 mt-1">Est: Rp {{ number_format($totalShoppingCost, 0, ',', '.') }}</p>
                    </div>

                    <!-- Budget Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-medium text-gray-500">Weekly Budget</h3>
                            <div class="p-2 bg-green-50 rounded-lg text-green-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                        <p class="text-lg font-bold text-gray-900 truncate">Rp {{ number_format($userBudget - $totalShoppingCost, 0, ',', '.') }}</p>
                        <p class="text-xs text-{{ ($userBudget - $totalShoppingCost) < 0 ? 'red' : 'green' }}-600 mt-1">Remaining from Rp {{ number_format($userBudget, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Recent Activity Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Expiring Alert (Left Col) -->
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900">⚠️ Expiring Soon</h3>
                            <a href="{{ route('inventory') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">View All</a>
                        </div>
                        @if(count($expiringItems) > 0)
                            <div class="space-y-3">
                                @foreach($expiringItems as $item)
                                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-100">
                                        <div class="flex items-center">
                                            <div class="w-2 h-2 rounded-full bg-red-500 mr-3"></div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-900">{{ $item['name'] }}</p>
                                                <p class="text-xs text-red-600">{{ $item['days_left'] }} days left</p>
                                            </div>
                                        </div>
                                        <span class="text-sm font-medium text-gray-600">{{ $item['qty'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <p>No items expiring soon! 🎉</p>
                            </div>
                        @endif
                    </div>

                    <!-- Recommended Recipe (Right Col) -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900">🍽️ For You</h3>
                            <a href="{{ route('recipes') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">Browse</a>
                        </div>
                        @if(count($recipes) > 0)
                            <div class="space-y-4">
                                @foreach(collect($recipes)->take(3) as $recipe)
                                    <div class="flex items-start space-x-3 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                                        <img src="{{ $recipe['image'] }}" class="w-12 h-12 rounded-lg object-cover bg-gray-200" alt="">
                                        <div>
                                            <p class="text-sm font-bold text-gray-900 line-clamp-1">{{ $recipe['title'] }}</p>
                                            <p class="text-xs text-green-600">{{ $recipe['match'] }}% Match</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <p>Add ingredients to get recommendations.</p>
                            </div>
                        @endif
                    </div>
                </div>
    </div>
</div>
@endsection
