<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Facades\Location;  

class SetLocaleFromIp
{
    public function handle(Request $request, Closure $next)
    {
        // Obtener la IP real del visitante.
        $ip = $request->ip();
        $locale = 'en'; // Idioma por defecto

        // Usar el paquete para obtener la ubicación
        if ($position = Location::get($ip)) {
            $spanishCountries = ['ES', 'MX', 'CO', 'AR', 'PE', 'VE', 'CL', 'EC', 'GT', 'CU', 'BO', 'HN', 'PY', 'SV', 'NI', 'CR', 'PA', 'UY', 'DO', 'PR'];
            if (in_array($position->countryCode, $spanishCountries)) {
                $locale = 'es';
            }
        }

        // Establecer el idioma para la solicitud actual
        App::setLocale($locale);

        // Si el usuario ha iniciado sesión, guardamos su preferencia de idioma en la base de datos
        if (Auth::check() && Auth::user()->locale !== $locale) {
            Auth::user()->update(['locale' => $locale]);
        }

        return $next($request);
    }
}