<?php

namespace Vp\VaultsPay;

class VaultsPayManager
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function make($currency = null, $channel = null): Client
    {
        return new Client(
            new SDK\VaultsPay([
                'base_url' => $this->config['base_url'],
                'client_id' => $this->config['client_id'],
                'client_secret' => $this->config['client_secret'],
                'currency' => $currency ?? $this->config['currency'],
                'channel'  => $channel ?? $this->config['channel'],
            ])
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Public checkout helper
    |--------------------------------------------------------------------------
    */
    public function checkout($amount, $orderId)
    {
        return $this->make()->checkout($amount, $orderId);
    }
}
