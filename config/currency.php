<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Region & Currency
    |--------------------------------------------------------------------------
    |
    | When auto-detection is inconclusive or on local environment, this region
    | will be used as the default. Supported: 'YE', 'GLOBAL'
    |
    */
    'default_region' => env('DEFAULT_REGION', 'YE'),

    /*
    |--------------------------------------------------------------------------
    | Supported Regions & Currencies
    |--------------------------------------------------------------------------
    */
    'regions' => [
        'YE' => [
            'code' => 'YE',
            'currency' => 'SAR',
            'symbol' => 'ر.س',
            'symbol_en' => 'SAR',
            'name_ar' => 'اليمن',
            'name_en' => 'Yemen',
            'flag' => '🇾🇪',
            'price_field' => 'price_sar',
            'description_ar' => 'الدفع بالريال السعودي (ر.س) داخل اليمن',
        ],
        'GLOBAL' => [
            'code' => 'GLOBAL',
            'currency' => 'USD',
            'symbol' => '$',
            'symbol_en' => '$',
            'name_ar' => 'خارج اليمن',
            'name_en' => 'International',
            'flag' => '🌍',
            'price_field' => 'price_usd',
            'description_ar' => 'الدفع بالدولار ($) لجميع الدول حول العالم',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | GeoIP Lookup Settings
    |--------------------------------------------------------------------------
    |
    | Cache TTL in seconds (24 hours default), and HTTP request timeout
    | in seconds to ensure visitors never experience page load lag.
    |
    */
    'cache_ttl' => env('GEOIP_CACHE_TTL', 86400),
    'timeout' => env('GEOIP_TIMEOUT', 1.5),

    /*
    |--------------------------------------------------------------------------
    | Testing & Development Overrides
    |--------------------------------------------------------------------------
    |
    | You can test Geo-detection on localhost by setting either of these in .env:
    | GEOIP_TEST_COUNTRY=YE   (or US, SA, etc.)
    | GEOIP_TEST_IP=82.114.160.1  (Yemeni public IP)
    |
    */
    'test_country' => env('GEOIP_TEST_COUNTRY', null),
    'test_ip' => env('GEOIP_TEST_IP', null),
];
