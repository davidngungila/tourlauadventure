<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password form.
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a reset link to the given user.
     * Enhanced with rate limiting and better security.
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Rate limiting: max 3 requests per 15 minutes per IP
        $key = 'password-reset:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "Too many password reset attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        // Enhanced validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email:rfc,dns|max:255',
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email address is too long.',
        ]);

        if ($validator->fails()) {
            RateLimiter::hit($key, 900); // 15 minutes
            return back()->withErrors($validator)->withInput();
        }

        // Check if user exists (for security, don't reveal if email doesn't exist)
        $user = \App\Models\User::where('email', $request->email)->first();
        
        // Always return success message to prevent email enumeration
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Increment rate limiter
        RateLimiter::hit($key, 900); // 15 minutes

        // Always show success message (security best practice)
        if ($status === Password::RESET_LINK_SENT || $status === Password::INVALID_USER) {
            return back()->with('status', 'If that email address exists in our system, we have sent a password reset link to it.');
        }

        return back()->with('status', 'If that email address exists in our system, we have sent a password reset link to it.');
    }
}
