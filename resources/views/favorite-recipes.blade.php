@extends('layouts.dashboard')

@section('content')
    <div class="space-y-6">
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Favorite Recipes
                </h2>
            </div>
        </div>

        <!-- Recipe Grid -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3" id="favorite-grid">
            @forelse($favorites as $recipe)
                <div
                    class="group relative flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="aspect-h-4 aspect-w-3 bg-gray-200 sm:aspect-none group-hover:opacity-75 sm:h-52">
                        <img src="{{ $recipe['image'] }}" alt="{{ $recipe['title'] }}"
                            class="h-full w-full object-cover object-center sm:h-full sm:w-full">
                    </div>
                    <div class="flex flex-1 flex-col space-y-2 p-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            <a href="#">
                                <span aria-hidden="true" class="absolute inset-0"></span>
                                {{ $recipe['title'] }}
                            </a>
                        </h3>
                        <p class="text-sm text-gray-500">{{ $recipe['desc'] }}</p>
                        <div class="mt-auto flex items-center justify-between pt-2">
                            <div class="flex items-center space-x-1 text-sm text-gray-500">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $recipe['time'] }}</span>
                            </div>
                            <button onclick="removeFavorite({{ $recipe['id'] }}, this)"
                                class="relative z-10 p-1 text-red-500 hover:text-red-700 transition-colors">
                                <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-gray-500 col-span-full text-center py-10" id="empty-message">
                    No favorite recipes yet.
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function removeFavorite(id, btn) {
            if (!confirm('Remove this recipe from favorites?')) return;

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
                    if (data.status === 'success' && !data.is_favorite) {
                        // Remove the card
                        const card = btn.closest('.group');
                        card.remove();

                        // Check if grid is empty
                        const grid = document.getElementById('favorite-grid');
                        // We check if there are any recipe cards left. Use logic based on children count.
                        // Note: if 'empty-message' exists initially, it's 1 child. If we remove last card, 0 children.
                        if (grid.querySelectorAll('.group').length === 0) {
                            grid.innerHTML = '<div class="text-gray-500 col-span-full text-center py-10">No favorite recipes yet.</div>';
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
@endsection