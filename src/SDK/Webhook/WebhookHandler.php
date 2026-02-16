<?php

namespace Vp\VaultsPay\SDK\Webhook;

use Vp\VaultsPay\SDK\VaultsPay;

class WebhookHandler
{
    public static function handle(VaultsPay $vaultspay): array
    {
        $raw = file_get_contents('php://input');

        if (!$raw) {
            http_response_code(400);
            return ['error' => 'Empty webhook'];
        }

        $payload = json_decode($raw, true);

        if (!$payload) {
            http_response_code(400);
            return ['error' => 'Invalid JSON'];
        }

        $paymentId = $payload['paymentId'] ?? null;

        if (!$paymentId) {
            http_response_code(400);
            return ['error' => 'paymentId missing'];
        }

        $status = $vaultspay->verifyPayment($paymentId);

        return [
            'status' => $status,
            'payment_id' => $paymentId
        ];
    }
}
