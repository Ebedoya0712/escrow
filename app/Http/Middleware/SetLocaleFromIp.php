<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Stevebauman\Location\Facades\Location;

class SetLocaleFromIp
{
    public function handle(Request $request, Closure $next)
    {
        // Obtener la IP del visitante. Para pruebas locales, puedes forzar una IP.
        // $ip = '8.8.8.8'; // Ejemplo de IP de USA
        $ip = $request->ip();

        // Usar el paquete para obtener la ubicación
        if ($position = Location::get($ip)) {
            // Lista de códigos de países de habla hispana
            $spanishCountries = ['ES', 'MX', 'CO', 'AR', 'PE', 'VE', 'CL', 'EC', 'GT', 'CU', 'BO', 'HN', 'PY', 'SV', 'NI', 'CR', 'PA', 'UY', 'DO', 'PR'];

            if (in_array($position->countryCode, $spanishCountries)) {
                App::setLocale('es');
            } else {
                App::setLocale('en');
            }
        } else {
            // Si la IP no se puede determinar, usar el idioma por defecto (inglés)
            App::setLocale('en');
        }

        return $next($request);
    }
}