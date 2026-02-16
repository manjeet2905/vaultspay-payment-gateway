<?php

namespace Vp\VaultsPay\SDK;

class Config
{
    public string $baseUrl;
    public string $clientId;
    public string $clientSecret;

    // REQUIRED FOR MULTI CHANNEL GATEWAY
    public string $currency;
    public string $channel;

    public function __construct(array $config)
    {
        $this->baseUrl = rtrim($config['base_url'] ?? '', '/');
        $this->clientId = $config['client_id'] ?? '';
        $this->clientSecret = $config['client_secret'] ?? '';

        // runtime parameters (important)
        $this->currency = $config['currency'] ?? 'AED';
        $this->channel  = $config['channel'] ?? 'card';

        if (!$this->baseUrl || !$this->clientId || !$this->clientSecret) {
            throw new \InvalidArgumentException('VaultsPay config missing required credentials');
        }
    }
}
