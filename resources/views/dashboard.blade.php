@extends('layouts.dashboard')

@section('content')
    {{-- ONBOARDING MODAL --}}
    @if(isset($isEmptyInventory) && $isEmptyInventory)
    <div id="onboardingModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/90 backdrop-blur-sm transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl w-full">
                
                {{-- STEP 1: Survey --}}
                <div id="step1" class="p-8 sm:p-12">
                    <div class="text-center mb-10">
                        <h3 class="text-3xl font-bold text-gray-900 mb-4">Dari mana kamu tahu tentang ZeroMeal?</h3>
                        <p class="text-lg text-gray-500">Pilih salah satu opsi di bawah ini.</p>
                    </div>
                        
                    <input type="hidden" id="sourceSelect" value="">

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12">
                        <!-- Option 1: Social Media -->
                        <div onclick="selectOption('social_media', this)" class="survey-card cursor-pointer relative bg-white border-2 border-gray-100 rounded-2xl p-8 hover:shadow-lg transition-all duration-300 flex flex-col items-center justify-center text-center h-64 group">
                            <div class="absolute top-4 right-4 w-6 h-6 bg-green-600 rounded-full text-white flex items-center justify-center opacity-0 check-icon transition-opacity">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div class="w-16 h-16 mb-6 text-green-600 bg-green-50 rounded-full flex items-center justify-center group-hover:bg-green-100 transition-colors">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"></path></svg>
                            </div>
                            <span class="text-lg font-semibold text-gray-900 mb-1">Social Media</span>
                            <span class="text-sm text-gray-500">IG, TikTok, FB</span>
                        </div>

                        <!-- Option 2: Search Engine -->
                        <div onclick="selectOption('search', this)" class="survey-card cursor-pointer relative bg-white border-2 border-gray-100 rounded-2xl p-8 hover:shadow-lg transition-all duration-300 flex flex-col items-center justify-center text-center h-64 group">
                            <div class="absolute top-4 right-4 w-6 h-6 bg-green-600 rounded-full text-white flex items-center justify-center opacity-0 check-icon transition-opacity">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div class="w-16 h-16 mb-6 text-green-600 bg-green-50 rounded-full flex items-center justify-center group-hover:bg-green-100 transition-colors">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <span class="text-lg font-semibold text-gray-900 mb-1">Search Engine</span>
                            <span class="text-sm text-gray-500">Google, Bing</span>
                        </div>

                        <!-- Option 3: Friends -->
                        <div onclick="selectOption('friend', this)" class="survey-card cursor-pointer relative bg-white border-2 border-gray-100 rounded-2xl p-8 hover:shadow-lg transition-all duration-300 flex flex-col items-center justify-center text-center h-64 group">
                            <div class="absolute top-4 right-4 w-6 h-6 bg-green-600 rounded-full text-white flex items-center justify-center opacity-0 check-icon transition-opacity">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div class="w-16 h-16 mb-6 text-green-600 bg-green-50 rounded-full flex items-center justify-center group-hover:bg-green-100 transition-colors">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <span class="text-lg font-semibold text-gray-900 mb-1">Teman / Keluarga</span>
                            <span class="text-sm text-gray-500">Rekomendasi</span>
                        </div>
                    </div>

                    <div class="flex justify-end items-center pt-4">
                        <button onclick="goToStep(2)" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg text-base font-bold transition-all shadow-lg hover:shadow-xl flex items-center transform hover:-translate-y-0.5">
                            Continue
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </div>

                {{-- STEP 2: Ask to Add Item --}}
                <div id="step2" class="hidden p-8 sm:p-12">
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
                         <button onclick="goToStep(1)" class="mt-8 text-gray-400 hover:text-gray-600 font-medium">
                            &larr; Kembali
                        </button>
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

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
                
                <div class="bg-green-50 border border-green-100 rounded-lg p-4 mb-6">
                    <p class="text-green-800">
                        You are successfully logged in! 
                        @if(Session::has('user'))
                            Welcome, <span class="font-bold">{{ Auth::user()->name ?? Session::get('user')['nama'] ?? 'User' }}</span>.
                        @endif
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Feature Card 1 -->
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition">
                        <div class="h-10 w-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">My Recipes</h3>
                        <p class="text-gray-600 text-sm">Manage your saved recipes and meal plans.</p>
                    </div>

                    <!-- Feature Card 2 -->
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition">
                        <div class="h-10 w-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Browse Catalog</h3>
                        <p class="text-gray-600 text-sm">Discover new sustainable meal ideas.</p>
                    </div>

                    <!-- Feature Card 3 -->
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition">
                        <div class="h-10 w-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Profile</h3>
                        <p class="text-gray-600 text-sm">Update your preferences and settings.</p>
                    </div>
                </div>
    </div>
</div>
@endsection
