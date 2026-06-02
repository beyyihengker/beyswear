<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user && in_array($user->role, $roles)) {
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'Akses ditolak! Anda tidak memiliki izin.');
    }
}