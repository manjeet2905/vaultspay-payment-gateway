# VaultsPay Laravel Payment Gateway

Laravel integration for **VaultsPay Hosted Checkout** returning verified
payment results as JSON.

The package: - Creates a hosted payment - Redirects the customer to
VaultsPay - Receives the return - Verifies the transaction using
transactionId - Returns full gateway response

No database required --- uses Laravel cache temporarily.

------------------------------------------------------------------------

## Installation

``` bash
composer require manjeet2905/vaultspay
```

Publish configuration:

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

## Usage

Create a payment route:

``` php
use Vp\VaultsPay\Facades\VaultsPay;

Route::get('/pay', function () {
    return VaultsPay::checkout(100, 'ORDER1001');
});
```

After payment, VaultsPay redirects to:

    /vaultspay/result/{reference}

The package verifies payment and returns JSON response.

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

## Status Values

  Status    Meaning
  --------- -------------------
  SUCCESS   Payment captured
  FAILED    Payment declined
  PENDING   Processing
  INVALID   Reference expired

------------------------------------------------------------------------

## Notes

-   Package uses cache to temporarily store transactionId
-   No database migration required
-   Works with APIs, SPAs and mobile apps

------------------------------------------------------------------------

## License

MIT
