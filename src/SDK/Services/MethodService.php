<?php

namespace Vp\VaultsPay\SDK\Services;

use Vp\VaultsPay\SDK\Config;
use Vp\VaultsPay\SDK\Http\HttpClient;
use Vp\VaultsPay\SDK\Services\AuthService;

class MethodService
{
    private static ?string $schemaCode = null;

    public static function getSchema(Config $config, string $token, string $currency): string
    {
        if (self::$schemaCode) {
            return self::$schemaCode;
        }

        $channelName = AuthService::getChannelName();

        if (!$channelName) {
            throw new \Exception('Channel name missing before fetching payment methods');
        }

        $response = HttpClient::postJson(
            $config->baseUrl . '/external/v1/get-vaultspay-allowed-payment-methods',
            [
                'currencyCode' => $currency,
                'channelName'  => $channelName
            ],
            $token
        );

        if (empty($response['data'])) {
            throw new \Exception('No payment methods returned: ' . json_encode($response));
        }

        $method = $response['data'][0] ?? null;

        if (!$method || empty($method['code'])) {
            throw new \Exception('Invalid payment methods response: ' . json_encode($response));
        }

        self::$schemaCode = $method['code'];

        return self::$schemaCode;
    }
}
