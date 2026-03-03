<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminSessionAuth
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless(session('admin_seq_id'), 403, 'Unauthorized.');
        return $next($request);
    }
}
