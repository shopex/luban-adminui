<?php
namespace Shopex\AdminUI\Http\Middleware;

use Closure;

class Middleware
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}