<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsCustomer
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        if ($request->user()->role !== 'customer') {
            return response()->json([
                'message' => 'Forbidden. Customer access only.'
            ], 403);
        }

        return $next($request);
    }
}