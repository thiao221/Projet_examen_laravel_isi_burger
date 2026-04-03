<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use Illuminate\Support\Facades\Route;

// Accueil : Redirige vers le catalogue des burgers
Route::get('/', function () {
    return redirect()->route('products.index');
});

// ESPACE CLIENT (connecté)
Route::middleware(['auth'])->group(function () {

    // Redirection du Dashboard : On peut soit afficher une vue, soit rediriger vers l'accueil
    Route::get('/dashboard', function () {
        return redirect()->route('products.index'); // Redirige vers les produits au lieu de l'erreur 404
    })->name('dashboard');

    // Catalogue des burgers
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    // Ajout du POST pour gérer l'ajout au panier ou la commande directe
    Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Commandes
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/my', [OrderController::class, 'myOrders'])->name('orders.my');
    Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
});

// ESPACE GESTIONNAIRE (Admin)
Route::middleware(['auth', 'role:gestionnaire'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // CRUD Produits admin
        Route::resource('products', AdminProductController::class);
        Route::patch('products/{product}/archive', [AdminProductController::class, 'archive'])->name('products.archive');

        // Gestion des Commandes
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
        Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
        // Paiement
        Route::post('/orders/{order}/pay', [\App\Http\Controllers\PaymentController::class, 'store'])
            ->name('orders.pay');
    });

require __DIR__.'/auth.php';
