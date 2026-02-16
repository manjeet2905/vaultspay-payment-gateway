<?php
namespace Vp\VaultsPay;

use Vp\VaultsPay\SDK\VaultsPay as SDK;
use Illuminate\Support\Facades\Cache;

class Client
{
    protected SDK $sdk;

    public function __construct(SDK $sdk)
    {
        $this->sdk = $sdk;
    }

    /*
    |--------------------------------------------------------------------------
    | Create Payment (returns URL + paymentId)
    |--------------------------------------------------------------------------
    */
    public function create(array $data): array
    {
        return $this->sdk->createPaymentData($data);
    }

    /*
    |--------------------------------------------------------------------------
    | Redirect helper (backward compatibility)
    |--------------------------------------------------------------------------
    */
    public function redirect(array $data): string
    {
        return $this->create($data)['url'];
    }

    /*
    |--------------------------------------------------------------------------
    | Verify payment
    |--------------------------------------------------------------------------
    */
    public function verify(string $paymentId): array
    {
        return $this->sdk->verify($paymentId);
    }

    /*
    |--------------------------------------------------------------------------
    | Hosted Checkout (STATELESS)
    |--------------------------------------------------------------------------
    */
    public function checkout(float $amount, string $orderId)
    {
        $payment = $this->create([
            'amount'       => $amount,
            'order_id'     => $orderId,
            'redirect_url' => route('vaultspay.result', ['reference'=>$orderId]),
            'callback_url' => route('vaultspay.callback'),
        ]);

        // store paymentId for 30 minutes
        Cache::put('vaultspay_' . $orderId, $payment['paymentId'], now()->addMinutes(30));

        return redirect()->away($payment['url']);
    }

}
