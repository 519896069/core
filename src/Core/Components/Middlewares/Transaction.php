<?php

namespace Core\Components\Middlewares;


use Closure;
use Illuminate\Support\Facades\DB;

class Transaction
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
        DB::transaction(function () use (&$response, $next, $request, $guard) {
            $response = $next($request);
        });
        return $response;
    }
}