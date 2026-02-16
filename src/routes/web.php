<?php

use Illuminate\Support\Facades\Route;
use Vp\VaultsPay\Http\Controllers\VaultsPayController;

Route::prefix('vaultspay')->name('vaultspay.')->group(function () {

    // Browser lands here after payment
    // Route::get('/result', [VaultsPayController::class, 'result'])->name('result');
    Route::get('/result/{reference}', [VaultsPayController::class, 'result'])->name('result');

    // VaultsPay webhook (authoritative confirmation)
    Route::post('/callback', [VaultsPayController::class, 'callback'])->name('callback');

    Route::get('/status/{reference}', [VaultsPayController::class,'status']);

});
