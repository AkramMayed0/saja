<?php

namespace App\Services;

class CurrencyService
{
    /**
     * Detect region in Yemen based on IP and return appropriate currency.
     * YER for Northern governorates, SAR for Southern governorates.
     */
    public function detectYemenRegion($ip)
    {
        // Placeholder for IP geolocation logic
        // Ideally, you would use a service like GeoIP.
        $region = 'North'; 
        
        if ($region === 'South') {
            return 'SAR';
        }
        
        return 'YER';
    }

    /**
     * Convert USD amount to local currency.
     */
    public function convertToLocal($amountUsd, $currency)
    {
        // Example dynamic rates
        $rates = [
            'YER' => 530,  // USD to YER (Sanaa standard)
            'SAR' => 3.75, // USD to SAR
        ];

        $rate = $rates[$currency] ?? 1; // Default to 1 (USD) if currency not found

        return $amountUsd * $rate;
    }
}
