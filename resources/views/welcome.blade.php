@extends('layouts.app')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <div class="mx-auto max-w-7xl px-6 py-24 sm:py-32 lg:px-8 lg:py-40">
        <div class="grid grid-cols-1 gap-x-8 gap-y-16 lg:grid-cols-2 lg:items-center">
            <div class="max-w-2xl text-center lg:text-left">
                <h1 class="font-display text-5xl font-medium tracking-tight text-slate-900 sm:text-7xl">
                    Cook Smart. <span class="text-green-600">Waste Less.</span>
                </h1>
                <p class="mt-6 text-lg leading-8 text-slate-700">
                    ZeroMeal helps you plan meals, track ingredients, and save money while helping the planet. Join thousands who are making a difference today.
                </p>
                <div class="mt-10 flex items-center justify-center lg:justify-start gap-x-6">
                    <a href="{{ route('login') }}" class="rounded-full bg-green-600 px-8 py-3 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 transition">
                        Get Started
                    </a>
                    <a href="#" class="rounded-full bg-white border border-gray-300 px-8 py-3 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-50 transition">
                        Learn more <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>
            
            <div class="relative lg:col-span-1">
                <img src="{{ asset('images/home-bg.png') }}" alt="ZeroMeal Kitchen" class="aspect-[4/3] w-[85%] mx-auto rounded-2xl object-cover lg:aspect-[3/4]">
            </div>
        </div>
    </div>

    <!-- About Us Section -->
    <div id="about-us" class="py-24 sm:py-32 bg-white">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="relative isolate overflow-hidden bg-gray-900 px-6 py-24 text-center shadow-2xl rounded-3xl sm:px-16">
                <!-- Background visual content (optional gradient/glow) -->
                <svg viewBox="0 0 1024 1024" class="absolute left-1/2 top-1/2 -z-10 h-[64rem] w-[64rem] -translate-x-1/2 [mask-image:radial-gradient(closest-side,white,transparent)]" aria-hidden="true">
                    <circle cx="512" cy="512" r="512" fill="url(#gradient)" fill-opacity="0.7" />
                    <defs>
                        <radialGradient id="gradient">
                            <stop stop-color="#16a34a" /> <!-- Green-600 -->
                            <stop offset="1" stop-color="#15803d" /> <!-- Green-700 -->
                        </radialGradient>
                    </defs>
                </svg>
                
                <h2 class="mx-auto max-w-2xl text-3xl font-bold tracking-tight text-white sm:text-4xl">About ZeroMeal</h2>
                
                <p class="mx-auto mt-6 max-w-3xl text-lg leading-8 text-gray-300">
                    ZeroMeal is a smart kitchen assistant application designed to manage food inventory, reduce food waste, and simplify daily grocery shopping across multiple platforms. Equipped with an expiration date tracker via manual input, recipe recommendations based on available ingredients, and meal planning features, ZeroMeal helps users save money while encouraging more mindful consumption habits.
                </p>


            </div>
        </div>
    </div>

    <!-- Features Section placeholder -->
    <div class="bg-green-600 py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:text-center">
                <h2 class="text-base font-semibold leading-7 text-green-100">Faster & Smarter</h2>
                <p class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">Everything you need to manage your kitchen</p>
            </div>
            <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-4xl">
                <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-10 lg:max-w-none lg:grid-cols-2 lg:gap-y-16">
                    <div class="relative pl-16">
                        <dt class="text-base font-semibold leading-7 text-white">
                            <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-white">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3.75m-3-3.75l-3 3.75M12 9.75V4.5m0 12.75l2.25 2.25m-2.25-2.25l-2.25 2.25" />
                                </svg>
                            </div>
                            Zero Waste Tracking
                        </dt>
                        <dd class="mt-2 text-base leading-7 text-green-100">Keep track of expiration dates and get notified before food goes bad.</dd>
                    </div>
                    <div class="relative pl-16">
                        <dt class="text-base font-semibold leading-7 text-white">
                            <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-white">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.864 4.243A7.5 7.5 0 0119.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 004.5 10.5a7.464 7.464 0 01-1.15 3.993m1.989 3.559A11.209 11.209 0 008.25 10.5a3.75 3.75 0 117.5 0c0 .527-.021 1.049-.064 1.565M12 10.5a14.94 14.94 0 01-3.6 9.75m6.633-4.596a18.666 18.666 0 01-2.485 5.33" />
                                </svg>
                            </div>
                            Smart Recipes
                        </dt>
                        <dd class="mt-2 text-base leading-7 text-green-100">Get recipe suggestions based on ingredients you already have.</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
