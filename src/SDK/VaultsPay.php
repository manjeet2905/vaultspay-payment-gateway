<?php
namespace Vp\VaultsPay\SDK;

use Vp\VaultsPay\SDK\Config;
use Vp\VaultsPay\SDK\Services\AuthService;
use Vp\VaultsPay\SDK\Services\PaymentService;
use Vp\VaultsPay\SDK\Services\VerificationService;

class VaultsPay
{
    private Config $config;

    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    /*
    |--------------------------------------------------------------------------
    | Create Hosted Payment
    |--------------------------------------------------------------------------
    */
    public function createPaymentData(array $data): array
    {
        $token = AuthService::getToken($this->config);

        $payment = PaymentService::create($this->config, $token, $data);

        if (empty($payment['data']['paymentUrl']) || empty($payment['data']['paymentId'])) {
            throw new \Exception('VaultsPay payment initialization failed: ' . json_encode($payment));
        }

        return [
            'url'       => $payment['data']['paymentUrl'],
            'paymentId' => $payment['data']['paymentId'],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Verify Payment (Authoritative — transactionId only)
    |--------------------------------------------------------------------------
    */
    public function verify(string $transactionId): array
    {
        $token = AuthService::getToken($this->config);

        return VerificationService::verify(
            $this->config,
            $token,
            $transactionId
        );
    }

}
