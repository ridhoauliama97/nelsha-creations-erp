<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supported = array_keys(config('app.supported_locales', []));

        $fallback = $this->pick(config('app.locale'), $supported)
            ?? $this->pick(config('app.fallback_locale'), $supported)
            ?? ($supported[0] ?? 'en');

        $queryLang = $this->pick($request->query('lang'), $supported);

        $user = $request->user();

        // Explicit ?lang= wins for this request. Otherwise the authenticated
        // user's preferred language (profile setting) is authoritative and
        // takes precedence over any stale session locale, so changing the
        // profile language is reflected immediately in the panels.
        $locale = $queryLang
            ?? $this->pick($user?->language ?? null, $supported)
            ?? $this->pick(Session::get('locale'), $supported)
            ?? $fallback;

        if ($queryLang !== null && Session::get('locale') !== $queryLang) {
            Session::put('locale', $queryLang);
        }

        // Keep the session in sync with the resolved locale for signed-in
        // users, so a previously stored locale cannot override the profile.
        if ($user && Session::get('locale') !== $locale) {
            Session::put('locale', $locale);
        }

        if (App::getLocale() !== $locale) {
            App::setLocale($locale);
        }

        return $next($request);
    }

    protected function pick(mixed $candidate, array $supported): ?string
    {
        return is_string($candidate) && in_array($candidate, $supported, true)
            ? $candidate
            : null;
    }
}
