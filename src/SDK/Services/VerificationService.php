<?php
namespace Vp\VaultsPay\SDK\Services;

use Vp\VaultsPay\SDK\Config;
use Vp\VaultsPay\SDK\Http\HttpClient;

class VerificationService
{
    /*
    |--------------------------------------------------------------------------
    | Verify payment using transactionId (VaultsPay authoritative identifier)
    |--------------------------------------------------------------------------
    */
    public static function verify(Config $config, string $token, string $transactionId): array
    {
        $response = HttpClient::postJson(
            $config->baseUrl . '/external/v1/get-transaction-details',
            [
                'transactionId' => $transactionId,
            ],
            $token
        );

        return [
            'status' => self::normalizeStatus($response),
            'raw'    => $response,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Normalize VaultsPay statuses
    |--------------------------------------------------------------------------
    */
    private static function normalizeStatus(array $response): string
    {
        if (! isset($response['data'])) {
            return 'PENDING';
        }

        $status = $response['data']['transactionStatus'] ?? $response['data']['status'] ?? 'PENDING';

        $status = strtoupper($status);

        return match ($status) {
            'SUCCESS', 'CAPTURED', 'PAID'        => 'SUCCESS',
            'FAILED', 'DECLINED', 'REJECTED'     => 'FAILED',
            'PENDING', 'PROCESSING', 'INITIATED' => 'PENDING',
            default => 'PENDING',
        };
    }
}
