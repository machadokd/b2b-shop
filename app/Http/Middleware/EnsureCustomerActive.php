<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->customer && $user->customer->is_blocked) {
            auth()->logout();
            $request->session()->invalidate();

            return redirect()->route('customer.login')
                ->withErrors(['email' => 'A sua conta está bloqueada. Contacte o suporte.']);
        }

        return $next($request);
    }
}
