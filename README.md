# VaultsPay Laravel Payment Gateway

Laravel integration for **VaultsPay Hosted Checkout**.

This package allows you to accept payments using VaultsPay while keeping
your application simple --- no session handling, no manual verification
logic, and no frontend polling required.

------------------------------------------------------------------------

## Installation

``` bash
composer require vp/vaultspay
```

Publish config:

``` bash
php artisan vendor:publish --tag=vaultspay-config
```

------------------------------------------------------------------------

## Configuration (.env)

    VAULTSPAY_BASE_URL=https://testapi.vaultspay.com/
    VAULTSPAY_CLIENT_ID=YOUR_CLIENT_ID
    VAULTSPAY_CLIENT_SECRET=YOUR_SECRET

    VAULTSPAY_CURRENCY=AED
    VAULTSPAY_CHANNEL=web

------------------------------------------------------------------------

## Basic Usage

``` php
use Vp\VaultsPay\Facades\VaultsPay;

Route::get('/pay', function () {
    return VaultsPay::checkout(100, 'ORDER123');
});
```

------------------------------------------------------------------------

## Example Response

``` json
{
  "status": "SUCCESS",
  "raw": {
    "message": "Successful.",
    "data": {
      "transactionStatus": "SUCCESS",
      "currency": "AED",
      "transactionId": "AP_TEST_123456789",
      "transactionTime": "2026-01-01T12:00:00.000000Z",
      "transactionAmount": 100,
      "transactionVat": 5,
      "transactionFee": 2,
      "transactionSubTotalAmount": 93,
      "countryCode": "AE",
      "clientReference": "ORDER_DEMO_1001",
      "customerDetails": {
        "name": "John Doe",
        "email": "customer@example.com",
        "phoneNumber": "500000000",
        "cardNumber": "4111XXXXXXXX1111",
        "cardHolderName": "JOHN DOE",
        "cardIssuer": "Demo Bank",
        "cardBrand": "visa",
        "cardType": "credit",
        "cardCountry": "AE",
        "cardExpiry": "12/2030"
      }
    },
    "code": 200
  }
}
```

------------------------------------------------------------------------

## License

MIT
