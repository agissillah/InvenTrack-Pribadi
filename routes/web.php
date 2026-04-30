<?php

use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseRequestController;
use Illuminate\Support\Facades\Route;

// Route landing page aplikasi.
Route::get('/', function () {
    return view('welcome');
});

// Route untuk menampilkan halaman login khusus admin.
Route::get('/login/admin', function () {
    return view('auth.login-admin');
})->name('login.admin');

// Route untuk menampilkan halaman login khusus kasir.
Route::get('/login/kasir', function () {
    return view('auth.login-kasir');
})->name('login.kasir');

// Route resource untuk modul Purchase Request.
// Satu baris ini otomatis membuat 7 endpoint CRUD standar:
// index, create, store, show, edit, update, destroy.
Route::resource('/purchase-requests', PurchaseRequestController::class)->names('purchase-requests');

// Route resource untuk modul Purchase Order.
Route::resource('/purchase-orders', PurchaseOrderController::class)->names('purchase-orders');
