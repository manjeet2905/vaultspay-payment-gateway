<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Vp\VaultsPay\Facades\VaultsPay;

Route::post('/vaultspay/webhook', function () {

    $transactionId = request('vpTransactionId');
    $status = request('status');

    if (!$transactionId || !$status) {
        return response()->json(['error' => 'invalid payload'], 400);
    }

    // Normalize status to SDK format
    $status = strtoupper($status);

    // store result for browser polling
    Cache::put('vp_status_'.$transactionId, $status, now()->addMinutes(15));

    Log::info('VaultsPay Webhook', [
        'transactionId' => $transactionId,
        'status' => $status
    ]);

    return response()->json(['received' => true]);
});




