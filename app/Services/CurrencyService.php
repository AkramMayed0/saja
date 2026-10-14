<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CurrencyService
{
    public const REGION_YE = 'YE';
    public const REGION_GLOBAL = 'GLOBAL';

    public const CURRENCY_SAR = 'SAR';
    public const CURRENCY_USD = 'USD';

    /**
     * Resolve the active user region, automatically detecting via IP / CDN headers on first visit
     * while honoring explicit manual user choice stored in session.
     */
    public function resolveUserRegion(?Request $request = null): string
    {
        // Clear any stale manual override from previous testing
        if (Session::has('region_manually_set')) {
            Session::forget(['region_manually_set', 'user_region', 'user_currency', 'detected_country']);
        }

        // 1. If region already resolved in this session, return it immediately
        if (Session::has('user_region')) {
            return Session::get('user_region');
        }

        $request = $request ?: request();

        // 2. Perform Auto Geo-Detection
        $country = $this->detectCountry($request);

        // 3. Map Country Code to Region
        $region = ($country === 'YE') ? self::REGION_YE : self::REGION_GLOBAL;
        $currency = $this->getCurrencyForRegion($region);

        // 4. Store in session
        Session::put('user_region', $region);
        Session::put('user_currency', $currency);
        Session::put('detected_country', $country);

        return $region;
    }

    /**
     * Detect country code (ISO 3166-1 alpha-2) using multi-layer pro detection:
     * 1. Test override (.env)
     * 2. Edge / CDN Reverse Proxy headers (Cloudflare CF-IPCountry, CloudFront, etc.)
     * 3. Local/Private IP handling (safe for localhost development)
     * 4. Cached IP geolocation lookup (Fast HTTP with strict timeout)
     * 5. Safe default fallback
     */
    public function detectCountry(Request $request): string
    {
        // Layer 1: Developer testing override
        if ($override = config('currency.test_country')) {
            return strtoupper(trim($override));
        }

        // Layer 2: CDN / Reverse Proxy Headers (Zero latency, 100% accurate on production)
        $cdnHeader = $request->header('CF-IPCountry')
            ?? $request->header('CloudFront-Viewer-Country')
            ?? $request->header('X-Country-Code')
            ?? $request->header('GEOIP_COUNTRY_CODE');

        if ($cdnHeader && strlen($cdnHeader) === 2 && strtoupper($cdnHeader) !== 'XX') {
            return strtoupper($cdnHeader);
        }

        // Layer 3: Client IP resolution
        $ip = config('currency.test_ip') ?: $request->ip();

        if (!$ip || $this->isPrivateOrLocalIp($ip)) {
            // Default to configured region's country on local/private addresses
            return config('currency.default_region', self::REGION_YE) === self::REGION_YE ? 'YE' : 'US';
        }

        // Layer 4: Cached GeoIP lookup (24h cache to avoid duplicate external requests)
        $cacheKey = 'geoip_country_' . md5($ip);
        $cacheTtl = config('currency.cache_ttl', 86400);

        try {
            return Cache::remember($cacheKey, $cacheTtl, function () use ($ip) {
                return $this->lookupCountryFromIp($ip);
            });
        } catch (\Throwable $e) {
            Log::warning('GeoIP Cache/Lookup failed: ' . $e->getMessage());
            return config('currency.default_region', self::REGION_YE) === self::REGION_YE ? 'YE' : 'US';
        }
    }

    /**
     * Perform an external HTTP lookup for public IP with strict timeout
     * so user page load is never slowed down.
     */
    protected function lookupCountryFromIp(string $ip): string
    {
        $timeout = (float) config('currency.timeout', 1.5);

        // Service 1: ip-api.com (fast and free)
        try {
            $response = Http::timeout($timeout)->get("http://ip-api.com/json/{$ip}?fields=status,countryCode");
            if ($response->successful()) {
                $data = $response->json();
                if (($data['status'] ?? '') === 'success' && !empty($data['countryCode'])) {
                    return strtoupper($data['countryCode']);
                }
            }
        } catch (\Throwable $e) {
            // Fallback to secondary service
        }

        // Service 2: ipapi.co fallback
        try {
            $response = Http::timeout($timeout)->get("https://ipapi.co/{$ip}/country/");
            if ($response->successful()) {
                $country = trim($response->body());
                if (strlen($country) === 2) {
                    return strtoupper($country);
                }
            }
        } catch (\Throwable $e) {
            // Silently fall back to default
        }

        return config('currency.default_region', self::REGION_YE) === self::REGION_YE ? 'YE' : 'US';
    }

    /**
     * Check if IP is local, loopback, or private RFC1918 range.
     */
    protected function isPrivateOrLocalIp(string $ip): bool
    {
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost'], true)) {
            return true;
        }

        return filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) === false;
    }

    /**
     * Explicitly set user region (e.g. from header switcher).
     */
    public function setRegion(string $region): void
    {
        $validRegions = array_keys(config('currency.regions', []));
        if (!in_array($region, $validRegions, true)) {
            $region = config('currency.default_region', self::REGION_YE);
        }

        $currency = $this->getCurrencyForRegion($region);

        Session::put('user_region', $region);
        Session::put('user_currency', $currency);
        Session::put('region_manually_set', true);

        // Synchronize active cart item prices to new currency
        $this->syncCartPrices($currency, $region);
    }

    /**
     * Synchronize items in active cart when currency changes,
     * so user isn't charged SAR amounts under USD or vice versa.
     */
    public function syncCartPrices(string $currency, string $region): void
    {
        $userId = auth()->id();
        $sessionId = Session::getId();

        $cart = Cart::with('cartItems.product')
            ->where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId)->whereNull('user_id');
                }
            })->first();

        if (!$cart) {
            return;
        }

        $cart->update(['currency' => $currency]);

        foreach ($cart->cartItems as $item) {
            if ($item->product) {
                $newPrice = $this->getProductPrice($item->product, $region);
                $item->update([
                    'price' => $newPrice,
                    'currency' => $currency,
                ]);
            }
        }
    }

    /**
     * Get the current active user region ('YE' or 'GLOBAL').
     */
    public function getUserRegion(): string
    {
        return Session::get('user_region', function () {
            return $this->resolveUserRegion();
        });
    }

    /**
     * Get current active user currency code ('SAR' or 'USD').
     */
    public function getUserCurrency(): string
    {
        $region = $this->getUserRegion();
        return $this->getCurrencyForRegion($region);
    }

    /**
     * Get the currency code for a given region.
     */
    public function getCurrencyForRegion(string $region): string
    {
        $config = config("currency.regions.{$region}");
        return $config['currency'] ?? self::CURRENCY_SAR;
    }

    /**
     * Get currency symbol (e.g. 'ر.س' or '$').
     */
    public function getCurrencySymbol(?string $currency = null): string
    {
        $currency = $currency ?: $this->getUserCurrency();
        return $currency === self::CURRENCY_USD ? '$' : 'ر.س';
    }

    /**
     * Check if currently active region is Yemen.
     */
    public function isYemen(): bool
    {
        return $this->getUserRegion() === self::REGION_YE;
    }

    /**
     * Get the price of a product for current (or specified) region.
     */
    public function getProductPrice($product, ?string $region = null): float
    {
        $region = $region ?: $this->getUserRegion();

        if ($region === self::REGION_YE) {
            // Inside Yemen -> SAR
            return (float) ($product->price_sar ?? $product->price ?? 0);
        }

        // Outside Yemen -> USD
        return (float) ($product->price_usd ?? round(($product->price ?? 0) / 3.75, 2));
    }

    /**
     * Format a price with the current (or specified) currency symbol.
     */
    public function formatPrice(float $amount, ?string $currency = null): string
    {
        $currency = $currency ?: $this->getUserCurrency();
        $formatted = number_format($amount, 2);

        if ($currency === self::CURRENCY_USD) {
            return '$' . $formatted;
        }

        return $formatted . ' ر.س';
    }

    /**
     * Get all supported regions with metadata.
     */
    public function getAllRegions(): array
    {
        return config('currency.regions', []);
    }

    /**
     * Get details for current or specified region.
     */
    public function getRegionDetails(?string $region = null): array
    {
        $region = $region ?: $this->getUserRegion();
        return config("currency.regions.{$region}", [
            'code' => $region,
            'currency' => 'SAR',
            'symbol' => 'ر.س',
            'name_ar' => 'اليمن',
            'name_en' => 'Yemen',
            'flag' => '🇾🇪',
        ]);
    }
}
