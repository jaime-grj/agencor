<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class SetLocaleFromBrowser
{
    public function handle($request, Closure $next)
    {
        $supportedLocales = Config::get('app.supported_locales', ['es']);

        // Get browser preferred languages
        $browserLocales = $request->getLanguages();

        foreach ($browserLocales as $locale) {
            $locale = substr($locale, 0, 2); // Use only language code (e.g. 'en' from 'en-US')
            if (in_array($locale, $supportedLocales)) {
                App::setLocale($locale);
                break;
            }
        }

        return $next($request);
    }
}