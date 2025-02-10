<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/admin', function () {
    return view('pages.dashbord');
})->name('admin.dashboard');

Route::get('/all_Events', function () {
    return view('pages.events.all');
})->name('allEvents');


Route::get('/eventByid', [EventController::class, 'showEventPageById'])->name('event.show');


///////////////////////////*******EVENT*********//////////////////////





