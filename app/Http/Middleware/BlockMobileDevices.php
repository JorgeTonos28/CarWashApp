<?php

namespace App\Http\Middleware;

use App\Models\AppSetting;
use Closure;
use Illuminate\Http\Request;

class BlockMobileDevices
{
    public function handle(Request $request, Closure $next)
    {
        if ($this->isMobile($request) && !AppSetting::allowMobileAccess()) {
            if ($request->routeIs('login') || $request->routeIs('logout')) {
                return $next($request);
            }

            return response()->view('mobile-warning', [], 403);
        }

        return $next($request);
    }

    protected function isMobile(Request $request): bool
    {
        $agent = $request->header('User-Agent', '');
        return (bool) preg_match('/(android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini|mobile|tablet)/i', $agent);
    }
}
