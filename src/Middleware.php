<?php
namespace Shopex\AdminUI;

use Closure;

class Middleware
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}