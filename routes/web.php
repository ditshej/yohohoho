<?php

use App\Http\Controllers\CardsController;
use App\Http\Controllers\CardsImportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('cards', [CardsController::class, 'index'])->name('cards.index');
Route::get('cards/{card}', [CardsController::class, 'show'])->name('cards.show');

Route::get('cards-import/create', [CardsImportController::class, 'create'])->name('cardsImport.create');
Route::post('cards-import', [CardsImportController::class, 'store'])->name('cardsImport.store');
