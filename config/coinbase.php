<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Coinbase Commerce API Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your Coinbase Commerce API credentials here.
    | Get your API key from: https://commerce.coinbase.com/dashboard/settings
    |
    */

    'api_key' => env('COINBASE_API_KEY', ''),
    'webhook_secret' => env('COINBASE_WEBHOOK_SECRET', ''),
    'api_url' => env('COINBASE_API_URL', 'https://api.commerce.coinbase.com'),
    'verify_ssl' => env('COINBASE_VERIFY_SSL', true),
    
    /*
    |--------------------------------------------------------------------------
    | Supported Cryptocurrencies
    |--------------------------------------------------------------------------
    */
    
    'currencies' => [
        'BTC' => 'Bitcoin',
        'ETH' => 'Ethereum',
        'USDC' => 'USD Coin',
        'USDT' => 'Tether',
        'DAI' => 'Dai',
        'LTC' => 'Litecoin',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Redirect URLs
    |--------------------------------------------------------------------------
    */
    
    'redirect_url' => env('COINBASE_REDIRECT_URL', env('APP_URL') . '/payment/success'),
    'cancel_url' => env('COINBASE_CANCEL_URL', env('APP_URL') . '/payment/cancel'),
    
    /*
    |--------------------------------------------------------------------------
    | Webhook Settings
    |--------------------------------------------------------------------------
    */
    
    'webhook_url' => env('COINBASE_WEBHOOK_URL', env('APP_URL') . '/webhook/coinbase'),
];
