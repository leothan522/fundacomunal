<?php

use Illuminate\Support\Facades\Route;
use SweetAlert2\Laravel\Swal;

Route::get('/', function () {
    Swal::fire([
        'title' => 'Laravel + SweetAlert2 = <3',
        'text' => 'This is a simple alert using SweetAlert2',
        'icon' => 'success',
        'confirmButtonText' => 'Cool'
    ]);
    return view('welcome');
})->name('web.index');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/home', function () {
        return view('dashboard');
    })->name('home');
});
