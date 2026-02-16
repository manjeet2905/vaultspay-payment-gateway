<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Credentials
    |--------------------------------------------------------------------------
    */
    'base_url'      => env('VAULTSPAY_BASE_URL'),
    'client_id'     => env('VAULTSPAY_CLIENT_ID'),
    'client_secret' => env('VAULTSPAY_CLIENT_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Default Transaction Settings
    |--------------------------------------------------------------------------
    */
    'currency'      => env('VAULTSPAY_CURRENCY', 'AED'),
    'channel'       => env('VAULTSPAY_CHANNEL', 'web'),

    /*
    |--------------------------------------------------------------------------
    | Package Routes
    |--------------------------------------------------------------------------
    | These are route NAMES, not URLs
    | The package will generate full URLs automatically
    */
    'result_route'   => env('VAULTSPAY_RESULT_ROUTE', 'vaultspay.result'),
    'callback_route' => env('VAULTSPAY_CALLBACK_ROUTE', 'vaultspay.callback'),

    /*
    |--------------------------------------------------------------------------
    | UI Behaviour (result page polling)
    |--------------------------------------------------------------------------
    */
    'poll_interval' => env('VAULTSPAY_POLL_INTERVAL', 2000),
    'max_attempts'  => env('VAULTSPAY_MAX_ATTEMPTS', 10),

];
