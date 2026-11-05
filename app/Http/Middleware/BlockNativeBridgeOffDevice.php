<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\NativeApp;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * NativePHP registra las rutas _native/api/* sin autenticación ni CSRF.
 * Solo tienen sentido dentro de la app, así que en el servidor web no existen.
 */
class BlockNativeBridgeOffDevice
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_if($request->is('_native/*') && ! NativeApp::running() && getenv('JUMP_BRIDGE_PORT') === false, 404);

        return $next($request);
    }
}
