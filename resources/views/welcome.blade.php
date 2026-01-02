@extends('layouts.app')

@section('content')
<div class="relative overflow-hidden bg-white">
    <!-- Hero Section -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-32 sm:py-48 lg:py-64 text-center">
        <h1 class="mx-auto max-w-4xl font-display text-5xl font-medium tracking-tight text-slate-900 sm:text-7xl">
            Cook Smart. <span class="text-green-600">Waste Less.</span>
        </h1>
        <p class="mx-auto mt-6 max-w-2xl text-lg tracking-tight text-slate-700">
            ZeroMeal helps you plan meals, track ingredients, and save money while helping the planet. Join thousands who are making a difference today.
        </p>
        <div class="mt-10 flex justify-center gap-x-6">
            <a href="{{ route('login') }}" class="rounded-full bg-green-600 px-8 py-3 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 transition">
                Get Started
            </a>
            <a href="#" class="rounded-full bg-white border border-gray-300 px-8 py-3 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-50 transition">
                Learn more <span aria-hidden="true">→</span>
            </a>
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
