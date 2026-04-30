<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LoginRateLimiter
{
    // Max attempts before lockout
    private const MAX_ATTEMPTS = 5;

    // Lockout duration in seconds
    private const DECAY_SECONDS = 900; // 15 menit

    public function handle(Request $request, Closure $next)
    {
        if (! $request->isMethod('POST')) {
            return $next($request);
        }

        $key = $this->throttleKey($request);

        if ($this->isLockedOut($key)) {
            $seconds = Cache::get("login_lockout_until:{$key}") - time();
            Log::warning('Login lockout triggered', [
                'ip' => $request->ip(),
                'email' => $request->input('email'),
            ]);

            return back()
                ->withErrors(['email' => 'Terlalu banyak percobaan login. Coba lagi dalam '.ceil($seconds / 60).' menit.'])
                ->withInput($request->only('email'));
        }

        $response = $next($request);

        // If login failed (redirect back with errors)
        if ($this->loginFailed($response)) {
            $this->incrementAttempts($key);
            $attempts = Cache::get("login_attempts:{$key}", 0);
            $remaining = self::MAX_ATTEMPTS - $attempts;

            if ($remaining <= 0) {
                Cache::put("login_lockout_until:{$key}", time() + self::DECAY_SECONDS, self::DECAY_SECONDS);
                Log::warning('Account locked after failed attempts', [
                    'ip' => $request->ip(),
                    'email' => $request->input('email'),
                ]);
            }
        } else {
            // Successful login — clear attempts
            $this->clearAttempts($key);
        }

        return $response;
    }

    private function throttleKey(Request $request): string
    {
        // Key = hash of email + IP to prevent enumeration
        return sha1(strtolower($request->input('email', '')).'|'.$request->ip());
    }

    private function isLockedOut(string $key): bool
    {
        $until = Cache::get("login_lockout_until:{$key}", 0);
        if ($until && time() < $until) {
            return true;
        }
        // Expired lockout — clear
        if ($until && time() >= $until) {
            $this->clearAttempts($key);
        }

        return false;
    }

    private function incrementAttempts(string $key): void
    {
        $attempts = Cache::get("login_attempts:{$key}", 0) + 1;
        Cache::put("login_attempts:{$key}", $attempts, self::DECAY_SECONDS);
    }

    private function clearAttempts(string $key): void
    {
        Cache::forget("login_attempts:{$key}");
        Cache::forget("login_lockout_until:{$key}");
    }

    private function loginFailed($response): bool
    {
        return $response->getStatusCode() === 302
            && str_contains($response->headers->get('Location', ''), 'login');
    }
}
