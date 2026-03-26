<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use Illuminate\Support\Facades\Route;

// Page d'accueil redirige vers le catalogue
Route::get('/', function () {
    return redirect()->route('products.index');
});

// ESPACE CLIENT (connecté)
Route::middleware(['auth'])->group(function () {

    // Catalogue des burgers
    Route::get('/products', [ProductController::class, 'index'])
        ->name('products.index');
    Route::get('/products/{slug}', [ProductController::class, 'show'])
        ->name('products.show');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ESPACE GESTIONNAIRE
Route::middleware(['auth', 'role:gestionnaire'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // CRUD Produits admin
        Route::resource('products', AdminProductController::class);
        Route::patch('products/{product}/archive', [AdminProductController::class, 'archive'])
            ->name('products.archive');
    });

require __DIR__.'/auth.php';
