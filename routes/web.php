<?php

use App\Models\Hit;
use App\Models\SpaceObject;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Register a webpage hit, just to track numbers (storing nothing)
    Hit::create();

    $asteroid = SpaceObject::find(Cache::get('highest_risk'));

    return view('welcome', [
        'asteroid' => $asteroid,
    ]);
});
