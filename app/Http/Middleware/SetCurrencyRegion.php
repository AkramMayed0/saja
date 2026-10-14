<?php

namespace App\Http\Middleware;

use App\Services\CurrencyService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetCurrencyRegion
{
    protected CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Resolve user region (Auto Geo-Detection on first hit, or honors session)
        $this->currencyService->resolveUserRegion($request);

        $currentRegion = $this->currencyService->getUserRegion();
        $currentCurrency = $this->currencyService->getUserCurrency();
        $currencySymbol = $this->currencyService->getCurrencySymbol($currentCurrency);
        $regionDetails = $this->currencyService->getRegionDetails($currentRegion);

        // 2. Share seamlessly with all Blade templates
        View::share('currentRegion', $currentRegion);
        View::share('currentCurrency', $currentCurrency);
        View::share('currencySymbol', $currencySymbol);
        View::share('regionDetails', $regionDetails);
        View::share('currencyService', $this->currencyService);

        return $next($request);
    }
}
