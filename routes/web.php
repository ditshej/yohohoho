<?php

use App\Http\Controllers\CardsController;
use App\Http\Controllers\CardsImportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('cards', [CardsController::class, 'index'])->name('cards.index');
Route::get('cards/{card}', [CardsController::class, 'show'])->name('cards.show');

Route::get('cards-import/create', [CardsImportController::class, 'create'])->name('cards-import.create');
Route::post('cards-import', [CardsImportController::class, 'store'])->name('cards-import.store');
Route::post('cards-import/api', [CardsImportController::class, 'storeFromApi'])->name('cards-import.storeFromApi');
