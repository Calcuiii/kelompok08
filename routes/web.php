<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShoppingController;
use Illuminate\Support\Facades\Auth;


// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Login pengguna
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'loginAnggota'])->name('login');

// Register pengguna
Route::get('/register', [AuthController::class, 'showRegisterFormAnggota'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Home route for regular users
//Route::get('/home', [HomeController::class, 'index'])->name('home');

// Profile route
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

// Resource route for products
Route::resource('products', ProductController::class);
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

Route::get('/user', function () {
    return view('user');
})->name('user.view');

Route::get('/shopping', [ShoppingController::class, 'shopping'])->name('shopping.index');
Route::post('/shopping', [ShoppingController::class, 'buy'])->name('shopping.buy');
Route::get('/shopping/shopping.receipt/{id}', [ShoppingController::class, 'generateReceipt'])->name('shopping.receipt');

Route::get('/shopping', [ShoppingController::class, 'shopping'])->name('shopping');

Route::get('/user', function () {
    return view('user');
})->name('user');

Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login'); // Arahkan ke halaman user setelah logout
})->name('logout');

Route::post('/shopping/buyMultiple', [ShoppingController::class, 'buyMultiple'])->name('shopping.buyMultiple');
Route::post('/shopping/shopping.receipt', [ShoppingController::class, 'generateReceipt'])->name('shopping.shopping.receipt');
