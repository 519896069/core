<?php

namespace Core\Components\Middleware;

use Closure;
use Illuminate\Http\Response;
use Spatie\Permission\Exceptions\UnauthorizedException;

class Authenticate
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!($user = auth()->user()))
            throw new UnauthorizedException(Response::HTTP_UNAUTHORIZED, '请登录');
        if (!$user->isAdminister() && $user->cant($request->route()->getName()))
            throw new UnauthorizedException(Response::HTTP_FORBIDDEN, '没有访问权限');
        return $next($request);
    }
}