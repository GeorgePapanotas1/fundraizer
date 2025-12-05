<?php

use Illuminate\Support\Facades\Route;

// Serve the Vue SPA for all frontend routes.
Route::get('/{any?}', function () {
    return view('spa');
})->where('any', '.*');
