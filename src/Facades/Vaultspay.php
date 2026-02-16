<?php
namespace Vp\VaultsPay\Facades;

use Illuminate\Support\Facades\Facade;

class VaultsPay extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'vaultspay';
    }

    public static function checkout($amount, $orderId)
    {
        return app('vaultspay')->checkout($amount, $orderId);
    }

}
