<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEmpleadoEmpresa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $empresa = null)
    {
        if ($empresa == 'CUPON' && session('ID_Empresa') != 'CUPON') {
            return redirect()->route('cupones.index');
        }

        if ($empresa == null && session('ID_Empresa') == 'CUPON') {
            return redirect()->route('empresas.index');
        }

        if ($empresa == null && session('ID_Rol') == 2) {
            return redirect()->route('cupones.canjear');
        }

        return $next($request);
    }
}
