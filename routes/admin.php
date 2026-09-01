<?php

use App\Livewire\Admin\Listeners\Form as ListenerForm;
use App\Livewire\Admin\Listeners\Index as ListenersIndex;
use Illuminate\Support\Facades\Route;

Route::view('/', 'admin.dashboard')->name('dashboard');

Route::get('listeners', ListenersIndex::class)->name('listeners.index');
Route::get('listeners/create', ListenerForm::class)->name('listeners.create');
Route::get('listeners/{user}/edit', ListenerForm::class)->name('listeners.edit');
