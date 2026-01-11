@extends('layouts.admin')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-base font-semibold leading-6 text-gray-900">Create Recipe</h3>
                    <p class="mt-1 text-sm text-gray-600">Add a new recipe to the database.</p>
                </div>
            </div>
            <div class="mt-5 md:col-span-2 md:mt-0">
                <form action="{{ route('admin.recipes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="shadow sm:overflow-hidden sm:rounded-md">
                        <div class="space-y-6 bg-white px-4 py-5 sm:p-6">

                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6">
                                    <label for="judul"
                                        class="block text-sm font-medium leading-6 text-gray-900">Title</label>
                                    <input type="text" name="judul" id="judul" required
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>

                                <div class="col-span-6">
                                    <label for="deskripsi"
                                        class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                                    <textarea name="deskripsi" id="deskripsi" rows="3" required
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="difficulty"
                                        class="block text-sm font-medium leading-6 text-gray-900">Difficulty</label>
                                    <select id="difficulty" name="difficulty"
                                        class="mt-2 block w-full rounded-md border-0 bg-white py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="Easy">Easy</option>
                                        <option value="Medium">Medium</option>
                                        <option value="Hard">Hard</option>
                                    </select>
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="image_url" class="block text-sm font-medium leading-6 text-gray-900">Image
                                        URL</label>
                                    <input type="text" name="image_url" id="image_url"
                                        placeholder="http://example.com/image.jpg"
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="time_estimate"
                                        class="block text-sm font-medium leading-6 text-gray-900">Time Estimate
                                        (Minutes)</label>
                                    <input type="number" name="time_estimate" id="time_estimate" required
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="calories" class="block text-sm font-medium leading-6 text-gray-900">Calories
                                        (kcal)</label>
                                    <input type="number" name="calories" id="calories" required
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>

                                <!-- Add other fields (carbs, protein, fat) if needed based on provided screenshot -->
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                            <a href="{{ route('admin.dashboard') }}"
                                class="text-sm font-semibold leading-6 text-gray-900 mr-4">Cancel</a>
                            <button type="submit"
                                class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection