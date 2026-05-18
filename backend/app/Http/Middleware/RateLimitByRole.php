<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

// Role-Based Rate Limiting
// Different users deserve different access. Free users may get 60 requests/min, while premium users get 200/min.
// Example: SaaS app with tiered plans. Give more access to paying customers, without hurting free-tier users.
class RateLimitByRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $role = $user === null ? UserRole::EMPLOYEE->value : $user->role;
        $limits = [
            UserRole::ADMIN->value => 100,
            UserRole::HR_MANAGER->value => 150,
            UserRole::EMPLOYEE->value => 50,
        ];
        $maxAttempts = $limits[$role] ?? $limits[UserRole::EMPLOYEE->value];
        $key = "rate:{$role}:".($user === null ? $request->ip() : $user->id);

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($key);

            return response()->json(['message' => __('messages.too_many_requests')], 429, [
                'Retry-After' => (string) $retryAfter,
                'X-RateLimit-Limit' => (string) $maxAttempts,
                'X-RateLimit-Remaining' => '0',
            ]);
        }

        RateLimiter::hit($key, 60);

        return $next($request);
    }
}
