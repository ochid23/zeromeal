@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
                
                <div class="bg-green-50 border border-green-100 rounded-lg p-4 mb-6">
                    <p class="text-green-800">
                        You are successfully logged in! 
                        @if(Session::has('user'))
                            Welcome, <span class="font-bold">{{ Session::get('user')['name'] ?? 'User' }}</span>.
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
    </div>
</div>
@endsection
