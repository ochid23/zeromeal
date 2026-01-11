@extends('layouts.admin')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-semibold text-gray-900">Recipes Management</h1>
                <p class="mt-2 text-sm text-gray-700">Add, edit, or remove recipes for the ZeroMeal application.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('admin.recipes.create') }}"
                    class="block rounded-md bg-green-600 px-4 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                    + Add New Recipe
                </a>
            </div>
        </div>

        <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">

                    @if(session('success'))
                        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center"
                            role="alert">
                             <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="block sm:inline font-medium">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg" role="alert">
                            <span class="block sm:inline">{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="py-3.5 pl-4 pr-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500 sm:pl-6">ID
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Image
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Title
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-gray-500">
                                        Description</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-gray-500">
                                        Difficulty</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($recipes as $recipe)
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                            #{{ $recipe['resep_id'] ?? $loop->iteration }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <div class="h-16 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                                <img src="{{ $recipe['image_url'] ?? 'https://placehold.co/100x100' }}" alt=""
                                                    class="h-full w-full object-cover object-center">
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $recipe['judul'] ?? 'No Title' }}</td>
                                        <td class="px-3 py-4 text-sm text-gray-500 max-w-xs truncate">
                                            {{ $recipe['deskripsi'] ?? '-' }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <span
                                                class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">{{ $recipe['difficulty'] ?? 'Easy' }}</span>
                                        </td>
                                        <td
                                            class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('admin.recipes.edit', $recipe['resep_id'] ?? 0) }}"
                                                    class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('admin.recipes.destroy', $recipe['resep_id'] ?? 0) }}"
                                                    method="POST" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-3 py-4 text-sm text-center text-gray-500">No recipes found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection