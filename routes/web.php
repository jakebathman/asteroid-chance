<?php

use App\Models\Hit;
use App\Models\SpaceObject;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Register a webpage hit, just to track numbers (storing nothing)
    Hit::create();

    $asteroid = SpaceObject::find(Cache::get('highest_risk'));

    // Force to use 2024 YR4 for now
    $asteroid = SpaceObject::where('designation', '2024 YR4')->first();

    return view('welcome', [
        'asteroid' => $asteroid,
    ]);
});
