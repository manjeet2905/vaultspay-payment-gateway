<?php

namespace Vp\VaultsPay\SDK\Services;

use Vp\VaultsPay\SDK\Config;
use Vp\VaultsPay\SDK\Http\HttpClient;

class AuthService
{
    private static ?string $token = null;
    private static int $expiresAt = 0;

    private static ?string $channelName = null;
    private static ?string $channelCode = null;

    public static function getToken(Config $config): string
    {
        // Reuse token if still valid
        if (self::$token && time() < self::$expiresAt) {
            return self::$token;
        }

        $response = HttpClient::postForm(
            $config->baseUrl . '/external/v1/merchant-auth',
            [
                'clientId'     => $config->clientId,
                'clientSecret' => $config->clientSecret,

                // 🔴 REQUIRED FOR YOUR GATEWAY
                'currency'     => $config->currency,
                'channel'      => $config->channel,
            ]
        );

        if (empty($response['data']['access_token'])) {
            throw new \Exception('VaultsPay authentication failed: ' . json_encode($response));
        }

        // Save token
        self::$token = $response['data']['access_token'];

        $ttl = $response['data']['token_expiry'] ?? 300;
        self::$expiresAt = time() + $ttl - 30;

        // Capture store/channel returned by gateway
        if (!empty($response['data']['stores'][0])) {
            self::$channelName = $response['data']['stores'][0]['channelName'] ?? null;
            self::$channelCode = $response['data']['stores'][0]['channelCode'] ?? null;
        } else {
            throw new \Exception('VaultsPay auth succeeded but no store/channel returned');
        }

        return self::$token;
    }

    public static function getChannelName(): ?string
    {
        return self::$channelName;
    }

    public static function getChannelCode(): ?string
    {
        return self::$channelCode;
    }

    public static function invalidate(): void
    {
        self::$token = null;
        self::$expiresAt = 0;
        self::$channelName = null;
        self::$channelCode = null;
    }
}
