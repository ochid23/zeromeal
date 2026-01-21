<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// This application is a frontend consumer. All API logic resides in zeromeal-api.
// Local API routes are disabled to prevent confusion and direct DB access.

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');