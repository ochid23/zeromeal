@extends('layouts.dashboard')

@section('content')


    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Recipes</h1>
            <p class="text-sm text-gray-500">Rekomendasi masakan berdasarkan stok bahanmu.</p>
        </div>
    </div>

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
                        <button class="text-sm font-semibold text-green-600 hover:text-green-700">Lihat Detail →</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    </main>

    <script>
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