<?php

use App\Http\Controllers\CardsImportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('cards-import/create', [CardsImportController::class, 'create'])->name('cardsImport.create');
Route::post('cards-import', [CardsImportController::class, 'store'])->name('cardsImport.store');
