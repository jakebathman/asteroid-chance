<?php

use App\Models\SpaceObject;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $asteroid = SpaceObject::find(Cache::get('highest_risk'));

    return view('welcome', [
        'asteroid' => $asteroid,
    ]);
});
