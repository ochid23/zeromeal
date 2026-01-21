@extends('layouts.dashboard')

@section('content')


@section('header_search')
    <div class="flex flex-col justify-center">
        <h1 class="text-xl font-bold text-gray-900 leading-tight">Recipes</h1>
        <p class="text-xs text-gray-500 hidden sm:block">Rekomendasi masakan berdasarkan stok.</p>
    </div>
@endsection

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($recipes as $recipe)
            @php
                $isFavorite = in_array($recipe['resep_id'] ?? $recipe['id'] ?? 0, $favorites);
                $id = $recipe['resep_id'] ?? $recipe['id'] ?? 0;
            @endphp
            <div
                class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition flex flex-col h-full">
                <div class="h-48 w-full bg-gray-200 relative">
                    <img src="{{ $recipe['image_url'] ?? 'https://placehold.co/600x400' }}"
                        alt="{{ $recipe['judul'] ?? 'Recipe' }}" class="w-full h-full object-cover">
                    <div class="absolute top-3 right-3 bg-white/90 backdrop-blur px-2.5 py-1 rounded-md shadow-sm">
                        <div class="text-xs font-bold text-green-700 flex items-center">
                            {{ $recipe['difficulty'] ?? 'Easy' }}
                        </div>
                    </div>
                    <button onclick="toggleFavorite({{ $id }}, this)"
                        class="absolute top-3 left-3 p-2 rounded-full bg-white/90 backdrop-blur shadow-sm hover:bg-white transition-colors group {{ $isFavorite ? 'text-red-500' : 'text-gray-400 hover:text-red-500' }}">
                        <svg class="w-5 h-5 {{ $isFavorite ? 'fill-current' : 'group-hover:fill-current' }}"
                            fill="{{ $isFavorite ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <h4 class="font-bold text-gray-900 text-lg mb-2">{{ $recipe['judul'] ?? 'No Title' }}</h4>
                    <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $recipe['deskripsi'] ?? '' }}</p>

                    <div class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between">
                        <div class="text-xs text-gray-500 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $recipe['waktu_pembuatan_menit'] ?? 0 }} min
                        </div>
                        <button onclick="openRecipeModal({{ $id }})" class="text-sm font-semibold text-green-600 hover:text-green-700">Lihat Detail →</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Recipe Detail Modal -->
    <div id="recipeModal" class="fixed inset-0 z-50 overflow-y-auto hidden" style="z-index: 9999;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeRecipeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full relative z-50">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-2xl leading-6 font-bold text-gray-900 mb-2" id="modal-title"></h3>
                            <p id="modal-description" class="text-sm text-gray-500 mb-4"></p>
                            
                            <div class="mb-4">
                                <img id="modal-image" src="" alt="Recipe Image" class="w-full h-64 object-cover rounded-lg">
                            </div>

                            <!-- Highlights -->
                            <div class="flex flex-wrap gap-2 mb-6">
                                <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span id="modal-time"></span>
                                </span>
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded flex items-center">
                                    Difficulty: <span id="modal-difficulty" class="ml-1"></span>
                                </span>
                                <span class="bg-orange-100 text-orange-800 text-xs font-semibold px-2.5 py-0.5 rounded flex items-center">
                                    <span id="modal-calories"></span>
                                </span>
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded flex items-center">
                                    Rating: <span id="modal-rating" class="ml-1"></span>
                                </span>
                            </div>

                            <!-- Nutrition Grid -->
                            <div class="grid grid-cols-3 gap-4 mb-6 text-center">
                                <div class="bg-blue-50 p-3 rounded-lg border border-blue-100">
                                    <span class="block text-xs text-gray-500 font-bold uppercase">Karbo</span>
                                    <span id="modal-carbs" class="text-lg font-bold text-blue-600"></span>
                                </div>
                                <div class="bg-red-50 p-3 rounded-lg border border-red-100">
                                    <span class="block text-xs text-gray-500 font-bold uppercase">Protein</span>
                                    <span id="modal-protein" class="text-lg font-bold text-red-600"></span>
                                </div>
                                <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-100">
                                    <span class="block text-xs text-gray-500 font-bold uppercase">Lemak</span>
                                    <span id="modal-fat" class="text-lg font-bold text-yellow-600"></span>
                                </div>
                            </div>

                            <!-- Fun Fact -->
                            <div id="modal-funfact-container" class="bg-purple-50 border-l-4 border-purple-500 p-4 mb-6 rounded-r-lg hidden">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-purple-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-purple-700">
                                            <span class="font-bold">Fun Fact:</span> <span id="modal-funfact"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <h4 class="font-bold text-lg text-gray-800 mb-3 border-b pb-2">Bahan-bahan</h4>
                                    <ul id="modal-ingredients" class="list-disc list-inside text-sm text-gray-600 space-y-1">
                                        <!-- Ingredients injected here -->
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="font-bold text-lg text-gray-800 mb-3 border-b pb-2">Instruksi</h4>
                                    <div id="modal-instructions" class="text-sm text-gray-600 whitespace-pre-line leading-relaxed">
                                        <!-- Instructions injected here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeRecipeModal()">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    

    <script>
        function openRecipeModal(id) {
            // Reset and Show loading state
            document.getElementById('modal-title').innerText = 'Loading...';
            document.getElementById('modal-description').innerText = '';
            document.getElementById('modal-ingredients').innerHTML = '';
            document.getElementById('modal-instructions').innerText = '';
            document.getElementById('modal-image').src = '';
            document.getElementById('modal-funfact-container').classList.add('hidden');
            
            // Show modal
            document.getElementById('recipeModal').classList.remove('hidden');

            fetch(`/recipes/${id}/details`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        const recipe = data.recipe;
                        const ingredients = data.ingredients;

                        document.getElementById('modal-title').innerText = recipe.judul;
                        document.getElementById('modal-description').innerText = recipe.deskripsi || '';
                        document.getElementById('modal-image').src = recipe.image_url || 'https://placehold.co/600x400';
                        document.getElementById('modal-instructions').innerText = recipe.langkah || 'Tidak ada instruksi.';
                        
                        document.getElementById('modal-time').innerText = `${recipe.waktu_pembuatan_menit || 0} min`;
                        document.getElementById('modal-calories').innerText = `${recipe.kalori_per_porsi || 0} kcal`;
                        document.getElementById('modal-difficulty').innerText = recipe.difficulty || '-';
                        document.getElementById('modal-rating').innerText = recipe.rating ? `${recipe.rating}/5` : '-';

                        document.getElementById('modal-carbs').innerText = `${recipe.karbohidrat_gram || 0}g`;
                        document.getElementById('modal-protein').innerText = `${recipe.protein_gram || 0}g`;
                        document.getElementById('modal-fat').innerText = `${recipe.lemak_gram || 0}g`;

                        if (recipe.fun_fact) {
                            document.getElementById('modal-funfact').innerText = recipe.fun_fact;
                            document.getElementById('modal-funfact-container').classList.remove('hidden');
                        }

                        const ingredientsList = document.getElementById('modal-ingredients');
                        ingredientsList.innerHTML = '';
                        if (ingredients.length > 0) {
                            ingredients.forEach(ing => {
                                const li = document.createElement('li');
                                li.innerText = `${ing.nama_bahan}: ${ing.jumlah_dibutuhkan} ${ing.satuan}`;
                                ingredientsList.appendChild(li);
                            });
                        } else {
                            ingredientsList.innerHTML = '<li>Tidak ada data bahan.</li>';
                        }
                    } else {
                        alert('Gagal memuat resep: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error fetching recipe details:', error);
                    document.getElementById('modal-title').innerText = 'Error loading recipe';
                    alert('Terjadi kesalahan saat memuat detail resep. Silakan coba lagi.');
                });
        }

        function closeRecipeModal() {
            document.getElementById('recipeModal').classList.add('hidden');
        }

        function toggleFavorite(id, btn) {
            fetch('{{ route("toggle-favorite") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: id })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const svg = btn.querySelector('svg');
                        if (data.is_favorite) {
                            btn.classList.remove('text-gray-400', 'hover:text-red-500');
                            btn.classList.add('text-red-500');
                            svg.setAttribute('fill', 'currentColor');
                            svg.classList.add('fill-current');
                            svg.classList.remove('group-hover:fill-current');
                        } else {
                            btn.classList.add('text-gray-400', 'hover:text-red-500');
                            btn.classList.remove('text-red-500');
                            svg.setAttribute('fill', 'none');
                            svg.classList.remove('fill-current');
                            svg.classList.add('group-hover:fill-current');
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
@endsection