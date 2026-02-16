<?php

namespace Vp\VaultsPay\SDK\Services;

use Vp\VaultsPay\SDK\Config;
use Vp\VaultsPay\SDK\Http\HttpClient;
use Vp\VaultsPay\SDK\Services\AuthService;
use Vp\VaultsPay\SDK\Services\MethodService;

class PaymentService
{
    public static function create(Config $config, string $token, array $data): array
    {
        $channelName = AuthService::getChannelName();
        $channelCode = AuthService::getChannelCode();

        if (!$channelName || !$channelCode) {
            throw new \Exception('VaultsPay channel information missing. Authentication did not return store data.');
        }

        // Normalize incoming Laravel fields
        $payload = [
            'amount'          => $data['amount'],
            'currency'        => $data['currency'] ?? $config->currency,
            'clientReference' => $data['order_id'] ?? $data['reference'],
            'redirectUrl'     => $data['redirect_url'],
            'callBackUrl'     => $data['callback_url'],
            'expiryInSeconds' => $data['expiry'] ?? 900,
            'channelName'     => $channelName,
        ];

        // Optional fields
        if (!empty($data['email'])) {
            $payload['customerEmail'] = $data['email'];
        }

        if (!empty($data['mobile'])) {
            $payload['customerMobile'] = $data['mobile'];
        }

        // Fetch schemaCode dynamically (depends on channel + currency)
        $schemaCode = MethodService::getSchema($config, $token, $payload['currency']);
        $payload['schemaCode'] = $schemaCode;

        return HttpClient::postJson(
            $config->baseUrl . '/external/v1/initialize-merchant-payment',
            $payload,
            $token
        );
    }
}
