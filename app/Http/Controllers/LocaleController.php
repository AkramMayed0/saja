<?php

namespace App\Http\Controllers;

class LocaleController extends Controller
{
    public function switch(string $locale)
    {
        if (in_array($locale, ['ar', 'en'])) {
            session()->put('locale', $locale);
        }

        // Only redirect back to our own site (the Referer header can point anywhere)
        $previous = url()->previous();
        if (parse_url($previous, PHP_URL_HOST) !== request()->getHost()) {
            $previous = route('home');
        }

        return redirect($previous);
    }
}
