<?php

use App\Http\Controllers\Api\PurchaseOrderApiController;
use App\Http\Controllers\Api\PurchaseRequestApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Purchase Request & Purchase Order
|--------------------------------------------------------------------------
|
| Semua route di sini otomatis diawali prefix /api
| Contoh: GET /api/purchase-requests
|
*/

Route::name('api.')->group(function () {
	// ── Purchase Request ──────────────────────────────────────────────────────────
	Route::apiResource('purchase-requests', PurchaseRequestApiController::class);

	// ── Purchase Order ────────────────────────────────────────────────────────────
	Route::apiResource('purchase-orders', PurchaseOrderApiController::class);
});