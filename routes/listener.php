<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'listener.dashboard')->name('dashboard');
