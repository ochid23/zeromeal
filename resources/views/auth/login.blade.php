@extends('layouts.guest')

@section('content')
<div class="flex min-h-screen bg-white">
    <!-- Left Side: Form -->
    <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 lg:px-24 py-12 relative">
        <!-- Back Button -->
        <a href="{{ route('home') }}" class="absolute top-8 left-8 text-gray-500 hover:text-green-600 flex items-center gap-2 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back
        </a>

        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="block mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="ZeroMeal Logo" class="h-10 w-auto">
            </a>

            <h2 class="text-3xl font-bold tracking-tight text-gray-900">
                Sign in to your account
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Not a member? 
                <a href="{{ route('register') }}" class="font-medium text-green-600 hover:text-green-500">
                    Register here for free
                </a>
            </p>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-md">
            
            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 font-medium text-sm text-red-600">
                    {{ session('error') }}
                </div>
            @endif

            <form class="space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
                    <div class="mt-2">
                        <input id="email" name="email" type="email" autocomplete="email" required class="block w-full rounded-md border-0 py-2.5 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6" value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                    <div class="mt-2">
                        <input id="password" name="password" type="password" autocomplete="current-password" required class="block w-full rounded-md border-0 py-2.5 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                    </div>
                     @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-600">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-900">Remember me</label>
                    </div>

                    <div class="text-sm">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="font-medium text-green-600 hover:text-green-500">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                </div>

                <div>
                    <button type="submit" class="flex w-full justify-center rounded-md bg-green-600 px-3 py-2.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                        Sign In
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Side: Image -->
    <div class="hidden lg:block relative w-1/2">
        <img class="absolute inset-0 h-full w-full object-cover" src="{{ asset('images/login-bg.jpg') }}" alt="Food background">
        <div class="absolute bottom-4 left-6 text-white bg-black/40 px-3 py-1 rounded text-xs backdrop-blur-sm">
            Photo by Joseph Gonzalez
        </div>
    </div>
</div>
@endsection
